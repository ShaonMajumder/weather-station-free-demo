<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\SensorData;
use App\Events\ReceivedSensorData;

class SensorDataController extends Controller
{
    public function logInDevice(Request $request){

    }

    public function store(Request $request)
    {
        // add bearer token check
        // $token = $request->bearerToken();

        // if (!$token) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        $validator = Validator::make($request->all(), [
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $ipAddress = $request->ip(); 
            event(new ReceivedSensorData([
                'temperature' => $request->temperature,
                'humidity' => $request->humidity,
                'ip_address' => $ipAddress,
                'system_details' => $request->system_details
            ]));

            $sensorData = new SensorData([
                'temperature' => $request->input('temperature'),
                'humidity' => $request->input('humidity'),
            ]);
            $sensorData->save();

            Log::info('Received POST request:', ['data' => $request->all()]);

            return response()->json(['message' => 'Request handled successfully']);
        } catch (\Exception $e) {
            Log::error('Error handling POST request:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function showData(){
        $latestData = SensorData::latest()->first();
        if($latestData){
            return response()->json(['temperature' => $latestData->temperature, 'humidity' => $latestData->humidity]);    
        }else{

        }
    }

    

    public function showSensorDataView(Request $request)
    {
        $paginationLength = $request->input('length', 10);
        $paginationLength = max(1, (int)$paginationLength);
        $sensorData = SensorData::paginate($paginationLength);
        return view('sensor-data-archieve', compact('sensorData', 'paginationLength'));
    }
}

