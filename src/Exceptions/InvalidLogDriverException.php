<?php

namespace TF\Exceptions;

use Exception;
use Throwable;

class InvalidLogDriverException extends Exception
{
    /**
     * Constructor
     *
     * @param string $message
     * @param integer $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = 'Invalid Log Driver',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
