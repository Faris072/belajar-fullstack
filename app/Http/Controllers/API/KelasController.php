<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function create(Request $request){
        try{
            $attributes = [
                'name' => 'Nama kelas',
                'angkatan' => 'Angkatan'
            ];
            $message = [
                'required' => ':attribute wajib di isi',//harus :attribute
            ];
            $validatedData = Validator::make($request->all(),[
                'name' => 'required',
                'angkatan' => 'required',
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

            $kelas = Kelas::create([
                'name' => $request->name,
                'angkatan' => $request->angkatan,
            ]);

            if(!$kelas){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Data gagal disimpan',
                        'code' => 500
                    ]
                ],500);
            }

            return response()->json([
                'data' => $kelas,
                'status' => [
                    'message' => 'Data berhasil disimpan',
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
        };
    }

    public function detail($id){
        try{

            $query = Kelas::with([
                'matkul',
                'matkul.presensi',
                'matkul.presensi.absen',
                'matkul.day',
                'matkul.dosen',
            ])->find($id);//with harus selalu paling depan

            if(!$query){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'gagal mengambil data',
                        'code' => 400,
                    ]
                ],400);
            }

            return response()->json([
                'data' => $query,
                'status' => [
                    'message' => 'Berhasil',
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

            $query = Kelas::with([
                'matkul',
                'matkul.presensi',
                'matkul.presensi.absen',
                'matkul.day',
                'matkul.dosen',
            ])->get();//with harus selalu paling depan

            if(!$query){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'gagal mengambil data',
                        'code' => 400,
                    ]
                ],400);
            }

            return response()->json([
                'data' => $query,
                'status' => [
                    'message' => 'Berhasil',
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
                'name' => 'Nama Kelas',
                'angkatan' => 'Angkatan'
            ];
            $message = [
                'required' => ':attribute wajib di isi',
                'numeric' => ':attribute harus bertipe angka'
            ];
            $validatedData = Validator::make($request->all(),[
                'name' => 'required',
                'angkatan' => 'required|numeric'
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

            $query = Kelas::find($id)->update([
                'name' => $request->name,
                'angkatan' => $request->angkatan,
            ]);

            if(!$query){
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Data gagal disimpan',
                        'code' => 500
                    ]
                ],500);
            }

            $kelas = Kelas::with('matkul','matkul.presensi','matkul.presensi.absen')->find($id);
            return response()->json([
                'data' => $kelas,
                'status' => [
                    'message' => 'Data berhasil disimpan',
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

    public function delete($id){
        try{
            $get = Kelas::find($id);
            if(!$get){
                return response()->json([
                    'data' => $get,
                    'status' => [
                        'message' => 'Data gagal ditemukan',
                        'code' => 500
                    ]
                ],500);
            }

            $query = Kelas::destroy($id);
            if(!$query){
                return response()->json([
                    'data' => $get,
                    'status' => [
                        'message' => 'Data gagal dihapus',
                        'code' => 500
                    ]
                ],500);
            }

            return response()->json([
                'data' => $get,
                'status' => [
                    'message' => 'Data berhasil dihapus',
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
}
