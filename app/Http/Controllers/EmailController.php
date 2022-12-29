<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;

class EmailController extends Controller
{
    public function postEmail(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        Mail::send(
            'email',
            array(
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'msg' => $request->message,
                'subject' => $request->subject,
            ),
            function ($message) {
                $message->from('web@mjt-tlpartner.com');
                $message->to('kontak@mjt-tlpartner.com', 'kontak.mjt-tlpartner')
                    ->subject('Website Email');
            }
        );

        return response()->json([
            'message' => 'Email sent successfully'
        ], 200);
    }
}
