<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    public function getData()
    {        
        $data = DB::table(DB::raw("(
                SELECT *,
                CASE
                WHEN infant_gender = 'M' THEN 'Male'
                WHEN infant_gender = 'F' THEN 'Female'
                ELSE '-'
                END AS gender
                FROM newborn
                )Z"))->get();

        return response()->json($data);
    }

    public function dataByFilterDate($date)
    {        
        $data = DB::table(DB::raw("(
            SELECT *,
            CASE
            WHEN infant_gender = 'M' THEN 'Male'
            WHEN infant_gender = 'F' THEN 'Female'
            ELSE '-'
            END AS gender
            FROM newborn
            WHERE DATE(STR_TO_DATE(birth_datetime, '%d-%m-%Y %H:%i')) = '$date'
            )Z"))->get();

        return response()->json($data);
    }

    public function dataByFilterYear($year)
    {
        $getData = DB::table(DB::raw("(
            SELECT
            month,
            year,
            COUNT(CASE WHEN infant_gender = 'M' THEN 1 END) as mtotal,
            COUNT(CASE WHEN infant_gender = 'F' THEN 1 END) as ftotal,
            AVG(CASE WHEN infant_gender = 'M' THEN weight ELSE '0' END) AS mavg,
            AVG(CASE WHEN infant_gender = 'F' THEN weight ELSE '0' END) AS favg
            FROM (
            SELECT
                SUBSTRING(birth_datetime, 4, 2) AS month,
                SUBSTR(birth_datetime, 7, 4) AS year,
                infant_gender,
                weight
            FROM newborn
            ) AS subquery
            WHERE year = '$year'
            GROUP BY month, year
        )Z"))->get();

        $month = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

        $data = [];
        foreach ($month as $key) {
            $found = false;
            foreach ($getData as $row) {
                if ($row->month === $key) {
                    $data[] = [$key, $row->mtotal, $row->mavg, $row->ftotal, $row->favg];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $data[] = [$key, 0, 0, 0, 0];
            }
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            $id = DB::table('newborn')->max('id');
            $id_seq = $request->input('infant_gender');

            $validator = Validator::make($request->all(), [
                'mother_name' => 'required|string|max:50',
                'mother_age' => 'required|integer|min:0',
                'gestational_age' => 'required|integer|min:0',
                'infant_gender' => 'required|array',
                'infant_gender.*' => 'in:M,F',
                'birth_datetime' => 'required|array',
                'birth_datetime.*' => 'date',
                'height' => 'required|array',
                'height.*' => 'numeric|min:0',
                'weight' => 'required|array',
                'weight.*' => 'numeric|min:0',
                'description' => 'array',
                'description.*' => 'nullable|string|max:50',
            ]);
    
            $validator->setAttributeNames([
                'motherName' => 'Mother Name',
                'motherAge' => 'Mother Age',
                'gestationalAge' => 'Gestational Age',
                'infant_gender' => 'Infant Gender',
                'birth_datetime' => 'Birth Date and Time',
                'height' => 'Height',
                'weight' => 'Weight',
                'description' => 'Description',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->getMessageBag();

                $formattedErrors = [];
                foreach ($errors->toArray() as $column => $messages) {
                    $formattedErrors[$column] = $messages[0];
                }

                return response()->json(['errors' => $formattedErrors]);
            }

            foreach ($id_seq as $key => $value) {

                if(!empty($request->input('description')[$key])){
                    $description = $request->input('description')[$key];
                }else{
                    $description = null;
                }

                if(!empty($request->input('birth_datetime')[$key])){
                    $datetimeString = $request->input('birth_datetime')[$key];
                    $timestamp = strtotime($datetimeString);

                    $datebirth = date('d-m-Y H:i', $timestamp);
                }

                DB::table('newborn')
                ->insert([
                    'id' => $id+1,
                    'id_seq' => $key+1,
                    'mother_name' => $request->input('mother_name'),
                    'mother_age' => $request->input('mother_age'),
                    'gestational_age' => $request->input('gestational_age'),
                    'infant_gender' => $value,
                    'birth_datetime'  => $datebirth,
                    'height' => $request->input('height')[$key],
                    'weight' => $request->input('weight')[$key],
                    'description' => $description
                ]);
            }

            DB::commit();
            $msg = "Data deleted successfully!";
            $indctr = "1";
            return response()->json(['msg' => $msg, 'indctr' => $indctr]);
        } catch (Exception $ex) {
            DB::rollback();
            $msg = "An error occurred on :" . $ex;
            $indctr = "0";
            return response()->json(['msg' => $msg, 'indctr' => $indctr]);
        }
    }

    public function show($id)
    {
        $main = DB::table('newborn')
        ->where('id',$id)
        ->first();

        $data = DB::table('newborn')
        ->select('*', DB::raw("DATE_FORMAT(STR_TO_DATE(birth_datetime, '%d-%m-%Y %H:%i'), '%Y-%m-%dT%H:%i') as datebirth"))
        ->where('id', $id)
        ->get();
        
        return response()->json(['main'=>$main, 'data'=>$data]);
    }

    public function update(Request $request, $id)
    {
        try {
            $id = $id;
            $idSeq = $request->input('infant_gender');

            $validator = Validator::make($request->all(), [
                'mother_name' => 'required|string|max:50',
                'mother_age' => 'required|integer|min:0',
                'gestational_age' => 'required|integer|min:0',
                'infant_gender' => 'required|array',
                'infant_gender.*' => 'in:M,F',
                'birth_datetime' => 'required|array',
                'birth_datetime.*' => 'date',
                'height' => 'required|array',
                'height.*' => 'numeric|min:0',
                'weight' => 'required|array',
                'weight.*' => 'numeric|min:0',
                'description' => 'array',
                'description.*' => 'nullable|string|max:50',
            ]);
    
            $validator->setAttributeNames([
                'motherName' => 'Mother Name',
                'motherAge' => 'Mother Age',
                'gestationalAge' => 'Gestational Age',
                'infant_gender' => 'Infant Gender',
                'birth_datetime' => 'Birth Date and Time',
                'height' => 'Height',
                'weight' => 'Weight',
                'description' => 'Description',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->getMessageBag();

                $formattedErrors = [];
                foreach ($errors->toArray() as $column => $messages) {
                    $formattedErrors[$column] = $messages[0];
                }

                return response()->json(['errors' => $formattedErrors]);
            }

            foreach ($idSeq as $key => $value) {     
                $id_seq = $key + 1;
                $getData = DB::table('newborn')
                ->whereRaw("id = '$id' AND id_seq = '$id_seq'")
                ->first();
                
                if(!empty($request->input('description')[$key])){
                    $description = $request->input('description')[$key];
                }else{
                    $description = null;
                }

                if(!empty($request->input('birth_datetime')[$key])){
                    $datetimeString = $request->input('birth_datetime')[$key];
                    $timestamp = strtotime($datetimeString);

                    $datebirth = date('d-m-Y H:i', $timestamp);
                }

                if(empty($getData)){
                    DB::table('newborn')
                    ->insert([
                        'id' => $id,
                        'id_seq' => $id_seq,
                        'mother_name' => $request->input('mother_name'),
                        'mother_age' => $request->input('mother_age'),
                        'gestational_age' => $request->input('gestational_age'),
                        'infant_gender' => $value,
                        'birth_datetime'  => $datebirth,
                        'height' => $request->input('height')[$key],
                        'weight' => $request->input('weight')[$key],
                        'description' => $description
                    ]);
                }else{
                    DB::table('newborn')
                    ->where('id', $id)
                    ->where('id_seq', $id_seq)
                    ->update([
                        'mother_name' => $request->input('mother_name'),
                        'mother_age' => $request->input('mother_age'),
                        'gestational_age' => $request->input('gestational_age'),
                        'infant_gender' => $value,
                        'birth_datetime'  => $datebirth,
                        'height' => $request->input('height')[$key],
                        'weight' => $request->input('weight')[$key],
                        'description' => $description
                    ]);
                }
            }
            
            DB::commit();
            $msg = "Data updated successfully!";
            $indctr = "1";
            return response()->json(['msg' => $msg, 'indctr' => $indctr]);
        } catch (Exception $ex) {
            DB::rollback();
            $msg = "An error occurred on :" . $ex;
            $indctr = "0";
            return response()->json(['msg' => $msg, 'indctr' => $indctr]);
        }
    }

    public function destroy($id, $id_seq)
    {
        try {
            DB::table('newborn')
            ->where('id', $id)
            ->where('id_seq', $id_seq)
            ->delete();
            
            DB::commit();
            $msg = "Data deleted successfully!";
            $indctr = "1";
            return response()->json(['msg' => $msg, 'indctr' => $indctr]);
        } catch (Exception $ex) {
            DB::rollback();
            $msg = "An error occurred on :" . $ex;
            $indctr = "0";
            return response()->json(['msg' => $msg, 'indctr' => $indctr]);
        }
    }
}
