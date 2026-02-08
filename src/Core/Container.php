<?php

namespace Ofernandoavila\TucanoCore\Core;

class Container
{
    private $bindings = [];

    public function register(string $class, mixed $resolver)
    {
        $this->bindings[$class] = call_user_func($resolver);
    }

    public function resolve(string $class)
    {
        if (!isset($this->bindings[$class]))
            throw new \Exception("Class not found: " . $class);

        return $this->bindings[$class];
    }
}
