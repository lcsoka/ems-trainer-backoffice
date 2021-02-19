<?php


use Illuminate\Support\ServiceProvider;

class ErrorMessages extends ServiceProvider
{
    public static function getMessageByCode($errorCode)
    {
        $messages = config('errormessages.messages');
        $result = 'Unknown error message';

        if (isset($messages[$errorCode])) {
            $result = $messages[$errorCode];
        }

        return $result;
    }
}
