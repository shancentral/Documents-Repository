<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => config('superadmin.name'),
            'email' => config('superadmin.email'),
            'password' => Hash::make( config('superadmin.password') ),
        ]);
    }
}
