<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //creating admin
        User::factory()->count(1)
            ->create([
                'role_id' => 1,
                'name' => 'admin',
                'email' => 'admin@admin.admin',
                'phone' => 123456789,
                'email_verified_at' => now(),
                'password' =>bcrypt('admin123'),
                'remember_token' => Str::random(10),
            ]);

        //creating customer
        User::factory()->count(1)
            ->create([
                'role_id' => 2,
                'name' => 'customer',
                'email' => 'customer@customer.customer',
                'phone' => 987654321,
                'email_verified_at' => now(),
                'password' =>bcrypt('customer123'),
                'remember_token' => Str::random(10),
            ]);
    }
}
