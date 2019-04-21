<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => '1',
            'status' => '1',
            'password' => bcrypt('admin123'),
            'first_name' => 'Nikhil',
            'last_name' => 'Jain',
            'name' => 'Nikhil Jain',
            'email' => 'admin@default.com'
        ]);
    }
}
