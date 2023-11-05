<?php

namespace Fratily\Tests\CloneWith\Mock;

class HasReadonlyProtectedProp
{
    protected readonly string $protected;

    public function __construct()
    {
        $this->protected = 'protected readonly property';
    }
}
