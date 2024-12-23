<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=> 'Market Place Admin',
            'email'=> 'admin@agrimarketplace.com',
            'password'=> Hash::make('password'),
            'role'=> User::ADMIN,
         ]);
        User::create([
            'name'=> 'Buyer User',
            'email'=> 'buyer@agrimarketplace.com',
            'password'=> Hash::make('password'),
            'role'=> User::BUYER,
         ]);
        User::create([
            'name'=> 'Farmer User',
            'email'=> 'farmer@agrimarketplace.com',
            'password'=> Hash::make('password'),
            'role'=> User::FARMER,
         ]);


    }
}
