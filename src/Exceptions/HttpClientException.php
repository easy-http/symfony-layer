<?php

namespace Pleets\HttpClient\Exceptions;

use Exception;
use Throwable;

class HttpClientException extends Exception
{
    public static function fromThrowable(Throwable $throwable): self
    {
        return new self($throwable->getMessage(), (int) $throwable->getCode(), $throwable);
    }
}
