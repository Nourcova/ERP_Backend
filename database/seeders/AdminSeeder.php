<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'name' => 'Ibrahim',
                'email'=> 'ibrahim@codi.tech',
                'image'=> 'image',
                'password' => '12345678'
            ],
            [
                'name' => 'Khalid',
                'email'=> 'khalid@codi.tech',
                'image'=> 'image',
                'password' => '12345678'
            ],
            [
                'name' => 'Souad',
                'email'=> 'souad@codi.tech',
                'image'=> 'image',
                'password' => '12345678'
            ],
            [
                'name' => 'Obaida',
                'email'=> 'obaida@codi.tech',
                'image'=> 'image',
                'password' => '12345678'
            ]
        ]);
    }
}
