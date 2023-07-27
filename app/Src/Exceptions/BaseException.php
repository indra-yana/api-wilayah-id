<?php

namespace App\Src\Exceptions;

use Illuminate\Http\Response;
use Exception;

class BaseException extends Exception
{

    protected $errorBag;

    public function __construct($message = 'An error occurred.', $errorBag = [], $code = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message, $code);

        $this->errorBag = $errorBag;
    }

    /**
     * Get all of the validation error messages.
     *
     * @return array
     */
    public function errors()
    {
        return $this->errorBag;
    }
}
