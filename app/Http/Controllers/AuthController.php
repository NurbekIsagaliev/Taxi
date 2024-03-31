<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function sendCode(Request $request)
    {
        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\d{11}$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid phone number'], 400);
        }

        // Поиск пользователя по номеру телефона
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 403);
        }

        // Генерация случайного OTP
        $otp = mt_rand(1000, 9999);

        // Сохранение OTP в базе данных
        $user->otp = $otp;
        $user->save();

        // Отправка OTP в ответе
        return response()->json(['code' => $otp], 200);
    }

    public function checkCode(Request $request)
    {
        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^\d{11}$/',
            'otp' => 'required|string|regex:/^\d{4}$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid phone number or OTP'], 400);
        }

        // Поиск пользователя по номеру телефона и OTP
        $user = User::where('phone', $request->phone)
                    ->where('otp', $request->otp)
                    ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid OTP'], 403);
        }

        // Обновление OTP и аутентификация пользователя
        $user->otp = null;
        $user->save();

        // Аутентификация пользователя и генерация токена JWT
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->otp])) {
            $token = $user->createToken('authToken')->accessToken;
            return response()->json(['access_token' => $token], 200);
        } else {
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }

    public function me(Request $request)
    {
        // Получение аутентифицированного пользователя из токена JWT
        $user = Auth::user();

        // Возвращение информации о пользователе в ответе
        return response()->json($user, 200);
    }
}
