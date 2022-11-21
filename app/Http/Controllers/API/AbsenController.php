<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\Presensi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    public function create(Request $request, $id){
        try{
            if(Auth()->user()->role_id == 1 || Auth()->user()->role_id == 2){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Access denied',
                        'code' => 403
                    ]
                ],403);
            }

            $start_absen = now();

            $request->add([
                'user_id' => Auth()->user()->id,
                'presensi_id' => $id
            ]);

            $message = [
                'user_id.required' => 'User id wajib di isi',
                'presensi_id.required' => 'Presensi id wajib di isi',
            ];

            $validatedData = Validator::make($request->all(),[
                'user_id' => 'required',
                'presensi_id' => 'required',
            ],$message);

            if($validatedData->fails()){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => $validatedData->getMessageBag(),
                        'code' => 401
                    ]
                ],401);
            }
            @dd($validatedData);
            //pake DB:: soale absen e rusak

            $cek_absen = DB::table('absen')->where('user_id',Auth()->user()->id)->where('presensi_id',$id)->first();
            if($cek_absen){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Anda sudah absen',
                        'code' => 403
                    ]
                ],403);
            }

            $absen = Absen::create([
                'user_id' => $request->user_id,
                'presensi_id' => $request->presensi_id,
                'start_absen' => $start_absen,
                'is_wfo' => $request->is_wfo,
                'description' => $request->description
            ]);

            // $absen = DB::table('absen')->insert([
            //     'user_id' => $request->user_id,
            //     'presensi_id' => $request->presensi_id,
            //     'start_absen' => $start_absen,
            //     'is_wfo' => $request->is_wfo,
            //     'description' => $request->description
            // ]);

            if($absen){
                return response()->json([
                    'data' => $absen,
                    'status' => [
                        'message' => 'Data absen disimpan',
                        'code' => 200
                    ]
                ],200);
            }
            else{
                return response()->json([
                    'data' => $absen,
                    'status' => [
                        'message' => 'Data gagal disimpan',
                        'code' => 500
                    ]
                ],500);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'data' => [],
                'status' => [
                    'message' =>$e->getMessage(),
                    'code' => 500
                ]
            ],500);
        }
    }
}
