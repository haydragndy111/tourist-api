<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tourist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $request['password'] = Hash::make($request->password);

        $user = User::create($request->all());

        $data['user'] = $user;
        $data['token'] = $user->createToken($request->email)->accessToken;

        $response = [
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => $data,
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            $user = User::where('email', $request->email)->first();
            // dd($user);

            // Check password
            if(!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Invalid credentials'
                    ], 401);
            }

                $data['user'] = $user;
            $data['token'] = $user->createToken('token')->accessToken;

            $response = [
                'status' => 'success',
                'message' => 'User is logged in successfully.',
                'data' => $data,
            ];

            return response()->json($response, 200);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function logout()
    {
        try {
            $user = Auth::guard('admin-api')->user();
            // dd($user);
            $user = User::findOrFail(Auth::id());
            $user->tokens()->delete();
            if ($logout) {
                return $this->showMessage('success', 200);
            }
            return $this->showMessage('Something went wrong', 500);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
}
