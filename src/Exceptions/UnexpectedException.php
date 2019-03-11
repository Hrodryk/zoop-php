<?php

namespace Zoop\Exceptions;

use RuntimeException;

/**
 * Class UnexpectedException.
 */
class UnexpectedException extends RuntimeException
{
    /**
     * UnexpectedException constructor.
     *
     * @param null $previous
     */
    public function __construct($previous = null)
    {
        parent::__construct('Um erro inesperado aconteceu, por favor contate o zoop', 500, $previous);
    }
}
