<?php

namespace Ofernandoavila\TucanoCore\Trait;

use Ofernandoavila\TucanoCore\Core\Factory;

trait ShortcodeTrait
{
    protected function getFromQueryString()
    {
        $data = [];

        if (explode('&', $_SERVER['QUERY_STRING'])[0] == '')
            return $data;

        foreach (explode('&', $_SERVER['QUERY_STRING']) as $query) {
            if (sizeof(explode('=', $query)) > 1)
                $data[explode('=', $query)[0]] = explode('=', $query)[1];
        }

        return $data;
    }

    protected function addJSVar(string $chave, mixed $valor)
    {
        $this->container
             ->Resolve(Factory::class)
             ->addJSVar($chave, $valor);
    }

    protected function getAssetsPath() {
        return str_replace('vendor/ofernandoavila/tucano-core/src/', '', plugin_dir_url(__DIR__)) . 'assets';
    }
}
