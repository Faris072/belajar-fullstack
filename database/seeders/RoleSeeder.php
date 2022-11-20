<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Admin'],
            ['name' => 'Dosen'],
            ['name' => 'Mahasiswa']
        ];

        foreach($data as $d){
            Role::create([
                'name' => $d['name'],
            ]);
        }
    }
}
