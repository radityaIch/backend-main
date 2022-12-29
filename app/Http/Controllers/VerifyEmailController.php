<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class VerifyEmailController extends Controller
{

    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect()->to('https://admin.mjt-tlpartner.com');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // return redirect(env('FRONT_URL') . '/email/verify/success');
        return redirect()->to('https://admin.mjt-tlpartner.com/verified/' . $user->email . '/' . $hash);
    }

    public function resend(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 400);
        }

        $user->sendEmailVerificationNotification();
        return response()->json([
            'message' => 'Verification link sent!'
        ], 200);
    }
}
