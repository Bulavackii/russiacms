<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 🗂️ Category
 *
 * Модель категории, используемой для группировки файлов, записей и других сущностей.
 *
 * Связана с:
 * 🔸 `files()` — один ко многим: категория → множество файлов
 */
class Category extends Model
{
    /**
     * 🔓 Разрешённые к массовому заполнению поля (fillable)
     *
     * - title — название категории
     * - type — тип категории (например: 'file', 'news', 'product')
     * - icon — иконка категории (например: FontAwesome-класс)
     */
    protected $fillable = ['title', 'type', 'icon'];

    /**
     * 🔗 files()
     *
     * Связь "один ко многим" с моделью File.
     *
     * Категория может содержать множество файлов.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class, 'category_id');
    }
}
