<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUs = AboutUs::with('misi')->latest()->get();

        return response()->json([
            'data' => $aboutUs
        ], 200);
    }

    public function store(Request $request)
    {
        $aboutUs = new AboutUs();

        $rules = [
            'tagline' => 'required',
            'visi' => 'required',
            'description' => 'required',
            'nib' => 'required',
            'image_1' => 'required|image|file|max:2048',
            'image_2' => 'required|image|file|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $image1 = $request->file('image_1');
        $image2 = $request->file('image_2');

        // image1
        $string = rand(22, 5033);
        $name = $string . '_' . preg_replace('/\s+/', '_', $image1->getClientOriginalName());
        $filename = $image1->storeAs('images', $name);
        $aboutUs->image_1 = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;

        // image2
        $string = rand(22, 5033);
        $name = $string . '_' . preg_replace('/\s+/', '_', $image2->getClientOriginalName());
        $filename = $image2->storeAs('images', $name);
        $aboutUs->image_2 = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;

        $aboutUs->tagline = $request->tagline;
        $aboutUs->visi = $request->visi;
        $aboutUs->description = $request->description;
        $aboutUs->nib = $request->nib;
        $aboutUs->save();

        return response()->json([
            'message' => 'About Us created successfully',
            'data' => $aboutUs
        ], 201);
    }

    public function show($id)
    {
        $aboutUs = AboutUs::where('id', $id)->with('misi')->first();

        if (!$aboutUs) {
            return response()->json([
                'message' => 'About Us not found'
            ], 400);
        }

        return response()->json([
            'data' => $aboutUs
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $aboutUs = AboutUs::find($id);

        if (!$aboutUs) {
            return response()->json([
                'message' => 'About Us not found'
            ], 400);
        }

        $rules = [
            'tagline' => 'required',
            'visi' => 'required',
            'description' => 'required',
            'nib' => 'required',
            'image_1' => 'image|file|max:2048',
            'image_2' => 'image|file|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $image1 = $request->file('image_1');
        $image2 = $request->file('image_2');

        if ($image1 != null) {
            if ($aboutUs->image_1 != '' && $aboutUs->image_1 != null) {
                $oldImage = $aboutUs->image_1;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $image1->getClientOriginalName());
            $filename = $image1->storeAs('images', $name);
            $aboutUs->image_1 = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        if ($image2 != null) {
            if ($aboutUs->image_2 != '' && $aboutUs->image_2 != null) {
                $oldImage = $aboutUs->image_2;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $image2->getClientOriginalName());
            $filename = $image2->storeAs('images', $name);
            $aboutUs->image_2 = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $aboutUs->tagline = $request->tagline;
        $aboutUs->visi = $request->visi;
        $aboutUs->description = $request->description;
        $aboutUs->nib = $request->nib;
        $aboutUs->save();

        return response()->json([
            'message' => 'About Us updated successfully',
            'data' => $aboutUs
        ], 201);
    }

    public function destroy($id)
    {
        $aboutUs = AboutUs::find($id);

        if (!$aboutUs) {
            return response()->json([
                'message' => 'About Us not found'
            ], 400);
        }

        if ($aboutUs->image_1 != '' && $aboutUs->image_1 != null) {
            $oldImage = $aboutUs->image_1;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        if ($aboutUs->image_2 != '' && $aboutUs->image_2 != null) {
            $oldImage = $aboutUs->image_2;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $aboutUs->delete();

        return response()->json([
            'message' => 'About Us deleted successfully',
        ], 201);
    }
}
