<?php

namespace Ofernandoavila\TucanoCore\Util;

use WP_REST_Request;

class View
{
    public static function get_file_as_string(string $path)
    {
        $file = __DIR__ . '/../../templates/' . $path . '.php';

        if (!file_exists($file))
            throw new \Exception("View não encontrada: " . $file);

        return file_get_contents($file);
    }

    public static function render(string $path, mixed $data = [])
    {
        $file = __DIR__ . '/../../templates/' . $path . '.php';

        if (!file_exists($file))
            throw new \Exception("View não encontrada: " . $file);

        extract($data);

        ob_start();

        try {
            include $file;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }

    public static function WebComponent(string $path, mixed $data = [])
    {
        $file = __DIR__ . '/../../views/' . $path . '.php';
        if (!file_exists($file))
            throw new \Exception("View não encontrada: " . $file);

        $terms = explode('/', __DIR__);
        $data['url'] = get_site_url() . "/wp-content/plugins/" . $terms[sizeof($terms) - 3] . "/assets";
        extract($data);

        ob_start();

        try {
            include_once __DIR__ . '/../../views/webcomponents-base.php';
            include $file;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }

    public function obter_parametros(WP_REST_Request $request)
    {
        return $request->get_params();
    }
}
