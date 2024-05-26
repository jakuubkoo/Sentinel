<?php

namespace App\Manager;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ErrorManager
 *
 * ErrorManager provides error handling operations.
 *
 * @package App\Manager
 */
class ErrorManager
{
    /**
     * Handles errors based on the application's mode.
     *
     * @param string $msg The error message.
     * @param int $code The error code.
     *
     * @throws HttpException
     *
     * @return mixed
     */
    public function handleError(string $msg, int $code): mixed
    {
        // throw HttpException with JSON response
        throw new HttpException($code, $msg, null, [], $code);
    }
}
