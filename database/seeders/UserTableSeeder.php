<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::firstOrCreate([
            'email' => 'alice@mail.com',
        ], [
            'name' => 'Alice',
            'password' => \Hash::make('password'),
        ]);

        \App\Models\User::firstOrCreate([
            'email' => 'bob@mail.com',
        ], [
            'name' => 'Bob',
            'password' => \Hash::make('password'),
        ]);

        \App\Models\User::firstOrCreate([
            'email' => 'arbisyarifudin@gmail.com',
        ],[
            'name' => 'Arbi S',
            'password' => \Hash::make('password'),
        ]);
    }
}
