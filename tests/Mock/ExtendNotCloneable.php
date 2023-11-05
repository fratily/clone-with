<?php

namespace Fratily\Tests\CloneWith\Mock;

class ExtendNotCloneable extends NotCloneable
{
    public function __clone()
    {
    }
}
