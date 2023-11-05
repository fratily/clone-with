<?php

namespace Fratily\Tests\CloneWith\Mock;

class HasReadonlyPrivateProp
{
    private readonly string $private; // @phpstan-ignore-line

    public function __construct()
    {
        $this->private = 'private readonly property';
    }
}
