<?php

namespace Modules\Payments\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 💳 Модель способа оплаты
 *
 * Хранит информацию о доступных методах оплаты:
 * - Название (например, "Картой онлайн")
 * - Описание (подробности, как работает)
 * - Тип (онлайн / оффлайн)
 * - Активность (включён или выключен)
 * - Настройки (в виде массива)
 */
class PaymentMethod extends Model
{
    // 🧾 Название таблицы в БД
    protected $table = 'payment_methods';

    // 📝 Разрешённые к массовому заполнению поля
    protected $fillable = [
        'title',        // 🏷️ Название метода оплаты
        'description',  // 📄 Краткое описание
        'type',         // 🔄 Тип: online / offline
        'active',       // ✅ Включён ли метод
        'settings',     // ⚙️ Настройки в виде массива (JSON)
    ];

    // 🧠 Преобразования типов для работы как с массивами/булевыми
    protected $casts = [
        'settings' => 'array',   // 🔧 Преобразовать в массив автоматически
        'active' => 'boolean',   // ✅ Активность как true/false
    ];
}
