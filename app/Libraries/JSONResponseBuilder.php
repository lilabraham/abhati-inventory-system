<?php

namespace App\Libraries;

class JSONResponseBuilder
{
    public static function make(
        int $code,
        bool $success,
        string $message = '',
        mixed $data = null
    ): array {
        return [
            'code'    => $code,
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ];
    }
}