<?php

namespace App\Http\Controllers;

use App\Models\Cover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CoverController extends Controller
{
    public function index()
    {
        $cover = Cover::latest()->get();

        return response()->json([
            'data' => $cover
        ], 200);
    }

    public function store(Request $request)
    {
        $cover = new Cover();

        $rules = [
            'image' => 'image|file|max:2048',
            'category' => 'required',
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
            $cover->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $cover->category = $request->category;
        $cover->save();

        return response()->json([
            'message' => 'Cover created successfully',
            'data' => $cover
        ], 201);
    }

    public function show($id)
    {
        $cover = Cover::where('id', $id)->first();

        if (!$cover) {
            return response()->json([
                'message' => 'Cover not found'
            ], 400);
        }

        return response()->json([
            'data' => $cover
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $cover = Cover::find($id);

        if (!$cover) {
            return response()->json([
                'message' => 'Cover not found'
            ], 400);
        }

        $rules = [
            'image' => 'image|file|max:2048',
            'category' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $file = $request->file('image');

        if ($file != null) {
            if ($cover->image != '' && $cover->image != null) {
                $oldImage = $cover->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $cover->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $cover->category = $request->category;
        $cover->save();

        return response()->json([
            'message' => 'Cover updated successfully',
            'data' => $cover
        ], 201);
    }

    public function destroy($id)
    {
        $cover = Cover::find($id);

        if (!$cover) {
            return response()->json([
                'message' => 'Cover not found'
            ], 400);
        }

        if ($cover->image != '' && $cover->image != null) {
            $oldImage = $cover->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $cover->delete();

        return response()->json([
            'message' => 'Cover deleted successfully'
        ], 200);
    }
}
