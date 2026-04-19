<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SecurityAnswer;
class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'photo'=>'avatar-01.jpg',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'address' => '123 Admin Street',
            'phone' => '1234567890',
            'is_admin' => true,
            'is_active' => true,
            'password_reset'=>0
        ]);
        $user = User::where('email', 'admin@gmail.com')->first();
        SecurityAnswer::create([
            'user_id' => $user->id,
            'first_answer' => 'admin',
            'second_answer' => 'admin',
            'third_answer' => 'admin',
        ]);
    }
}
