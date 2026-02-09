<?php

namespace App\Http\Controller;

use App\Model\##{{NAME}}##;
use Ofernandoavila\TucanoCore\Core\Container;
use Ofernandoavila\TucanoCore\Core\Controller;

class ##{{NAME}}##Controller extends Controller
{
    public function __construct(
        private Container $container
    ) {
        parent::__construct(##{{NAME}}##::class);
    }
}
