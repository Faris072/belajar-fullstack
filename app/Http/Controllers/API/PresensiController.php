<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Presensi;
use App\Models\Matkul;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function open(Request $request){
        $attributes = [
            'matkul_id' => 'Id matkul',
            'presensi_date' => 'Tanggal presensi',
            'start_presensi' => 'Waktu mulai presensi',
            'end_presensi' => 'Waktu selesai presensi',
            'description' => 'Deskripsi',
            'status' => 'Status'
        ];
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'numeric' => ':attribute harus berupa angka',
            'status.numeric' => 'Status berupa boolean bertipe numeric (1/0)'
        ];
        $validatedData = Validator::make($request->all(),[
            'matkul_id' => 'required|numeric',
            'description' => '',
        ],$messages,$attributes);

        if($validatedData->fails()){
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => $validatedData->getMessageBag(),
                    'code' => 400
                ]
            ],400);
        }

        if($request->status == 1 || $request->status == 0){}
        else{
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => 'Status berupa boolean bertipe numeric (1/0)',
                    'code' => 400
                ]
            ],400);
        }

        $date = now();
        $start = date('H:i:s', strtotime('now'));
        $end = date('H:i:s', strtotime('+1 hours'));

        $data = [
            'matkul_id' => $request->matkul_id,
            'presensi_date' => $date,
            'start_presensi' => $start,
            'end_presensi' => $end,
            'description' => $request->description,
            'status' => 1
        ];

        $cek_matkul = Matkul::find($request->matkul_id);

        if(!$cek_matkul){
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => 'Matkul tidak ditemukan',
                    'code' => 400
                ]
            ],400);
        }

        if(Auth()->user()->role_id == 1 || Auth()->user()->role_id == 2){
            // @dd($date);
            $cek_presensi = DB::select('SELECT * FROM presensi WHERE id = (SELECT MAX(id) FROM presensi WHERE matkul_id = '.$request->matkul_id.')');
            if(count($cek_presensi) > 0){
                if($cek_presensi[0]->status == 0){
                    $create = Presensi::create($data);
                    $create->presensi_date = Carbon::make($create->presensi_date)->translatedFormat('l, j F Y H:i:s');//carbon untuk management date
                    if($create){
                        return response()->json([
                            'data' => $create,//hanya bisa untuk create
                            'status' => [
                                'message' => 'Successfully created',
                                'code' => 200
                            ]
                        ],200);
                    }
                    else{
                        return response()->json([
                            'data' => [],
                            'status' => [
                                'message' => 'Server error',
                                'code' => 500,
                            ]
                        ],500);
                    }
                }
                else{
                    return response()->json([
                        'data' => [],
                        'status' => [
                            'message' => 'Presensi terakhir masih terbuka',
                            'code' => 403
                        ]
                    ],403);
                }
            }
            else{
                $create = Presensi::create($data);
                if($create){
                    return response()->json([
                        'data' => $create,//hanya bisa untuk create
                        'status' => [
                            'message' => 'Successfully created',
                            'code' => 200
                        ]
                    ],200);
                }
                else{
                    return response()->json([
                        'data' => [],
                        'status' => [
                            'message' => 'Server error',
                            'code' => 500,
                        ]
                    ],500);
                }
            }
        }
        else{
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => 'Access Denied',
                    'code' => 403
                ]
            ],403);
        }
    }

    public function close(Request $request){
        $matkul_id = $request->matkul_id;
        $presensi = DB::select('SELECT * FROM presensi WHERE id = (SELECT MAX(id) FROM presensi WHERE matkul_id ='.$matkul_id.')');
        if($presensi[0]->status == 1){
            $close = Presensi::find($presensi[0]->id)->update([
                'end_presensi' => now(),
                'status' => 0
            ]);
        }
        else{
            return response()->json([
                'data' => [],
                'status' => [
                    'message' => 'Presensi sudah tertutup',
                    'code' => 403
                ]
            ],403);
        }

        if($close){
            return response()->json([
                'data' => Presensi::find($presensi[0]->id),
                'status' => [
                    'message' => 'Successfully closed',
                    'code' => 200
                ]
            ],200);
        }
    }

    public function delete($id){
        try{
            $deleted_presensi = Presensi::find($id);
            if($deleted_presensi){
                if($deleted_presensi->status == 1){
                    return response()->json([
                        'data' => [],
                        'status' => [
                            'message' => 'Presensi masih terbuka',
                            'code' => 403
                        ]
                    ],403);
                }
            }

            $delete = Presensi::destroy($id);

            if($delete){
                return response()->json([
                    'data' => [
                        'deleted_data' => $deleted_presensi
                    ],
                    'status' => [
                        'message' => 'Successfully delete',
                        'code' => 200
                    ]
                ],200);
            }
            else{
                return response()->json([
                    'data' => [],
                    'status' => [
                        'message' => 'Data not found for delete',
                        'code' => 404
                    ]
                ],404);
            }
        }
        catch(\Exception $e) {
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
