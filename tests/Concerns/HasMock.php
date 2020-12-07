<?php

namespace Tests\Concerns;

trait HasMock
{
    protected $mock;

    protected function setMock(callable $mock): void
    {
        $this->mock = $mock;
    }
}
