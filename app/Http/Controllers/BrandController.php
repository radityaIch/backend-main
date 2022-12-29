<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::get();

        return response()->json([
            'data' => $brand
        ], 200);
    }

    public function store(Request $request)
    {
        $brand = new Brand();

        $rules = [
            'brand_name' => 'required',
            'logo_light' => 'image|file|max:2048|required',
            'logo_dark' => 'image|file|max:2048|required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $file = $request->file('logo_light');

        if ($file != null) {
            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $brand->logo_light = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $file = $request->file('logo_dark');

        if ($file != null) {
            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $brand->logo_dark = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $brand->brand_name = $request->brand_name;
        $brand->save();

        return response()->json([
            'message' => 'Brand created successfully',
            'data' => $brand
        ], 201);
    }

    public function show($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'message' => 'Brand not found'
            ], 400);
        }

        return response()->json([
            'data' => $brand
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'message' => 'Brand not found'
            ], 400);
        }

        $rules = [
            'brand_name' => 'required',
            'logo_light' => 'image|file|max:2048',
            'logo_dark' => 'image|file|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $file = $request->file('logo_light');

        if ($file != null) {
            if ($brand->logo_light != '' && $brand->logo_light != null) {
                $oldImage = $brand->logo_light;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $brand->logo_light = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $file = $request->file('logo_dark');

        if ($file != null) {
            if ($brand->logo_dark != '' && $brand->logo_dark != null) {
                $oldImage = $brand->logo_dark;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $brand->logo_dark = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $brand->brand_name = $request->brand_name;
        $brand->save();

        return response()->json([
            'message' => 'Brand updated successfully',
            'data' => $brand
        ], 201);
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                'message' => 'Brand not found'
            ], 400);
        }

        if ($brand->logo_light != '' && $brand->logo_light != null) {
            $oldImage = $brand->logo_light;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        if ($brand->logo_dark != '' && $brand->logo_dark != null) {
            $oldImage = $brand->logo_dark;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully'
        ], 200);
    }
}
