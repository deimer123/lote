<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => '1',
            'name' => 'admin',
            'cedula' => '0000',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
            'address' => 'xxxxx',
            'telefono' => '11111'
            
        ]);
    }
}
