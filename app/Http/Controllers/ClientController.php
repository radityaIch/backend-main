<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index()
    {
        $client = Client::with('articleLink')->latest()->get();

        return response()->json([
            'data' => $client
        ], 200);
    }

    public function store(Request $request)
    {
        $client = new Client();

        $rules = [
            'image' => 'image|file|max:2048',
            'title' => 'required',
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
            $client->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $client->title = $request->title;
        $client->save();

        return response()->json([
            'message' => 'Client created successfully',
            'data' => $client
        ], 201);
    }

    public function show($id)
    {
        $client = client::where('id', $id)->with('articleLink')->first();

        if (!$client) {
            return response()->json([
                'message' => 'Client not found'
            ], 400);
        }

        return response()->json([
            'data' => $client
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'message' => 'Client not found'
            ], 400);
        }

        $rules = [
            'image' => 'image|file|max:2048',
            'title' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $file = $request->file('image');

        if ($file != null) {
            if ($client->image != '' && $client->image != null) {
                $oldImage = $client->image;
                $nameSplit = explode('/', $oldImage);
                $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
                Storage::delete($fileName);
            }

            $string = rand(22, 5033);
            $name = $string . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filename = $file->storeAs('images', $name);
            $client->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $client->title = $request->title;
        $client->save();

        return response()->json([
            'message' => 'Client updated successfully',
            'data' => $client
        ], 201);
    }

    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'message' => 'Client not found'
            ], 400);
        }

        if ($client->image != '' && $client->image != null) {
            $oldImage = $client->image;
            $nameSplit = explode('/', $oldImage);
            $fileName = 'images/' . $nameSplit[count($nameSplit) - 1];
            Storage::delete($fileName);
        }

        $client->delete();

        return response()->json([
            'message' => 'Client deleted successfully'
        ], 200);
    }
}
