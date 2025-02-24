<?php

namespace App\Http\Controllers;

use App\Models\PrintSetting;
use Illuminate\Http\Request;

class PrintSettingController extends Controller
{
    public function show()
    {
        $printSetting = PrintSetting::first() ?? new PrintSettings(); // Получаем первую запись
        return view('admin.print-settings', compact('printSetting'));
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'organization' => 'nullable|string|max:255',
            'phone1' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:50',
            'thanks' => 'nullable|string',
        ]);

        PrintSetting::updateOrCreate([], $data); // Сохраняем или обновляем запись

        return redirect()->route('admin.print-settings.show')->with('success', 'Настройки успешно сохранены');
    }
} 