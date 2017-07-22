<?php

namespace ArrayBox\Exceptions;

/**
 * Class InvalidKeyException
 *
 * @package ArrayBox\Exceptions
 */
class InvalidKeyException extends \InvalidArgumentException
{

    /**
     * InvalidKeyException constructor.
     *
     * @param int $code
     * @param null|mixed $previous
     */
    public function __construct($code = 0, \Throwable $previous = null)
    {
        parent::__construct('You can only set string or int as key.', $code, $previous);
    }
}