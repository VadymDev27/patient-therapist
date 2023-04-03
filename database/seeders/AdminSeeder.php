<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->admin()->create(
            [
                'email' => config('auth.admin.email'),
                'password' => Hash::make(config('auth.admin.password')),
                'admin_permissions' => array_keys(Admin::PERMISSIONS)
            ]
        );
    }
}
