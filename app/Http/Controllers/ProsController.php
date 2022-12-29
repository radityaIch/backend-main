<?php

namespace App\Http\Controllers;

use App\Models\Pros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProsController extends Controller
{
    public function index()
    {
        $pros = Pros::get();

        return response()->json([
            'data' => $pros
        ], 200);
    }

    public function store(Request $request)
    {
        $pros = new Pros();

        $rules = [
            'title' => 'required',
            'description' => 'required',
            'image' => 'image|file|max:2048|required'
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
            $pros->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $pros->title = $request->title;
        $pros->description = $request->description;
        $pros->save();

        return response()->json([
            'message' => 'Pros created successfully',
            'data' => $pros
        ], 201);
    }

    public function show($id)
    {
        $pros = Pros::find($id);

        if (!$pros) {
            return response()->json([
                'message' => 'Pros not found'
            ], 400);
        }

        return response()->json([
            'data' => $pros
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $pros = Pros::find($id);

        if (!$pros) {
            return response()->json([
                'message' => 'Pros not found'
            ], 400);
        }

        $rules = [
            'title' => 'required',
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
            if ($pros->image != '' && $pros->image != null) {
                $oldImage = $pros->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $pros->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $pros->title = $request->title;
        $pros->description = $request->description;
        $pros->save();

        return response()->json([
            'message' => 'Pros updated successfully',
            'data' => $pros
        ], 201);
    }

    public function destroy($id)
    {
        $pros = Pros::find($id);

        if (!$pros) {
            return response()->json([
                'message' => 'pros not found'
            ], 400);
        }

        if ($pros->image != '' && $pros->image != null) {
            $oldImage = $pros->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $pros->delete();

        return response()->json([
            'message' => 'Pros deleted successfully'
        ], 200);
    }
}
