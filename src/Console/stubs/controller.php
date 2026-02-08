<?php

namespace App\Http\Controller;

use App\Models\##{{ name }}##;
use Ofernandoavila\TucanoCore\Core\Container;
use Ofernandoavila\TucanoCore\Core\Router;

class ##{{ name }}##Controller extends Controller
{
    public function __construct(
        private Container $container
    ) {
        parent::__construct(
            '##{{ table_name }}##',
            $this->container->Resolve(Router::class),
            new ##{{ name }}##()
        );
    }
}
