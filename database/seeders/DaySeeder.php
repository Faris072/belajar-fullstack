<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Day;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Senin'],
            ['name' => 'Selasa'],
            ['name' => 'Rabu'],
            ['name' => 'Kamis'],
            ['name' => "Jum'at"],
            ['name' => 'Sabut'],
            ['name' => 'Minggu'],
        ];

        foreach ($data as $d){
            Day::create($d);
        }
    }
}
