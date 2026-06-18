<?php

namespace App\Models;

use CodeIgniter\Model;

class JSONResponseBuilder
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
}
