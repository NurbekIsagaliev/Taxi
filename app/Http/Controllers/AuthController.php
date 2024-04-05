<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use \Firebase\JWT\JWT;
use Exception;
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
        $userProfile = UserProfile::where('phone', $request->phone)->first();

        if (!$userProfile) {
            return response()->json(['error' => 'User not found'], 403);
        }

        // Генерация случайного OTP
        $otp = mt_rand(1000, 9999);

        // Сохранение OTP в базе данных
        $userProfile->otp = $otp;
        $userProfile->save();

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
        $userProfile = UserProfile::where('phone', $request->phone)
                    ->where('otp', $request->otp)
                    ->first();

        if (!$userProfile) {
            return response()->json(['error' => 'Invalid OTP'], 403);
        }

        // Обновление OTP и аутентификация пользователя
        $userProfile->otp = null;
        $userProfile->save();

        // Аутентификация пользователя и генерация токена JWT
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->otp])) {
            $token = $userProfile->createToken('authToken')->accessToken;
            return response()->json(['access_token' => $token], 200);
        } else {
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }

    public function me(Request $request)
    {
        // Получаем токен пользователя из заголовка Authorization
$userToken = $_SERVER['HTTP_AUTHORIZATION'];

// Создаем пустой объект stdClass с помощью json_decode
$emptyObject = json_decode('{}');

// Декодируем токен и получаем информацию о пользователе
try {
    $decoded = JWT::decode($userToken, $emptyObject, null);
    $userInfo = (array) $decoded;
} catch (Exception $e) {
    // Обработка ошибок при декодировании токена
    return response()->json(['error' => 'Invalid token'], 401);
}

// Предположим, что после декодирования вы получаете ID пользователя
$userID = $userInfo['user_id'];

// На основе ID пользователя выполните процесс аутентификации самостоятельно
// Например, если у вас есть модель UserProfile, вы можете использовать ее для получения пользователя по его ID
$userProfile = UserProfile::find($userID);

// Проверяем, что пользователь найден
if (!$userProfile) {
    return response()->json(['error' => 'User profile not found'], 404);
}

// Теперь $userProfile содержит информацию об аутентифицированном пользователе, извлеченную из JWT токена

        // Возвращение информации о пользователе в ответе
        return response()->json($userProfile, 200);
    }
}
