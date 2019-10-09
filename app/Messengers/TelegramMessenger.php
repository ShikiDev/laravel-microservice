<?php
/**
 * Created by PhpStorm.
 * User: Shiki
 * Date: 10/9/2019
 * Time: 1:06 PM
 */

namespace App\Messengers;


use App\Interfaces\MessengerInterface;

class TelegramMessenger implements MessengerInterface
{
    private $connect_url;
    private $auth_key;
    private $message;

    public function __construct($message)
    {
        $this->connect_url = !empty(env('API_TELEGRAM_URL')) ? env('API_TELEGRAM_URL') : '';
        $this->auth_key = !empty(env('API_TELEGRAM_TOKEN')) ? env('API_TELEGRAM_TOKEN') : '';
        $this->message = $message;
    }

    public function sendMessage()
    {
        // Curl запрос к api
        return $this->parseResponse(true);
    }

    /**
     * @param $response
     * @return mixed
     */
    public function parseResponse($response)
    {
        //тут типо парсим ответ
        //рисуем некий фидбек от для проверки данных
        $result['status'] = ($response)? 'ok' : 'error';
        return $result;
    }
}