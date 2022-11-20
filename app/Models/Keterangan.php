<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keterangan extends Model
{
    use HasFactory;

    protected $table = 'keterangan';
    protected $guarded = ['id'];

    public function absen(){
        return $this->hasMany(Absen::class, 'keterangan_id', 'id');
    }
}
