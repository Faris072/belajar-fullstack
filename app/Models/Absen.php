<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;

    protected $table = 'absen';
    protected $guarded = ['id'];

    public function mahasiswa(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function presensi(){
        return $this->belongsTo(Presensi::class);
    }

    public function keterangan(){
        return $this->belongsTo(Keterangan::class, 'keterangan_id', 'id');
    }
}
