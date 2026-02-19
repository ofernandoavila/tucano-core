<?php

namespace Ofernandoavila\TucanoCore\Util;

class File
{
    public static $rootPath = __DIR__;

    public static function writeFile(string $path, string $template, array $data = [])
    {
        if (file_exists($path))
            unlink($path);

        if (sizeof($data) > 0) {
            foreach ($data as $key => $value) { 
                $template = str_replace('##{{' . strtoupper($key) . '}}##', $value, $template);
            }
        }

        file_put_contents($path, $template);

        return true;
    }

    public static function readFile(string $path)
    {
        return file_get_contents($path);
    }

    public static function listDir(string $path)
    {
        $list = [];

        foreach (array_filter(scandir($path), fn($item) => $item != '.' && $item != '..') as $key => $value) {
            if (is_dir($path . '/' . $value))
                $list = array_merge($list, File::listDir($path . '/' . $value));

            if (is_file($path . '/' . $value))
                $list[] = $path . '/' . $value;
        }

        return $list;
    }

    public static function getStubContent(string $name)
    {
        return file_get_contents(self::$rootPath . '/../Console/stubs/' . $name . '.stub.php');
    }
}
