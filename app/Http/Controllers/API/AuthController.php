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
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function register(Request $request){
        try{
            $errorMessage = [
                'username.required' => 'Username wajib di isi!',
                'username.min:3' => 'Username minimal 3 karakter!',
                'password.min:8' => 'Password minimal 8 karakter!',
                'password.required' => 'Password wajib di isi!',
                'role_id' => 'Role wajib di isi!'
            ];

            $validatedData = Validator::make($request->all(),[
                'username' => 'required|min:3',
                'password' => 'required|min:8',
                'role_id' => 'required'
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
            if(Auth()->user()->role_id == 1){
                if($request->role_id != 1){
                    $register = User::create([
                        'role_id' => $request->role_id,
                        'username' => $request->username,
                        'password' => bcrypt($request->password)
                    ]);
                }
                else{
                    return response()->json([
                        'data' => [],
                        'status' => [
                            'message' => 'Tambah user admin ditolak!',
                            'code' => 400
                        ]
                    ]);
                }
            }
            else{
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Anda bukan admin',
                        'code' => 400
                    ]
                ]);
            }

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

    public function data(){
        try{
            $user = User::with('presensi','role')->get();
            if($user || count($user) > 0){
                // @dd($user->absen());
                return response()->json([
                    'data' => $user,
                    'status' => [
                        'message' => 'Get data user successfully',
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
                    'code' => 500
                ]
            ],500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 600
        ]);
    }
}
