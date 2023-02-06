<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use App\Models\User;

use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            ['name' => 'User', 'uuid' => (string) Str::uuid(), 'email' => 'user@test.com', 'password' => bcrypt('password'), 'role_id' => 1, 'status' => '1'],
            ['name' => 'Editor', 'uuid' => (string) Str::uuid(), 'email' => 'editor@test.com', 'password' => bcrypt('password'), 'role_id' => 2, 'status' => '1'],
            ['name' => 'Author', 'uuid' => (string) Str::uuid(), 'email' => 'author@test.com', 'password' => bcrypt('password'), 'role_id' => 3, 'status' => '1'],
            ['name' => 'Admin', 'uuid' => (string) Str::uuid(), 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'role_id' => 4, 'status' => '1'],
        ]);

        /* $role_user = [
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 2, 'role_id' => 2],
            ['user_id' => 3, 'role_id' => 3],
            ['user_id' => 4, 'role_id' => 4],
        ];
        \DB::table('role_user')->insert($role_user); */
    }
}
