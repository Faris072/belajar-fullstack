<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $table = 'day';
    protected $guarded = ['id'];

    public function matkul(){
        return $this->hasMany(Matkul::class, 'day_id', 'id');
    }
}
