<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ApplicationFailedException extends Exception
{
    public function __construct($message = 'Failed to process application')
    {
        parent::__construct($message);
    }
}
