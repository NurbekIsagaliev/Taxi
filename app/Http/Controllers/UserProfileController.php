<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;

class UserProfileController extends Controller
{
    // Метод для отображения списка пользователей
    public function index()
    {
        $users = UserProfile::all();
        return response()->json($users);
    }

    // Метод для создания нового пользователя
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:user_profiles',
        ]);

        $user = UserProfile::create([
            'phone' => $request->phone,
            'otp' => $request->otp,
        ]);

        return response()->json($user, 201);
    }

    // Метод для отображения информации о пользователе
    public function show($id)
    {
        $user = UserProfile::findOrFail($id);
        return response()->json($user);
    }

    // Метод для обновления информации о пользователе
    public function update(Request $request, $id)
    {
        $user = UserProfile::findOrFail($id);

        $request->validate([
            'phone' => 'required|unique:user_profiles,phone,' . $id,
        ]);

        $user->phone = $request->phone;
        $user->otp = $request->otp;
        $user->save();

        return response()->json($user);
    }

    // Метод для удаления пользователя
    public function destroy($id)
    {
        $user = UserProfile::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
