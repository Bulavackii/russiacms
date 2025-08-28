<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * 👥 Сидер для таблицы пользователей
 *
 * Добавляет базовых пользователей в БД:
 * — двух администраторов
 * — одного обычного пользователя
 */
class UsersTableSeeder extends Seeder
{
    /**
     * 🚀 Метод запуска сидера
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 👑 Администратор 1
        |--------------------------------------------------------------------------
        |
        | Если пользователь с таким email уже существует — будет обновлён.
        | Устанавливаются имя, пароль, подтверждение email, статус администратора.
        |
        */
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin', // 👤 Имя администратора
                'email_verified_at' => now(), // ✅ Email подтверждён
                'password' => Hash::make('123456'), // 🔐 Хешируем пароль
                'is_admin' => true, // 🛡️ Признак администратора
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 👑 Администратор 2
        |--------------------------------------------------------------------------
        |
        | Второй пользователь с правами администратора — можно использовать для тестов.
        |
        */
        User::updateOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'name' => 'Admin2',
                'email_verified_at' => now(),
                'password' => Hash::make('123456'),
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 👤 Обычный пользователь
        |--------------------------------------------------------------------------
        |
        | Пользователь без административных прав — `is_admin: false`.
        |
        */
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'email_verified_at' => now(),
                'password' => Hash::make('123456'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
