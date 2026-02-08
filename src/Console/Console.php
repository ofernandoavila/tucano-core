<?php

namespace Ofernandoavila\TucanoCore\Console;

use Illuminate\Container\Container;

class Console extends Container
{
    public function runningUnitTests(): bool
    {
        return false;
    }
}
