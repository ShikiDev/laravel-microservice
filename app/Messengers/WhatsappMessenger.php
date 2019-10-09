<?php
/**
 * Created by PhpStorm.
 * User: Shiki
 * Date: 10/9/2019
 * Time: 3:24 PM
 */

namespace App\Messengers;


use App\Interfaces\MessengerInterface;

class WhatsappMessenger implements MessengerInterface
{
    private $connect_url;
    private $auth_key;
    private $message;

    public function __construct($message)
    {
        $this->connect_url = !empty(env('API_WHATSAPP_URL')) ? env('API_WHATSAPP_URL') : '';
        $this->auth_key = !empty(env('API_WHATSAPP_TOKEN')) ? env('API_WHATSAPP_TOKEN') : '';
        $this->message = $message;
    }

    public function sendMessage()
    {
        return $this->parseResponse(true);
    }

    public function parseResponse($response)
    {
        $result['status'] = ($response)? 'ok' : 'error';
        return $result;
    }
}