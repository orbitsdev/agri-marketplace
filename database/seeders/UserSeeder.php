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
            'first_name'=> 'Super ',
            'middle_name'=> 'S',
            'last_name'=> 'Admin',
            'email'=> 'superadmin@gmail.com',
            'password'=> Hash::make('password'),
            'role'=> User::ADMIN,
         ]);
        User::create([
            'first_name'=> 'Admin',
            'middle_name'=> 'K',
            'last_name'=> 'Marketplace',
            'email'=> 'admin@agrimarketplace.com',
            'password'=> Hash::make('password'),
            'role'=> User::ADMIN,
         ]);
        User::create([
            'first_name'=> 'Admin',
            'middle_name'=> 'K',
            'last_name'=> 'Marketplace',
            'email'=> 'admin@gmail.com',
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
           'first_name'=> 'Buyer',
           'middle_name'=> 'K',
           'last_name'=> 'Marketplace',
            'email'=> 'buyer@gmail.com',
            'password'=> Hash::make('password'),
            'role'=> User::BUYER,
         ]);
        $farmer = User::create([
            'first_name'=> 'Farmer',
            'middle_name'=> 'K',
            'last_name'=> 'Marketplace',
            'email'=> 'farmer@gmail.com',
            'password'=> Hash::make('password'),
            'role'=> User::FARMER,
         ]);



            $farmer->farmer()->create([
                'farm_name'=> 'K Farm',
                'location'=> 'Kathmandu',
                'farm_size'=> '10',
                'description'=> 'This is a test farm',
            ]);
        $farmer2 = User::create([
            'first_name'=> 'Farmer2',
            'middle_name'=> 'K2',
            'last_name'=> 'Marketplace',
            'email'=> 'farmer2@gmail.com',
            'password'=> Hash::make('password'),
            'role'=> User::FARMER,
         ]);



            $farmer2->farmer()->create([
                'farm_name'=> 'K2 Farm',
                'location'=> 'Kathmandu',
                'farm_size'=> '10 Hectars',
                'description'=> 'Farmer 2',
            ]);




    }
}
