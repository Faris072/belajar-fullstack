<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['username' => 'admin', 'password' => bcrypt(123456), 'role_id' => 1],
            ['username' => 'dosen', 'password' => bcrypt(12345678), 'role_id' => 2],
            ['username' => 'mahasiswa', 'password' => bcrypt(12345678), 'role_id' => 3],
        ];

        foreach($data as $d){
            User::create($d);
        }
    }
}
