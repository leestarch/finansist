<?php

namespace App\Exceptions;

//use App\Services\TelegramBotService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Handler extends ExceptionHandler
{

    public function report(Throwable $e)
    {
        if (!in_array(config('app.env'), ['local', 'testing'])) {
//                logger($exception->getMessage());
            if (
//                false === mb_stripos($e->getMessage(), 'No query results for model') &&
//                false === mb_stripos($e->getMessage(), 'Unauthenticated') &&
//                false === mb_stripos($e->getMessage(), 'Ссылка устарела') &&
//                false === mb_stripos($e->getMessage(), 'The given data was invalid') &&
                false === mb_stripos($e->getMessage(), 'CSRF token mismatch') &&
//                false === mb_stripos($e->getMessage(), 'cURL error 28: Connection timed') &&
                false === mb_stripos($e->getMessage(), 'could not be found') &&
//                false === mb_stripos($e->getMessage(), 'Товар не выбран') &&
//                false === mb_strpos($e->getMessage(), 'method is not supported for this route') &&
//                false === mb_strpos($e->getMessage(), 'method is not supported for route') &&
//                false === mb_strpos($e->getMessage(), 'getUserProfilePhotos') &&
//                false === mb_strpos($e->getMessage(), 'failed to open stream: Network is unreachable') &&
//                false === mb_strpos($e->getMessage(), 'was only partially uploaded') &&
//                false === mb_strpos($e->getMessage(), 'auth.failed') &&
//                false === mb_strpos($e->getMessage(), 'GetAvatars') &&
                $e->getMessage() !== ''
            ) {
                $user = '';
                if (Auth::check()) {
                    $user = 'Пользователь: (' . Auth::user()->id . ')' . Auth::user()->name . " \n";
                }

//                with(new TelegramBotService())->sendChatTextMessage(TelegramBotService::ERROR_ID, "Выброшено исключение: " . $e->getMessage() . "\n-----------\n" . "\n" .
//                    'Файл/Строка: ' . $e->getFile() . ": " . $e->getLine() . "\n" . "\n" .
//                    'Код ошибки: ' . $e->getCode() . "\n" . "\n" .
//                    'Путь: ' . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '') . "\n" . "\n" .
//                    'IP: ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '') . "\n" . "\n" .
//                    'Агент: ' . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') . "\n" . "\n" .
//                    $user);

            }
        }
        return parent::report($e);
    }
}
