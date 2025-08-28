<?php

namespace Modules\Delivery\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    /**
     * 📦 Указываем, какие поля можно массово заполнять (mass assignment).
     * Это нужно для методов вроде create() и update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',        // 🏷️ Название метода доставки (например, "Курьером", "Почтой России")
        'description',  // 📝 Подробное описание (можно оставить пустым)
        'price',        // 💰 Стоимость доставки в рублях (например, 300.00)
        'active',       // ✅ Флаг активности (true — доступен, false — скрыт)
    ];
}
