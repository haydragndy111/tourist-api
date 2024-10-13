<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tourist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TouristAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'f_name' => 'required|string',
            'l_name' => 'required|string',
            'email' => 'required|email|unique:tourists',
            'password' => 'required|min:6',
            'description' => 'required|max:255',
        ]);

        $request['password'] = Hash::make($request->password);

        $tourist = Tourist::create($request->all());

        $data['user'] = $tourist;
        $data['token'] = $tourist->createToken('auth-token')->accessToken;

        $response = [
            'status' => 'success',
            'message' => 'Tourist is created successfully.',
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

            $tourist = Tourist::where('email', $request->email)->first();

            // Check password
            if(!$tourist || !Hash::check($request->password, $tourist->password)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Invalid credentials'
                    ], 401);
            }

            $data['user'] = $tourist;
            $data['token'] = $tourist->createToken('auth-token')->accessToken;

            $response = [
                'status' => 'success',
                'message' => 'Tourist is logged in successfully.',
                'data' => $data,
            ];

            return response()->json($response, 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json($th->getMessage(), 200);
        }
    }


}
