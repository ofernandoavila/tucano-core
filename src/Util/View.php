<?php

namespace Ofernandoavila\TucanoCore\Util;

use WP_REST_Request;

class View
{
    public static function webcomponent(string $path, mixed $data = [])
    {
        if (!file_exists($path))
            throw new \Exception("View not found: " . $path);

        extract($data);
        ob_start();

        try {
            include_once __DIR__ . '/../../app/View/components-base.php';
            include $file;
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }
}
