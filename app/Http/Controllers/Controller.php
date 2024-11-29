<?php

namespace App\Http\Controllers;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function vue(Request $request)
    {
        // 1. Проверяем наличие токена в query параметрах
        if ($request->has('token')) {
            $token = $request->query('token');

            // 2. Декодируем токен с использованием APP_KEY
            try {
                // Используем Key для декодирования JWT
                $decoded = JWT::decode($token, new Key(config('app.key'), 'HS256'));
                $userId = $decoded->id;

                // 3. Запрос на API другого проекта
                $userData = $this->fetchUserData($userId);

                // 4. Сохраняем данные пользователя и время запроса в cookies
                Cookie::queue('user_data', json_encode($userData), 60); // Сохранение на 60 минут
                Cookie::queue('last_request_time', now(), 60); // Время запроса

                // 5. Удаление токена из URL (очистка query параметров)
                return redirect()->route('home'); // Редирект на тот же маршрут без токена в URL

            } catch (ExpiredException $e) {
                return redirect()->route('home')->withErrors(['error' => 'Token has expired']);
            } catch (Exception $e) {
                return redirect()->away('https://lookin.team/login?' . http_build_query(['redirect' => 'https://lib.lookin.team/']));
            }
        }

        // 6. Если токена нет, проверяем данные в cookies
        if (Cookie::has('user_data')) {
            $userData = json_decode(Cookie::get('user_data'), true); // Получаем данные из cookies
            $lastRequestTime = Cookie::get('last_request_time');

            // Проверяем, прошло ли более 5 минут с последнего обновления данных
            if (Carbon::parse($lastRequestTime)->diffInMinutes(now()) > 5) {
                // Если прошло более 5 минут, обновляем данные
                $userId = $userData['id']; // Предполагаем, что ID пользователя есть в данных
                $userData = $this->fetchUserData($userId);

                // Обновляем данные и время запроса в cookies
                Cookie::queue('user_data', json_encode($userData), 60);
                Cookie::queue('last_request_time', now(), 60);
            }

            // Отображаем страницу с данными пользователя
            $params = [
                'user_id' => $userData['id'],
                'user' => $userData ?? ['display_name' => 'Не получен пользователь']
            ];
            $params = json_encode($params);
            return view('home', compact('params'));
        }
        // 7. Если данных в cookies нет, редирект на другой проект
        return redirect()->away('https://lookin.team/login?' . http_build_query(['redirect' => 'https://lib.lookin.team/']));
    }

    // Метод для запроса к API другого проекта
    private function fetchUserData($userId)
    {
        $client = new Client();
        $response = $client->get('https://api.lookin.team/api/knowledgeUsers/' . $userId);
        return json_decode($response->getBody(), true);
    }
}
