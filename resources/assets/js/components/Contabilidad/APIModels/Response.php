<?php

namespace App\APIModels;

class Response
{
    public $StatusCode;
    public $Message;
    public $Data;
    public $IsSuccess;

    public function __construct($StatusCode,$Message,$Data,$IsSuccess)
    {
        $this->StatusCode=$StatusCode;
        $this->Message=$Message;
        $this->Data=$Data;
        $this->IsSuccess=$IsSuccess;
    }
}

