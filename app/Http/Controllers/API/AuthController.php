<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(Request $request){
        try{
            $errorMessage = [
                'username.required' => 'Username wajib di isi!',
                'username.min:3' => 'Username minimal 3 karakter!',
                'password.min:8' => 'Password minimal 8 karakter!',
                'password.required' => 'Password wajib di isi!',
            ];

            $validatedData = Validator::make($request->all(),[
                'username' => 'required|min:3',
                'password' => 'required|min:8'
            ],$errorMessage);

            if($validatedData->fails()){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => $validatedData->messages(),
                        'code' => 400,
                    ]
                ]);
            }

            $register = User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password)
            ]);

            if($register){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'User berhasil dibuat',
                        'code' => 200
                    ]
                ],200);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => $e->getMessage(),
                    'code' => 500,
                ]
            ],500);
        }
    }

    public function login()
    {
        try{
            $credentials = request(['username', 'password']);

            if (! $token = auth()->attempt($credentials)) {
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Login gagal!',
                        'code' => 401
                    ],
                ], 401);
            }

            return $this->respondWithToken($token);
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => $e->getMessage(),
                    'code' => 500,
                ]
            ],500);
        }
    }

    public function me()
    {
        try{
            return response()->json(auth()->user());
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => $e->getMessage(),
                    'code' => 500
                ]
            ],500);
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
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
}
