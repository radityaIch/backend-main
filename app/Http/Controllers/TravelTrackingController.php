<?php

namespace App\Http\Controllers;

use App\Models\TravelTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelTrackingController extends Controller
{
    public function store(Request $request)
    {
        $tracking = new TravelTracking();

        $rules = [
            'travel_permit_id' => 'required',
            'keterangan' => 'required',
            'kendala' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $tracking->travel_permit_id = $request->travel_permit_id;
        $tracking->keterangan = $request->keterangan;
        $tracking->kendala = $request->kendala;
        $tracking->save();

        return response()->json([
            'message' => 'Tracking Created Successfully',
            'data' => $tracking
        ], 201);
    }

    public function show($travel_permit_id)
    {
        $tracking = TravelTracking::where('travel_permit_id', $travel_permit_id)->get();

        if (!$tracking) {
            return response()->json([
                'message' => 'Tracking not found'
            ], 400);
        }

        return response()->json([
            'data' => $tracking
        ], 200);
    }
}
