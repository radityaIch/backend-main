<?php

namespace App\Http\Controllers;

use App\Models\TravelSupply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TravelSupplyController extends Controller
{
    public function store(Request $request)
    {
        $supply = new TravelSupply();

        $rules = [
            'travel_permit_id' => 'required',
            'barang' => 'required',
            'qty' => 'required',
            'keterangan' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $supply->travel_permit_id = $request->travel_permit_id;
        $supply->barang = $request->barang;
        $supply->qty = $request->qty;
        $supply->keterangan = $request->keterangan;
        $supply->save();

        return response()->json([
            'message' => 'Supply Created Successfully',
            'data' => $supply
        ], 201);
    }

    public function show($travel_permit_id)
    {
        $supply = TravelSupply::where('travel_permit_id', $travel_permit_id)->get();

        if (!$supply) {
            return response()->json([
                'message' => 'Supply not found'
            ], 400);
        }

        return response()->json([
            'data' => $supply
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $supply = TravelSupply::find($id);

        $rules = [
            'travel_permit_id' => 'required',
            'barang' => 'required',
            'qty' => 'required',
            'keterangan' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $supply->travel_permit_id = $request->travel_permit_id;
        $supply->barang = $request->barang;
        $supply->qty = $request->qty;
        $supply->keterangan = $request->keterangan;
        $supply->save();

        return response()->json([
            'message' => 'Supply Updated Successfully',
            'data' => $supply
        ], 201);
    }

    public function destroy($id)
    {
        $supply = TravelSupply::find($id);
        if (!$supply) {
            return response()->json([
                'message' => 'Supply not found'
            ], 400);
        }

        $supply->delete();

        return response()->json([
            'message' => 'Supply deleted successfully'
        ], 200);
    }

    public function clear($travel_permit_id)
    {
        $ids = explode(",", $travel_permit_id);
        $supply = TravelSupply::whereIn('travel_permit_id', $ids);
        if (!$supply) {
            return response()->json([
                'message' => 'Supply not found'
            ], 400);
        }

        $supply->delete();

        return response()->json([
            'message' => 'Supply deleted successfully'
        ], 200);
    }
}
