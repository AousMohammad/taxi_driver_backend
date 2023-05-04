<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'ali',
            'email' => 'ali@gmail.com',
            'password' => bcrypt('12345'),
            'phone_number' => '0936943559',
            'type' => 1,
        ]);
        User::create([
            'name' => 'loay',
            'email' => 'loay@gmail.com',
            'password' => bcrypt('12345'),
            'phone_number' => '0936943558',
            'type' => 0,
        ]);
        User::create([
            'name' => 'reham',
            'email' => 'reham@gmail.com',
            'password' => bcrypt('12345'),
            'phone_number' => '0936943557',
            'type' => 0,
        ]);
    }
}
