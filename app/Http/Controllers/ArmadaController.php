<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArmadaController extends Controller
{
    public function index()
    {
        $armada = Armada::latest()->get();

        return response()->json([
            'data' => $armada
        ], 200);
    }

    public function store(Request $request)
    {
        $armada = new Armada();

        $rules = [
            'name' => 'required',
            'width' => 'required',
            'height' => 'required',
            'long' => 'required',
            'cbm' => 'required',
            'tonase' => 'required',
            'image' => 'image|file|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $file = $request->file('image');

        if ($file != null) {
            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $armada->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $armada->name = $request->name;
        $armada->width = $request->width;
        $armada->height = $request->height;
        $armada->long = $request->long;
        $armada->cbm = $request->cbm;
        $armada->tonase = $request->tonase;
        $armada->save();

        return response()->json([
            'message' => 'Armada created successfully',
            'data' => $armada
        ], 201);
    }


    public function show($id)
    {
        $armada = Armada::find($id);

        if (!$armada) {
            return response()->json([
                'message' => 'Armada not found'
            ], 400);
        }

        return response()->json([
            'data' => $armada
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $armada = Armada::find($id);

        if (!$armada) {
            return response()->json([
                'message' => 'Armada not found'
            ], 400);
        }

        $rules = [
            'name' => 'required',
            'width' => 'required',
            'height' => 'required',
            'long' => 'required',
            'cbm' => 'required',
            'tonase' => 'required',
            'image' => 'image|file|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $file = $request->file('image');

        if ($file != null) {
            if ($armada->image != '' && $armada->image != null) {
                $oldImage = $armada->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $armada->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $armada->name = $request->name;
        $armada->width = $request->width;
        $armada->height = $request->height;
        $armada->long = $request->long;
        $armada->cbm = $request->cbm;
        $armada->tonase = $request->tonase;
        $armada->save();

        return response()->json([
            'message' => 'Armada updated successfully',
            'data' => $armada
        ], 201);
    }

    public function destroy($id)
    {
        $armada = Armada::find($id);

        if (!$armada) {
            return response()->json([
                'message' => 'Armada not found'
            ], 400);
        }

        if ($armada->image != '' && $armada->image != null) {
            $oldImage = $armada->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $armada->delete();

        return response()->json([
            'message' => 'Armada deleted successfully'
        ], 200);
    }
}

