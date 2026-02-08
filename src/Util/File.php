<?php

namespace Ofernandoavila\TucanoCore\Util;

use Ofernandoavila\TucanoCore\Enum\StubEnum;

class File
{
    public static function list_dir(string $path)
    {
        return array_filter(scandir($path), function ($value) {
            return $value != '.' && $value != '..';
        });
    }

    public static function generate_file(string $file_name, StubEnum $stub, array $replaces = [])
    {
        $path = '';
        $template = File::read_file(self::get_paths()['stubs_folder'] . '/' . $stub->value);

        foreach ($replaces as $key => $replace)
            $template = str_replace("##{{ " . $key . " }}##", $replace, $template);

        if ($stub == StubEnum::MIGRATION)
            $path = self::get_paths()['migrations_folder']  . '/' . $file_name . '.php';

        if ($stub == StubEnum::SEED)
            $path = self::get_paths()['seeds_folder']  . '/' . $file_name . '.php';

        if ($stub == StubEnum::MODEL)
            $path = self::get_paths()['model_folder']  . '/' . $file_name . '.php';

        if ($stub == StubEnum::CONTROLLER)
            $path = self::get_paths()['controller_folder']  . '/' . $file_name . '.php';

        return File::write_file($path, $template);
    }

    public static function write_file(string $path, string $content = '')
    {
        return file_put_contents($path, $content);
    }

    public static function read_file(string $path)
    {
        if (!file_exists($path))
            throw new \Exception("File not found: " . $path);

        return file_get_contents($path);
    }

    public static function get_dir(string $key)
    {
        $paths = self::get_paths();

        return File::list_dir($paths[$key]);
    }

    public static function get_config_file(string $key)
    {
        $paths = self::get_paths();

        $content = require $paths[$key];

        return $content;
    }

    public static function get_path(string $key) {
        return self::get_paths()[$key];
    }

    private static function get_paths()
    {
        return require __DIR__ . '/../Config/paths.php';
    }
}
