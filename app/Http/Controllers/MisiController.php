<?php

namespace App\Http\Controllers;

use App\Models\Misi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MisiController extends Controller
{
    public function index()
    {
        $misi = Misi::all();

        return response()->json([
            'data' => $misi
        ], 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'about_us_id' => 'required',
            'misi' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $misies = explode("\n", $request->misi);

        foreach ($misies as $misi) {
            Misi::create([
                'about_us_id' => $request->about_us_id,
                'misi' => $misi
            ]);
        }

        $misi = Misi::where('about_us_id', $request->about_us_id)->get();

        return response()->json([
            'message' => 'Misi created successfully',
            'data' => $misi
        ], 201);
    }

    public function show($aboutUsId)
    {
        $misi = Misi::where('about_us_id', $aboutUsId)->get();

        if (!$misi) {
            return response()->json([
                'message' => 'Misi not found'
            ], 400);
        }

        return response()->json([
            'data' => $misi
        ], 200);
    }

    public function update(Request $request, $aboutUsId)
    {
        $misi = Misi::where('about_us_id', $aboutUsId)->get();

        if (!$misi) {
            return response()->json([
                'message' => 'Misi not found'
            ], 400);
        }

        $rules = [
            'about_us_id' => 'required',
            'misi' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $misies = explode("\n", $request->misi);

        foreach ($misi as $misi) {
            $misi->delete();
        }

        foreach ($misies as $misi) {
            Misi::create([
                'about_us_id' => $request->about_us_id,
                'misi' => $misi
            ]);
        }

        $misi = Misi::where('about_us_id', $aboutUsId)->get();

        return response()->json([
            'message' => 'Misi updated successfully',
            'data' => $misi
        ], 201);
    }

    public function destroy($id)
    {
        $misi = Misi::find($id);

        if (!$misi) {
            return response()->json([
                'message' => 'Misi not found'
            ], 400);
        }

        $misi->delete();

        return response()->json([
            'message' => 'Misi deleted successfully',
        ], 201);
    }

    public function deleteAll($aboutUsId)
    {
        $misi = Misi::where('about_us_id', $aboutUsId)->get();

        if (!$misi) {
            return response()->json([
                'message' => 'Misi not found'
            ], 400);
        }

        foreach ($misi as $misi) {
            $misi->delete();
        }

        return response()->json([
            'message' => 'Misi deleted successfully',
        ], 201);
    }
}
