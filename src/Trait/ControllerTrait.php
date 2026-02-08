<?php

namespace Ofernandoavila\TucanoCore\Trait;

use WP_REST_Request;
use WP_REST_Response;

trait ControllerTrait
{
    public function enviar_response(mixed $data = '', int $code = 200)
    {
        return new WP_REST_Response($data, $code);
    }

    public function obter_parametros(WP_REST_Request $request)
    {
        return $request->get_params();
    }

    protected function paged_data(\Illuminate\Database\Eloquent\Builder $query, $page, $perPage)
    {
        return [
            'page' => $page,
            'perPage' => $perPage,
            'totalItems' => $query->paginate(perPage: $perPage, page: $page)->total(),
            'data' => $query->paginate(perPage: $perPage, page: $page)->getCollection(),
        ];
    }
}
