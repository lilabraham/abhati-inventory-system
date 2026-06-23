<?php

namespace App\Models;


class JSONResponseBuilder implements \JsonSerializable
{
    public $code = null;
    public $success = true;
    public $message = '';
    public $data = null;


    public function buildResponse(?int $code, ?bool $success = true, $message = '', $data = null)
    {
        $this->code = $code;
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
    }
    public function jsonSerialize(): array
    {
        return [
            'code'    => $this->code,
            'success' => $this->success,
            'message' => $this->message,
            'data'    => $this->data,
        ];
    }
}
