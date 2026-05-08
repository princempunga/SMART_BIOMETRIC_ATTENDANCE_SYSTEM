<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'System Admin',
            'email' => 'admin@university.edu',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'John Lecturer',
            'email' => 'lecturer@university.edu',
            'password' => \Illuminate\Support\Facades\Hash::make('lecturer123'),
            'role' => 'lecturer',
        ]);
    }
}
