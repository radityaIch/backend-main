<?php

namespace App\Http\Controllers;

use App\Models\TravelPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TravelPermitController extends Controller
{
    public function index()
    {
        $permit = TravelPermit::latest()->get();

        return response()->json([
            'data' => $permit
        ], 200);
    }

    public function store(Request $request)
    {
        $permit = new TravelPermit();

        $rules = [
            'no_do' => 'required',
            'pengirim' => 'required',
            'alamat_muat' => 'required',
            'alamat_kirim' => 'required',
            'no_telp' => 'required',
            'nopol' => 'required',
            'driver' => 'required',
            'unit' => 'required',
            'pengiriman' => 'required',
            'harga_jual' => 'required',
            'harga_beli' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $permit->no_do = $request->no_do;
        $permit->pengirim = $request->pengirim;
        $permit->alamat_muat = $request->alamat_muat;
        $permit->alamat_kirim = $request->alamat_kirim;
        $permit->no_telp = $request->no_telp;
        $permit->nopol = $request->nopol;
        $permit->driver = $request->driver;
        $permit->unit = $request->unit;
        $permit->pengiriman = $request->pengiriman;
        $permit->harga_jual = $request->harga_jual;
        $permit->harga_beli = $request->harga_beli;
        $permit->save();

        return response()->json([
            'message' => 'Permit Created Successfully',
            'data' => $permit
        ], 201);
    }

    public function show($id)
    {
        $permit = TravelPermit::find($id);

        if (!$permit) {
            return response()->json([
                'message' => 'Travel Permit not found'
            ], 400);
        }

        return response()->json([
            'data' => $permit
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $permit = TravelPermit::find($id);

        $rules = [
            'no_do' => 'required',
            'pengirim' => 'required',
            'alamat_muat' => 'required',
            'alamat_kirim' => 'required',
            'no_telp' => 'required',
            'nopol' => 'required',
            'driver' => 'required',
            'unit' => 'required',
            'pengiriman' => 'required',
            'harga_jual' => 'required',
            'harga_beli' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $permit->no_do = $request->no_do;
        $permit->pengirim = $request->pengirim;
        $permit->alamat_muat = $request->alamat_muat;
        $permit->alamat_kirim = $request->alamat_kirim;
        $permit->no_telp = $request->no_telp;
        $permit->nopol = $request->nopol;
        $permit->driver = $request->driver;
        $permit->unit = $request->unit;
        $permit->pengiriman = $request->pengiriman;
        $permit->harga_jual = $request->harga_jual;
        $permit->harga_beli = $request->harga_beli;
        $permit->save();

        return response()->json([
            'message' => 'Permit Updated Successfully',
            'data' => $permit
        ], 201);
    }

    public function destroy($id)
    {

        $permit = TravelPermit::find($id);

        if (!$permit) {
            return response()->json([
                'message' => 'Travel Permit not found'
            ], 400);
        }

        $permit->delete();

        return response()->json([
            'message' => 'Travel Permit deleted successfully'
        ], 200);
    }
}
