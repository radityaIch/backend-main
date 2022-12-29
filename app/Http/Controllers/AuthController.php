<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    public function index()
    {
        $user = User::latest()->get();

        return response()->json([
            'data' => $user
        ], 200);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 400);
        }

        return response()->json([
            'data' => $user
        ], 200);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        if ($user->email_verified_at == null) {
            return response()->json(['message' => 'Email not verified'], 401);
        }

        if ($user->verified == null) {
            return response()->json(['message' => 'Not verified by admin'], 401);
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function user()
    {
        return response()->json([
            'data' => auth()->user()
        ], 200);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'user logged out successfully']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'message' => 'Email already registered',
            ], 400);
        }

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'position' => 'required',
            'level' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ], 400);
        }

        $verified = null;

        if ($request->level == 2) {
            $verified = 1;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $request->position,
            'level' => $request->level,
            'verified' => $verified
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    public function update(Request $request)
    {
        $id = auth()->user()->id;

        $user = User::find($id);

        $rules = [
            'name' => 'required',
            'position' => 'required',
            'email' => 'required',
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
            $user->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $oldEmail = $user->email;

        if ($oldEmail != $request->email) {
            $user->email_verified_at = null;
            $user->email = $request->email;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->position = $request->position;
        $user->save();

        if ($oldEmail != $user->email) {
            $user->sendEmailVerificationNotification();

            return response()->json([
                'message' => ['User updated successfully', 'Email verification link sent!'],
                'data' => $user,
            ], 201);
        }

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 201);
    }

    public function updateById(Request $request, $id)
    {
        $user = User::find($id);

        $rules = [
            'name' => 'required',
            'image' => 'image|file|max:2048',
            'position' => 'required',
            'level' => 'required'
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
            $user->image = $request->getSchemeAndHttpHost() . '/storage/public/' .  $filename;
        }

        $user->name = $request->name;
        $user->position = $request->position;
        $user->level = $request->level;
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 201);
    }

    public function verified($id)
    {
        $user = User::find($id);

        if (!$user) {
            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 400);
            }
        }

        $user->verified = 1;
        $user->save();

        return response()->json([
            'message' => 'User verified successfully',
        ], 201);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 201);
    }
}
