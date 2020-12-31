<?php

namespace Justmd5\Crypto\Rsa\Exceptions;

use Exception;

class InvalidPrivateKey extends Exception
{
    public static function make(): self
    {
        return new self('This does not seem to be a valid private key.');
    }
}
