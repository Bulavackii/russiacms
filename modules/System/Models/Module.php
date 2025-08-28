<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 🧩 Модель Module
 *
 * Представляет запись о модуле системы:
 * - название
 * - версия
 * - активность (вкл/выкл)
 */
class Module extends Model
{
    /**
     * 🛠️ Разрешённые к массовому заполнению поля
     */
    protected $fillable = [
        'name',     // 🏷️ Название модуля (уникальное имя папки)
        'version',  // 🔢 Версия модуля (например, "1.0.0")
        'active',   // ✅ Статус активности (1 — включен, 0 — выключен)
    ];

    /**
     * 🧪 Приведение типов
     */
    protected $casts = [
        'active' => 'boolean', // 🎚️ Приводим активность к булевому типу
    ];
}
