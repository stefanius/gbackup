<?php

namespace App\Exceptions;

use Exception;

class InvalidUrlException extends Exception
{
    public function __construct() {
        parent::__construct("Invalid URL.");
    }
}
