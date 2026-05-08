<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Role::insert([
    [
        'name' => 'Admin',
        'slug' => 'admin'
    ],
    [
        'name' => 'User',
        'slug' => 'user'
    ],
    [
        'name' => 'Finance',
        'slug' => 'finance'
    ],
    [
        'name' => 'Marketing',
        'slug' => 'marketing'
    ],
]);
    }
}
