<?php

namespace Ofernandoavila\TucanoCore\Core;

class Factory
{
    private array $jsVars = [];
    private Config $config;

    public function __construct(
        private Container $container
    ) {
        $this->container->register(Router::class, fn() => new Router());
        $this->container->register(Database::class, fn() => new Database($this->container));
        $this->container->register(Migration::class, fn() => new Migration($this->container));

        $this->config = $this->container->resolve(Config::class);

        $this->container->register(Factory::class, fn() => $this);
    }

    public function install()
    {
        $migration = $this->container->resolve(Migration::class);

        $migration->createMigrationTable();
        $migration->migrate();
    }

    public function uninstall()
    {
        $migration = $this->container->resolve(Migration::class);

        $migration->drop();
        $migration->dropMigrationTable();
    }

    public function init()
    {
        $this->addControllers();
        $this->addShortcodes();
        $this->addRouter();

        if ($this->config->getWebComponentsConfig() && $this->config->getWebComponentsConfig()['global_js_variable']) {
            add_action('wp_enqueue_scripts', function () {
                wp_localize_script('jquery', $this->config->getWebComponentsConfig()['global_js_variable'], $this->jsVars);
            }, 1);
        }

        return $this;
    }

    private function addRouter()
    {
        if (file_exists($this->config->getRoutesPath())) {
            $router = $this->container->resolve(Router::class);
            require $this->config->getRoutesPath();
            $router->Publish();
        }

        return $this;
    }

    private function addControllers()
    {
        if (file_exists($this->config->getControllerDirPath())) {
            $controllers = array_filter(scandir($this->config->getControllerDirPath()), fn($item) => $item != '.' && $item != '..');

            foreach ($controllers as $controller) {
                $className = "\App\Http\Controller\\" . explode('.', $controller)[0];
                $class = new $className($this->container);

                $this->container->register($class::class, fn() => $class);
            }
        }

        return $this;
    }

    private function addShortcodes()
    {
        if (file_exists($this->config->getShortcodesDirPath())) {
            $controllers = array_filter(scandir($this->config->getShortcodesDirPath()), fn($item) => $item != '.' && $item != '..');

            foreach ($controllers as $controller) {
                $className = "\App\Shortcode\\" . explode('.', $controller)[0];
                $class = new $className($this->container);

                $this->container->register($class::class, fn() => $class);
            }
        }

        return $this;
    }

    public function addJSVar(string $chave, mixed $valor)
    {
        $this->jsVars[$chave] = $valor;

        return $this;
    }
}
