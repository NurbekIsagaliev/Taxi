<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserProfile;
class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admins = explode(',', env('ADMINS'));

        foreach ($admins as $admin) {
            // Создаем запись администратора в таблице user_profiles
            UserProfile::create([
                'phone' => $admin,
                'otp' => null, // Можно установить значение OTP, если это необходимо
            ]);
        }
    }
}
