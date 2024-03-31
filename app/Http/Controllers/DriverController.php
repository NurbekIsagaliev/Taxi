<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    // Метод для создания нового водителя
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:drivers',
            'name' => 'required|string',
        ]);

        $driver = Driver::create([
            'phone' => $request->phone,
            'name' => $request->name,
        ]);

        return response()->json($driver, 201);
    }

    // Метод для отображения информации о водителе
    public function show($id)
    {
        $driver = Driver::findOrFail($id);
        return response()->json($driver);
    }

    // Метод для обновления информации о водителе
    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'phone' => 'required|unique:drivers,phone,' . $id,
            'name' => 'required|string',
        ]);

        $driver->phone = $request->phone;
        $driver->name = $request->name;
        $driver->save();

        return response()->json($driver);
    }

    // Метод для удаления водителя
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();
        return response()->json(null, 204);
    }
}
