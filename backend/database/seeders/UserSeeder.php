<?php

namespace Database\Seeders;

use App\Modules\Users\Enums\UserRole;
use App\Modules\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Админ — входит в систему, видит всё.
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'role' => UserRole::Admin,
                'password' => Hash::make('password'),
            ],
        );

        // Менеджеры — входят и видят свои диалоги.
        $managers = [
            'anna@example.com' => 'Анна Соколова',
            'igor@example.com' => 'Игорь Петров',
        ];
        foreach ($managers as $email => $name) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'role' => UserRole::Manager,
                    'password' => Hash::make('password'),
                ],
            );
        }

        // Клиенты — не входят в систему (без пароля).
        $clients = [
            'client.smirnov@example.com' => 'Дмитрий Смирнов',
            'client.orlova@example.com' => 'Елена Орлова',
            'client.volkov@example.com' => 'Павел Волков',
            'client.morozova@example.com' => 'Ольга Морозова',
        ];
        foreach ($clients as $email => $name) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'role' => UserRole::Client,
                ],
            );
        }
    }
}
