<?php

namespace Fratily\Tests\CloneWith\Mock;

class HasReadonlyPublicProp
{
    public readonly string $public;

    public function __construct()
    {
        $this->public = 'public readonly property';
    }
}
