<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;

    protected $table = 'matkul';
    protected $guarded = ['id'];

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function dosen(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function presensi(){
        return $this->hasMany(Presensi::class, 'matkul_id', 'id');
    }

    public function day(){
        return $this->belongsTo(Day::class, 'day_id', 'id');
    }
}
