<?php

namespace Ofernandoavila\TucanoCore\Trait;

trait ControllerTrait
{
    public function send(mixed $data = '', int $code = 200)
    {
        return new \WP_REST_Response($data, $code);
    }

    public function getParams(\WP_REST_Request $request)
    {
        return $request->get_params();
    }

    protected function pagedData(\Illuminate\Database\Eloquent\Builder $query, $page, $perPage)
    {
        return [
            'page' => $page,
            'perPage' => $perPage,
            'totalItems' => $query->paginate(perPage: $perPage, page: $page)->total(),
            'data' => $query->paginate(perPage: $perPage, page: $page)->getCollection(),
        ];
    }
}
