<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Keterangan;

class KeteranganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Masuk'],
            ['name' => 'Sakit'],
            ['name' => 'Izin'],
            ['name' => 'Alasan'],
        ];

        foreach($data as $d){
            Keterangan::create($d);
        }
    }
}
