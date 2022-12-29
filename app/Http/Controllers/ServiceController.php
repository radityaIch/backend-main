<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $service = Service::latest()->get();

        return response()->json([
            'data' => $service
        ], 200);
    }

    public function store(Request $request)
    {
        $service = new Service();

        $rules = [
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
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
            $service->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $service->title = $request->title;
        $service->subtitle = $request->subtitle;
        $service->description = $request->description;
        $service->save();

        return response()->json([
            'message' => 'Service created successfully',
            'data' => $service
        ], 201);
    }

    public function show($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'message' => 'Service not found'
            ], 400);
        }

        return response()->json([
            'data' => $service
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'message' => 'Service not found'
            ], 400);
        }

        $rules = [
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
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
            if ($service->image != '' && $service->image != null) {
                $oldImage = $service->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $service->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $service->title = $request->title;
        $service->subtitle = $request->subtitle;
        $service->description = $request->description;
        $service->save();

        return response()->json([
            'message' => 'Service updated successfully',
            'data' => $service
        ], 201);
    }

    public function destroy($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'message' => 'Service not found'
            ], 400);
        }

        if ($service->image != '' && $service->image != null) {
            $oldImage = $service->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $service->delete();

        return response()->json([
            'message' => 'Service deleted successfully'
        ], 200);
    }
}
