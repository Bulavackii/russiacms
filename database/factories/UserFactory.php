<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * 🧪 Фабрика для генерации пользователей (User)
 *
 * Позволяет удобно создавать пользователей с фейковыми данными для сидеров и тестов.
 * Использует библиотеку Faker (через `fake()`).
 *
 * @extends Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * 🔐 Кешированный пароль, чтобы не вызывать Hash::make() при каждом вызове
     *
     * Используется общий для всех пользователей "password", хешируется один раз.
     */
    protected static ?string $password;

    /**
     * ⚙️ Определение стандартного состояния модели User
     *
     * Этот метод возвращает массив полей, которые будут заполнены при создании
     * пользователя через фабрику. Используется по умолчанию.
     *
     * @return array<string, mixed> — Набор атрибутов для модели
     */
    public function definition(): array
    {
        return [
            // 👤 Имя пользователя — случайное
            'name' => fake()->name(),

            // 📧 Email — уникальный и безопасный
            'email' => fake()->unique()->safeEmail(),

            // ✅ Email подтверждён (дата/время верификации)
            'email_verified_at' => now(),

            // 🔐 Пароль — хеш от слова "password", одинаковый для всех
            // Кэшируется в static::$password, чтобы не вызывать Hash::make каждый раз
            'password' => static::$password ??= Hash::make('password'),

            // 🧠 Токен для функции "Запомнить меня"
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * 🚫 Состояние: email НЕ подтверждён
     *
     * Позволяет явно указать, что у пользователя не подтверждён email.
     * Используется в тестах, когда нужно проверить доступность функционала
     * для неподтверждённых пользователей.
     *
     * Пример:
     * User::factory()->unverified()->create();
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
