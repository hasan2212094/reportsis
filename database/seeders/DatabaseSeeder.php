<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([[
            'name' => 'lingga',
            'email' => 'lingga@gmail.com',
            'password' => Hash::make('12345678'),
            'is_admin' => true
        ],[
            'name' => 'hasan',
            'email' => 'hasan@gmail.com',
            'password' => Hash::make('12345678'),
            'is_admin' => true

        ],[
            'name' => 'idris',
            'email' => 'idris@gmail.com',
            'password' => Hash::make('12345678'),
            'is_admin' => false
        ]
           
        ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
