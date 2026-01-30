<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = [
            ['name' => 'Siswa 1', 'email' => 'siswa1@school.com'],
            ['name' => 'Siswa 2', 'email' => 'siswa2@school.com'],
            ['name' => 'Siswa 3', 'email' => 'siswa3@school.com'],
            ['name' => 'Siswa 4', 'email' => 'siswa4@school.com'],
            ['name' => 'Siswa 5', 'email' => 'siswa5@school.com'],
        ];

        foreach ($siswa as $s) {
            \App\Models\User::create([
                'name' => $s['name'],
                'email' => $s['email'],
                'password' => bcrypt('password'),
                'role' => 'siswa',
            ]);
        }
    }
}
