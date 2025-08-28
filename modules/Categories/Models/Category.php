<?php

namespace Modules\Categories\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 🗂️ Модель категории
 *
 * Представляет запись в таблице `categories`.
 * Используется для привязки к записям, продуктам, FAQ и другим сущностям.
 */
class Category extends Model
{
    /**
     * ✅ Разрешённые для массового заполнения (Mass Assignment) поля
     *
     * Здесь перечисляем только те поля, которые можно безопасно заполнять
     * через `create()`, `update()` и подобные методы.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title'];
}
