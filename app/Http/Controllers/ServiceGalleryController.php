<?php

namespace App\Http\Controllers;

use App\Models\ServiceGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceGalleryController extends Controller
{
    public function index()
    {
        $serviceGallery = ServiceGallery::latest()->get();

        return response()->json([
            'data' => $serviceGallery
        ], 200);
    }

    public function store(Request $request)
    {
        $serviceGallery = new ServiceGallery();

        $rules = [
            'category' => 'required',
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
            $serviceGallery->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $serviceGallery->category = $request->category;
        $serviceGallery->save();

        return response()->json([
            'message' => 'Service Gallery created successfully',
            'data' => $serviceGallery
        ], 201);
    }

    public function show($id)
    {
        $serviceGallery = ServiceGallery::find($id);

        if (!$serviceGallery) {
            return response()->json([
                'message' => 'Service Gallery not found'
            ], 400);
        }

        return response()->json([
            'data' => $serviceGallery
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $serviceGallery = ServiceGallery::find($id);

        if (!$serviceGallery) {
            return response()->json([
                'message' => 'Service Gallery not found'
            ], 400);
        }

        $rules = [
            'category' => 'required',
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
            if ($serviceGallery->image != '' && $serviceGallery->image != null) {
                $oldImage = $serviceGallery->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $serviceGallery->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $serviceGallery->category = $request->category;
        $serviceGallery->save();

        return response()->json([
            'message' => 'Service Gallery updated successfully',
            'data' => $serviceGallery
        ], 201);
    }

    public function destroy($id)
    {
        $serviceGallery = ServiceGallery::find($id);

        if (!$serviceGallery) {
            return response()->json([
                'message' => 'Service Gallery not found'
            ], 400);
        }

        if ($serviceGallery->image != '' && $serviceGallery->image != null) {
            $oldImage = $serviceGallery->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $serviceGallery->delete();

        return response()->json([
            'message' => 'Service Gallery deleted successfully'
        ], 200);
    }
}
