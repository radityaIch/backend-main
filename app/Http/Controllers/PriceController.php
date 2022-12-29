<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PriceController extends Controller
{
    public function index()
    {
        $price = Price::latest()->get();

        return response()->json([
            'data' => $price
        ]);
    }

    public function store(Request $request)
    {
        $price = new Price();

        $rules = [
            'title' => 'required',
            'type' => 'required',
            'image' => 'required|image|file|max:2048',
            'file' => 'file|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $image = $request->file('image');
        $file = $request->file('file');
        $string = rand(22, 5033);

        if ($file != null) {
            $name = preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('files', $name);
            $price->file = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $name = $string . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
        $filename = $image->storeAs('images', $name);
        $price->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;

        $price->title = $request->title;
        $price->type = $request->type;
        $price->save();

        return response()->json([
            'message' => 'Price created successfully',
            'data' => $price
        ], 201);
    }

    public function show($id)
    {
        $price = Price::find($id);

        if (!$price) {
            return response()->json([
                'message' => 'Price not found'
            ], 400);
        }

        return response()->json([
            'data' => $price
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $price = Price::find($id);

        if (!$price) {
            return response()->json([
                'message' => 'Price not found'
            ], 400);
        }

        $rules = [
            'title' => 'required',
            'type' => 'required',
            'image' => 'image|file|max:2048',
            'file' => 'file|max:2048'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $image = $request->file('image');
        $file = $request->file('file');

        if ($file != null) {
            if ($price->file != '' && $price->file != null) {
                $oldFile = $price->file;
                $nameSplit = explode('/', $oldFile);
                $fileName = 'files/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }
            $name = preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('files', $name);
            $price->file = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        if ($image != null) {
            if ($price->image != '' && $price->image != null) {
                $oldImage = $price->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $image->getClientOriginalName());
            $filename = $image->storeAs('images', $name);
            $price->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $price->title = $request->title;
        $price->type = $request->type;
        $price->save();

        return response()->json([
            'message' => 'price updated successfully',
            'data' => $price
        ], 201);
    }

    public function destroy($id)
    {
        $price = Price::find($id);

        if (!$price) {
            return response()->json([
                'message' => 'Price not found'
            ], 400);
        }

        if ($price->file != '' && $price->file != null) {
            $oldFile = $price->file;
            $nameSplit = explode('/', $oldFile);
            $fileName = 'files/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        if ($price->image != '' && $price->image != null) {
            $oldImage = $price->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $price->delete();

        return response()->json([
            'message' => 'Price deleted successfully'
        ], 200);
    }
}
