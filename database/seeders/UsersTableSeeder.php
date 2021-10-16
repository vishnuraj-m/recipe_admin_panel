<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'firstName' => 'Food',
            'lastName' => 'Recipes',
            'email' => 'admin@example.com',
            'image' => 'admin.jpg',
            'usertype' => 1,
            'status' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('settings')->insert([
            'id' => '1',
            'fcm_key' => '',
            'privacy_policy' => '',
            'terms_and_conditions' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('recipe_statuses')->insert(
            [
                [
                    'id' => '1',
                    'name' => 'Pending',
                    'color' => 'Chartreuse',
                ],
                [
                    'id' => '2',
                    'name' => 'Approved',
                    'color' => 'Limegreen',
                ],
                [
                    'id' => '3',
                    'name' => 'Disapproved',
                    'color' => 'Red',
                ]
            ]
        );
    }
}
