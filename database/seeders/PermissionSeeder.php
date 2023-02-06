<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use App\Models\Permission;

use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            ['name'=>'Add Post', 'uuid' => (string) Str::uuid(), 'slug'=>'add-post'],
            ['name'=>'Delete Post', 'uuid' => (string) Str::uuid(), 'slug'=>'delete-post'],
        ]);
    }
}
