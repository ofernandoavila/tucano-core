<?php

namespace Ofernandoavila\TucanoCore\Core;

use Ofernandoavila\TucanoCore\Core\Container;
use Ofernandoavila\TucanoCore\Trait\ShortcodeTrait;

class Shortcode
{
    use ShortcodeTrait;

    public function __construct(
        protected Container $container,
        protected string $shortcode,
        callable $callback
    ) {
        add_shortcode($this->shortcode, $callback);
    }
}
