<?php

namespace Ofernandoavila\TucanoCore\Core;

use Ofernandoavila\TucanoCore\Trait\ControllerTrait;

class Controller
{
    use ControllerTrait;

    public function __construct(
        protected string $model
    ) {}

    public function index(\WP_REST_Request $request)
    {
        $params = $this->getParams($request);

        $page = isset($params['page']) ? intval($params['page']) : 1;
        $perPage = isset($params['perPage']) ? intval($params['perPage']) : 10;

        if ($perPage == -1)
            return $this->send($this->model::all());

        $query = $this->model::newQuery();

        if (isset($params['id']))
            return $this->getById($params['id']);

        $query = $this->sanitizeFilters($query, $params);

        return $this->pagedData($query, $page, $perPage);
    }

    public function create(\WP_REST_Request $request)
    {
        $entity =  $this->model::make($this->getParams($request));

        $entity->ativo = true;
        $entity->save();

        return $this->send(null, 201);
    }

    public function update(\WP_REST_Request $request)
    {
        $params = json_decode($request->get_body());
        $model = $this->getById($params->id);

        if (!$model)
            return $this->send(null, 404);

        if (isset($params->descricao))
            $model->descricao = $params->descricao;

        if (isset($params->ativo))
            $model->ativo = $params->ativo;

        $model->save();

        return $this->send();
    }

    public function delete(\WP_REST_Request $request)
    {
        $model = $this->getById($this->getParams($request)['id']);

        if (!$model)
            return $this->send(null, 404);

        $model->delete();

        return $this->send();
    }

    protected function getById(string $id)
    {
        $model = $this->model::where('id', $id)->get();

        return sizeof($model) > 0 ? $model[0] : null;
    }

    protected function sanitizeFilters(\Illuminate\Database\Eloquent\Builder $query, array $filter)
    {
        return $query;
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
