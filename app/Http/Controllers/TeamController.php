<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function index()
    {
        $team = Team::latest()->get();

        return response()->json([
            'data' => $team
        ], 200);
    }

    public function store(Request $request)
    {
        $team = new Team();

        $rules = [
            'name' => 'required',
            'position' => 'required',
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
            $team->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $team->name = $request->name;
        $team->position = $request->position;
        $team->save();

        return response()->json([
            'message' => 'Team created successfully',
            'data' => $team
        ], 201);
    }

    public function show($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json([
                'message' => 'Team not found'
            ], 400);
        }

        return response()->json([
            'data' => $team
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json([
                'message' => 'Team not found'
            ], 400);
        }

        $rules = [
            'name' => 'required',
            'position' => 'required',
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
            if ($team->image != '' && $team->image != null) {
                $oldImage = $team->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $team->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $team->name = $request->name;
        $team->position = $request->position;
        $team->save();

        return response()->json([
            'message' => 'Team updated successfully',
            'data' => $team
        ], 201);
    }

    public function destroy($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json([
                'message' => 'Team not found'
            ], 400);
        }

        if ($team->image != '' && $team->image != null) {
            $oldImage = $team->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $team->delete();

        return response()->json([
            'message' => 'Team deleted successfully'
        ], 200);
    }
}
