<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Matkul;
use App\Models\Kelas;
use App\Models\Day;

class MatkulController extends Controller
{
    public function create(Request $request){
        try{
            $attributes = [
                'kelas_id' => 'Kelas',
                'user_id' => 'User',
                'day_id' => 'Hari',
                'name' => 'Nama matkul',
                'start_matkul' => 'Waktu mulai matkul',
                'end_matkul' => 'Waktu selesai matkul'
            ];
            $message = [
                'required' => ':attribute wajib di isi',
                'numeric' => ':attribute harus berupa angka'
            ];
            $validatedData = Validator::make($request->all(),[
                'kelas_id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'day_id' => 'required|numeric',
                'name' => 'required',
                'start_matkul' => 'required',
                'end_matkul' => 'required'
            ],$message,$attributes);

            if($validatedData->fails()){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => $validatedData->getMessageBag(),
                        'code' => 400
                    ]
                ],400);
            }

            if(Auth()->user()->role_id != 1){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Access denied',
                        'code' => 403
                    ]
                ],403);
            }

            $cek_kelas = Kelas::find($request->kelas_id);
            $cek_day = Day::find($request->kelas_id);

            if(!$cek_kelas){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Id kelas tidak ditemukan',
                        'code' => 404
                    ]
                ],404);
            }
            if(!$cek_day){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Id hari tidak ditemukan',
                        'code' => 404
                    ]
                ],404);
            }

            $query = Matkul::create([
                'kelas_id' => $request->kelas_id,
                'user_id' => $request->user_id,
                'day_id' => $request->day_id,
                'name' => $request->name,
                'start_matkul' => $request->start_matkul,
                'end_matkul' => $request->end_matkul
            ]);

            if($query){
                return response()->json([
                    'data' => $query,
                    'status' => [
                        'message' => 'Data berhasil disimpan',
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

    public function detail($id){
        try{
            $query = Matkul::with([
                'kelas',
                'dosen',
                'presensi',
                'presensi.absen',
                'presensi.absen.keterangan',
                'day'
            ])->find($id);

            if(!$query){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Data gagal ditampilkan',
                        'code' => 500
                    ]
                ],500);
            }

            return response()->json([
                'data' => $query,
                'status' => [
                    'message' => 'Data berhasil ditampilkan',
                    'code' => 200
                ]
            ],200);
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

    public function index(){
        try{
            $query = Matkul::with([
                'kelas',
                'dosen',
                'presensi',
                'presensi.absen',
                'presensi.absen.keterangan',
                'day'
            ])->get();

            if(!$query){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Data gagal ditampilkan',
                        'code' => 500
                    ]
                ],500);
            }

            return response()->json([
                'data' => $query,
                'status' => [
                    'message' => 'Data berhasil ditampilkan',
                    'code' => 200
                ]
            ],200);
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

    public function update(Request $request, $id){
        try{
            $attributes = [
                'kelas_id' => 'Kelas',
                'user_id' => 'User',
                'day_id' => 'Hari',
                'name' => 'Nama matkul',
                'start_matkul' => 'Waktu mulai matkul',
                'end_matkul' => 'Waktu selesai matkul'
            ];
            $message = [
                'required' => ':attribute wajib di isi',
                'numeric' => ':attribute harus berupa angka'
            ];
            $validatedData = Validator::make($request->all(),[
                'kelas_id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'day_id' => 'required|numeric',
                'name' => 'required',
                'start_matkul' => 'required',
                'end_matkul' => 'required'
            ],$message,$attributes);

            if($validatedData->fails()){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => $validatedData->getMessageBag(),
                        'code' => 400
                    ]
                ],400);
            }

            if(Auth()->user()->role_id != 1){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Access denied',
                        'code' => 403
                    ]
                ],403);
            }

            $cek_kelas = Kelas::find($request->kelas_id);
            $cek_day = Day::find($request->kelas_id);

            if(!$cek_kelas){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Id kelas tidak ditemukan',
                        'code' => 404
                    ]
                ],404);
            }
            if(!$cek_day){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Id hari tidak ditemukan',
                        'code' => 404
                    ]
                ],404);
            }

            $query = Matkul::find($id)->update([
                'kelas_id' => $request->kelas_id,
                'user_id' => $request->user_id,
                'day_id' => $request->day_id,
                'name' => $request->name,
                'start_matkul' => $request->start_matkul,
                'end_matkul' => $request->end_matkul
            ]);

            if($query){
                return response()->json([
                    'data' => $query,
                    'status' => [
                        'message' => 'Data berhasil diupdate',
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

    public function delete($id){
        $cek_matkul = Matkul::find($id);
        if(!$cek_matkul){
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => 'Data tidak ditemukan'
                ]
            ],404);
        }

        $query = Matkul::destroy($id);
        if(!$query){
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => 'Data gagal dihapus',
                    'code' => 500
                ]
            ],500);
        }

        return response()->json([
            'data' => $cek_matkul,
            'status' => [
                'message' => 'Data berhasil dihapus',
                'code' => 200
            ]
        ],200);
    }
}
