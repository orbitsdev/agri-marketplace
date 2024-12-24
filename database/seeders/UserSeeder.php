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
            'first_name'=> 'Admin',
            'middle_name'=> 'K',
            'last_name'=> 'Marketplace',
            'email'=> 'admin@agrimarketplace.com',
            'password'=> Hash::make('password'),
            'role'=> User::ADMIN,
         ]);
        User::create([
           'first_name'=> 'Buyer',
           'middle_name'=> 'K',
           'last_name'=> 'Marketplace',
            'email'=> 'buyer@agrimarketplace.com',
            'password'=> Hash::make('password'),
            'role'=> User::BUYER,
         ]);
        User::create([
            'first_name'=> 'Farmer',
            'middle_name'=> 'K',
            'last_name'=> 'Marketplace',
            'email'=> 'farmer@agrimarketplace.com',
            'password'=> Hash::make('password'),
            'role'=> User::FARMER,
         ]);


    }
}
