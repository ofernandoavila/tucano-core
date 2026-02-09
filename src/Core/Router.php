<?php

namespace Ofernandoavila\TucanoCore\Core;

class Router
{
    public string $endpoint = '';
    public string $version = 'v1';
    public array $routes = [];

    private bool $isGroupConfig = false;
    private ?string $groupName;

    public function group(string $groupName, mixed $callback)
    {
        $this->isGroupConfig = true;
        $this->groupName = $groupName;

        $callback();

        $this->isGroupConfig = false;
        $this->groupName = null;
    }

    public function get(string $route, $callback, bool $permision = false)
    {
        return $this->registerRoute($route, 'GET', $callback, $permision);
    }

    public function post(string $route, $callback, bool $permision = false)
    {
        return $this->registerRoute($route, 'POST', $callback, $permision);
    }

    public function patch(string $route, $callback, bool $permision = false)
    {
        return $this->registerRoute($route, 'PATCH', $callback, $permision);
    }

    public function delete(string $route, $callback, bool $permision = false)
    {
        return $this->registerRoute($route, 'DELETE', $callback, $permision);
    }


    private function registerRoute(string $route, string $method, $callback, bool $permision = false)
    {
        if ($this->isGroupConfig)
            $route = $this->groupName . $route;

        return $this->routes[] = ["route" => $route, "method" => $method, "callback" => $callback, 'permision' => $permision];
    }

    public function publish()
    {
        add_action('rest_api_init', function () {
            foreach ($this->routes as $rota) {

                register_rest_route("$this->endpoint/$this->version", $rota['route'], array(
                    'methods'  => $rota['method'],
                    'callback' => $rota['callback'],
                    'permission_callback' => function () use ($rota) {
                        if ($rota['permision'])
                            return is_user_logged_in();

                        return true;
                    }
                ));
            }
        });
    }
}
