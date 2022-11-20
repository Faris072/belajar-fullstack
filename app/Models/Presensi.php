<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Absen;

class Presensi extends Model
{
    use HasFactory;
    protected $table = 'presensi';
    protected $guarded = ['id'];

    public function matkul(){
        return $this->belongsTo(Matkul::class, 'matkul_id', 'id');
    }

    public function absen(){
        return $this->hasMany(Absen::class, 'user_id', 'id');
    }
}
