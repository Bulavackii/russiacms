<?php

namespace Modules\Delivery\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Delivery\Models\DeliveryMethod;

class DeliveryMethodController extends Controller
{
    /**
     * 📦 Отображение списка всех методов доставки.
     * Показывает таблицу с названиями, ценами и статусом активности.
     */
    public function index()
    {
        // 🔄 Загружаем все записи из таблицы delivery_methods
        $methods = DeliveryMethod::all();

        // 📄 Передаём данные в шаблон admin.index
        return view('Delivery::admin.index', compact('methods'));
    }

    /**
     * ➕ Показ формы создания нового метода доставки.
     * Здесь пользователь вводит название, описание, цену и активность.
     */
    public function create()
    {
        return view('Delivery::admin.create'); // 🧾 Шаблон формы создания
    }

    /**
     * 💾 Обработка отправки формы создания.
     * Сохраняет новый метод доставки в базу данных.
     */
    public function store(Request $request)
    {
        // ✅ Проверка введённых пользователем данных
        $request->validate([
            'title'       => 'required|string|max:255', // 🏷️ Название обязательно и до 255 символов
            'description' => 'nullable|string',         // 📝 Описание не обязательно
            'price'       => 'required|numeric|min:0',  // 💰 Цена обязательна и ≥ 0
            'active'      => 'boolean',                 // ✅ Активен: true/false (чекбокс)
        ]);

        // 📥 Создание нового метода доставки
        DeliveryMethod::create([
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'active'      => $request->boolean('active'), // Преобразуем в булев тип
        ]);

        // 🔙 Возвращаем пользователя к списку с флеш-сообщением
        return redirect()->route('admin.delivery.index')
                         ->with('success', 'Метод доставки добавлен');
    }

    /**
     * ✏️ Показ формы редактирования существующего метода доставки.
     * Передаёт текущие значения в форму.
     */
    public function edit(DeliveryMethod $delivery)
    {
        // 📄 Передаём выбранный метод доставки в форму
        return view('Delivery::admin.edit', compact('delivery'));
    }

    /**
     * ♻️ Обновление существующего метода доставки.
     * Получает данные из формы редактирования и обновляет запись.
     */
    public function update(Request $request, DeliveryMethod $delivery)
    {
        // ✅ Проверяем, что все поля заполнены корректно
        $request->validate([
            'title'       => 'required|string|max:255', // 🏷️ Название обязательно
            'description' => 'nullable|string',         // 📝 Описание опционально
            'price'       => 'required|numeric|min:0',  // 💰 Цена ≥ 0
            'active'      => 'boolean',                 // ✅ Чекбокс "активен"
        ]);

        // 🔄 Обновляем поля в существующей записи
        $delivery->update([
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
            'active'      => $request->boolean('active'),
        ]);

        // 🔙 Перенаправление со статусом
        return redirect()->route('admin.delivery.index')
                         ->with('success', 'Метод доставки обновлён');
    }

    /**
     * 🗑️ Удаление метода доставки.
     * Удаляет запись из базы данных по ID.
     */
    public function destroy(DeliveryMethod $delivery)
    {
        // ❌ Удаляем запись
        $delivery->delete();

        // 🔙 Назад со всплывающим уведомлением
        return back()->with('success', 'Метод доставки удалён');
    }
}
