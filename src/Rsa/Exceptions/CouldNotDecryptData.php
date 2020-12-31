<?php

namespace Justmd5\Crypto\Rsa\Exceptions;

use Exception;

class CouldNotDecryptData extends Exception
{
    public static function make(): self
    {
        return new self("Could not decrypt the data.");
    }
}
