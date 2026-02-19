<?php

namespace Ofernandoavila\TucanoCore\Core;

class Factory
{
    private array $jsVars = [];
    private Config $config;
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->config = $this->container->resolve(Config::class);
        $this->container->register(Router::class, fn() => new Router());
        $this->container->register(Database::class, fn() => new Database($this->container));
        $this->container->register(Migration::class, fn() => new Migration($this->container));
        $this->container->register(Factory::class, fn() => $this);
    }
    public function install()
    {
        $this->container
             ->resolve(Migration::class)
             ->createMigrationTable()
             ->migrate();
    }
    public function uninstall()
    {
        $this->container
             ->resolve(Migration::class)
             ->drop()
             ->dropMigrationTable();
    }
    public function init()
    {
        $this->addControllers()
             ->addShortcodes()
             ->addRouter();

        if ($this->config->getWebComponentsConfig() && $this->config->getWebComponentsConfig()['global_js_variable']) {
            \add_action('wp_enqueue_scripts', function () {
                \wp_localize_script('jquery', $this->config->getWebComponentsConfig()['global_js_variable'], $this->jsVars);
            }, 1);
            \add_action('admin_enqueue_scripts', function () {
                \wp_localize_script('jquery', $this->config->getWebComponentsConfig()['global_js_variable'], $this->jsVars);
            }, 1);
        }
        return $this;
    }

    /**
     * Add the router settings to the core
     * 
     * @return Factory $this
     */
    private function addRouter()
    {
        if (\file_exists($this->config->getRoutesPath())) {
            $router = $this->container->resolve(Router::class);
            $container = $this->container;
            require $this->config->getRoutesPath();
            $router->Publish();
        }
        return $this;
    }

    /**
     * Add the controllers to the core
     * 
     * @return Factory $this
     */
    private function addControllers()
    {
        if (\file_exists($this->config->getControllerDirPath())) {
            $controllers = \array_filter(\scandir($this->config->getControllerDirPath()), fn($item) => $item != '.' && $item != '..');
            foreach ($controllers as $controller) {
                $className = $this->getNamespace($this->config->getControllerDirPath() . '/' . $controller) . '\\' . explode('.', $controller)[0];
                $class = new $className($this->container);
                $this->container->register($class::class, fn() => $class);
            }
        }
        return $this;
    }

    /**
     * Add the shotcodes to the core
     * 
     * @return Factory $this
     */
    private function addShortcodes()
    {
        if (\file_exists($this->config->getShortcodesDirPath())) {
            $controllers = \array_filter(\scandir($this->config->getShortcodesDirPath()), fn($item) => $item != '.' && $item != '..');
            foreach ($controllers as $controller) {
                $className = $this->getNamespace($this->config->getShortcodesDirPath() . '/' . $controller) . '\\' . explode('.', $controller)[0];
                $class = new $className($this->container);
                $this->container->register($class::class, fn() => $class);
            }
        }
        return $this;
    }

    /**
     * Register a JS variable to use
     * in the front-end. 
     * 
     * @return Factory $this
     */
    public function addJSVar(string $chave, mixed $valor)
    {
        $this->jsVars[$chave] = $valor;
        return $this;
    }

    /**
     * Return the namespace of the given PHP file
     * 
     * @return string
     */
    private function getNamespace(string $filePath): ?string
    {
        if (!is_file($filePath) || !is_readable($filePath))
            return null;

        $code = file_get_contents($filePath);
        
        if ($code === false)
            return null;

        $tokens = token_get_all($code);
        $namespace = '';

        foreach ($tokens as $key => $token) {
            if (is_array($token)) {
                if ($token[0] === T_NAMESPACE) {
                    $namespace = $tokens[$key + 2][1];
                    break;
                }
            }
        }

        return $namespace;
    }
}
