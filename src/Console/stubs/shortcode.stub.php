<?php

namespace App\Shortcode;

use Ofernandoavila\TucanoCore\Core\Container;
use Ofernandoavila\TucanoCore\Core\Shortcode;

class ##{{NAME}}##Shortcode extends Shortcode
{
    public function __construct(
        Container $container
    ) {
        parent::__construct($container, '##{{SHORTCODE}}##', [$this, 'action']);
    }
        
    public function action()
    {
        // Insert action here
    }
}
