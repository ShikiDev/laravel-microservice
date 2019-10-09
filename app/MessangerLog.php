<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessangerLog extends Model
{
    const CONTACT_LENGHT = 11;
    const COUNT_LIMIT = 10;

    //

    public static function validateContact($contact)
    {
        return (strlen($contact) == self::CONTACT_LENGHT) ? true : false;
    }

    public function isRepeatSend()
    {
        return ($this->count < self::COUNT_LIMIT) ? true : false;
    }
}
