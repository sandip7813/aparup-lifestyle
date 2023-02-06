<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use App\Models\Role;

use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['name'=>'User', 'uuid' => (string) Str::uuid(), 'slug'=>'user'],
            ['name'=>'Editor', 'uuid' => (string) Str::uuid(), 'slug'=>'editor'],
            ['name'=>'Author', 'uuid' => (string) Str::uuid(), 'slug'=>'author'],
            ['name'=>'Super Admin', 'uuid' => (string) Str::uuid(), 'slug'=>'super-admin'],
            ['name'=>'Admin', 'uuid' => (string) Str::uuid(), 'slug'=>'admin'],
        ]);
    }
}
