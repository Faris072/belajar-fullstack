<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'D3 TEKNIK INFORMATIKA A', 'angkatan' => 2022],
            ['name' => 'D3 TEKNIK INFORMATIKA B', 'angkatan' => 2022],
            ['name' => 'D3 PJJ TEKNIK INFORMATIKA A', 'angkatan' => 2022],
            ['name' => 'D3 PJJ TEKNIK INFORMATIKA B', 'angkatan' => 2022],
            ['name' => 'D3 PJJ TEKNIK INFORMATIKA B', 'angkatan' => 2022],
            ['name' => 'D4 LJ TEKNIK INFORMATIKA A', 'angkatan' => 2022],
            ['name' => 'D4 LJ TEKNIK INFORMATIKA B', 'angkatan' => 2022],
            ['name' => 'D4 LJ PJJ TEKNIK INFORMATIKA A', 'angkatan' => 2022],
            ['name' => 'D4 LJ PJJ TEKNIK INFORMATIKA B', 'angkatan' => 2022],
        ];

        foreach ($data as $d){
            Kelas::create($d);
        }
    }
}
