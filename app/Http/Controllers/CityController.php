<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index()
    {
        $city = City::latest()->get();

        return response()->json([
            'data' => $city
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $cities = explode("\n", $request->title);

        foreach ($cities as $city) {
            City::create([
                'title' => $city
            ]);
        }

        $city = City::all();

        return response()->json([
            'message' => 'City created successfully',
            'data' => $city
        ], 201);
    }

    public function show($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'message' => 'City not found'
            ], 400);
        }

        return response()->json([
            'data' => $city
        ], 200);
    }

    public function update(Request $request)
    {
        $city = City::all();

        if (!$city) {
            return response()->json([
                'message' => 'City not found'
            ], 400);
        }

        $rules = [
            'title' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $cities = explode("\n", $request->title);

        foreach ($city as $city) {
            $city->delete();
        }

        foreach ($cities as $city) {
            City::create([
                'title' => $city
            ]);
        }

        $city = City::all();

        return response()->json([
            'message' => 'City updated successfully',
            'data' => $city
        ], 201);
    }

    public function destroy($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'message' => 'City not found'
            ], 400);
        }

        $city->delete();

        return response()->json([
            'message' => 'City deleted successfully',
        ], 201);
    }
}
