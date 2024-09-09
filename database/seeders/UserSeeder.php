<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a user with the role of admin
        User::create([
            'username' => 'admin',
            'password' => bcrypt('adminpassword'),
            'age' => 30,
            'province' => 'Jakarta',
            'city' => 'Jakarta',
            'kelurahan' => 'Kelurahan',
            'kecamatan' => 'Kecamatan',
            'gender' => 'laki-laki',
            'role' => 'admin'
        ]);
    }
}
