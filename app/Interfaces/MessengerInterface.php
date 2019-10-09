<?php
/**
 * Created by PhpStorm.
 * User: Shiki
 * Date: 10/9/2019
 * Time: 9:50 AM
 */

namespace App\Interfaces;


interface MessengerInterface
{
    public function __construct($message);

    /**
     * Метод для отправки запроса к API.
     *
     * @return mixed
     */
    public function sendMessage();

    /**
     * Метод парсинга ответа от API.
     *
     * @return mixed
     */
    public function parseResponse($response);
}