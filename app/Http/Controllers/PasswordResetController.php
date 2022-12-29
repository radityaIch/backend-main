<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetToken;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PasswordResetSuccessNotification;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            "email" => "required|email|"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $data = $validator->validated();

        $user = User::where('email', $data["email"])->first();
        if (!$user) {
            return response()->json([
                'message' => 'Cannot find user with the email'
            ], 404);
        }

        $token = strtoupper(Str::random(8));

        $passwordReset = PasswordResetToken::updateOrCreate(
            ['email' => $user->email],
            [
                'token' => hash('md5', $token),
                "expires_at" => Carbon::now()->addMinute(30)
            ],
        );

        if ($user && $passwordReset) {
            $user->notify(new PasswordResetNotification($token));
        }

        return response()->json([
            'message' => 'a password reset code has been sent to your email'
        ]);
    }

    public function find($token)
    {
        $passwordReset = PasswordResetToken::where('token', hash('md5', $token))->first();
        if (!$passwordReset) {
            return response()->json([
                'message' => 'Invalid password reset code'
            ], 404);
        }

        if (Carbon::now()->greaterThan($passwordReset->expires_at)) {
            $passwordReset->delete();
            return response()->json([
                'messsage' => 'The password reset code given has expired'
            ], 404);
        }

        return response()->json($passwordReset);
    }

    public function reset(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'token' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $data = $validator->validated();

        $passwordReset = PasswordResetToken::where([
            ['token', hash('md5', $data["token"])],
            ['email', $data["email"]]
        ])->first();

        if(!$passwordReset){
            return response()->json([
                'message' => 'Invalid password reset code'
            ], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'Cannot find user with the email'
            ], 404);
        }

        $user->password = Hash::make($data["password"]);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccessNotification($passwordReset));
        return response()->json([
            'message' => 'Password updated'
        ], 201);
    }
}
