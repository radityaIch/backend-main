<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::latest()->get();

        return response()->json([
            'data' => $contact
        ], 200);
    }

    public function store(Request $request)
    {
        $contact = new Contact();

        $rules = [
            'type' => 'required',
            'title' => 'required',
            'contact' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $contact->type = $request->type;
        $contact->title = $request->title;
        $contact->contact = $request->contact;
        $contact->save();

        return response()->json([
            'message' => 'Contact created successfully',
            'data' => $contact
        ], 201);
    }

    public function show($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found'
            ], 400);
        }

        return response()->json([
            'data' => $contact
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found'
            ], 400);
        }

        $rules = [
            'type' => 'required',
            'title' => 'required',
            'contact' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $contact->type = $request->type;
        $contact->title = $request->title;
        $contact->contact = $request->contact;
        $contact->save();

        return response()->json([
            'message' => 'Contact updated successfully',
            'data' => $contact
        ], 201);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact not found'
            ], 400);
        }

        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully',
        ], 201);
    }
}
