<?php

namespace Ofernandoavila\TucanoCore\Core;

use Ofernandoavila\TucanoCore\Util\File;

class Factory
{
    private array $jsVars = [];
    private Config $config;

    public function __construct(
        private Container $container
    ) {
        $this->container->register(Router::class, fn() => new Router());
        $this->container->register(Database::class, fn() => new Database($this->container));        
        
        $this->config = $this->container->resolve(Config::class);

        $this->container->register(Factory::class, fn() => $this);
    }

    public function install()
    {
        Database::createMigrationTable();

        if(file_exists($this->config->getMigrationsPath())) {
            $migrations = array_filter(scandir($this->config->getMigrationsPath()), fn($item) => $item != '.' && $item != '..');
            
            foreach($migrations as $migration) {
                $migrationName = explode('.', $migration)[0];
                $migration = require $this->config->getMigrationsPath() . '/' . $migration;

                Database::applyMigration($migrationName, $migration);
            }
        }

        Database::removeMigration('2026_01_20_021100_criar_tabela_niveis', require $this->config->getMigrationsPath() . '/2026_01_20_021100_criar_tabela_niveis.php');

        // foreach (File::list_dir($this->config->GetMigrationsPath()) as $migration) {
        //     $migration = require_once __DIR__ . '/../../database/migrations/' . $migration;
        //     $migration->Up();
        // }

        // foreach (File::list_dir($this->config->GetSeedsPath()) as $seed) {
        //     $seed = require_once __DIR__ . '/../../database/seeds/' . $seed;
        //     $seed->seed();
        // }
    }

    public function uninstall()
    {
        // foreach (array_reverse(File::list_dir($this->config->GetMigrationsPath())) as $migration) {
        //     $path = __DIR__ . '/../../database/migrations/' . $migration;

        //     $migration = require_once $path;
        //     $migration->Down();
        // }
    }

    public function init()
    {
        $this->addControllers();
        $this->addShortcodes();
        $this->addRouter();
        // $slug = sanitize_title($config['nome']) . '-admin-menu';

        // add_action('admin_menu', function () use ($config, $slug) {
        //     add_menu_page(
        //         $config['nome'],
        //         $config['nome'],
        //         'manage_options',
        //         $slug,
        //         $config['callback'],
        //         $config['icon'],
        //         56
        //     );
        // });

        // add_action('admin_enqueue_scripts', function () {
        //     wp_register_style('trabalhe-conosco-admin-css', false);
        //     wp_enqueue_style('trabalhe-conosco-admin-css');

        //     wp_add_inline_style('trabalhe-conosco-admin-css', '
        //         #wpbody,
        //         #wpbody-content {
        //             height: 100% !important;
        //             padding-bottom: 0px !important;
        //         }
        //         #wpfooter {
        //             display: none !important;
        //         }

        //         dd,li {
        //             margin-bottom: 0px;
        //         }
        //     ');
        // });

        // add_action('admin_enqueue_scripts', function () {
        //     wp_localize_script('jquery', 'CcaaTrabalheConosco', $this->variaveisJs);
        // }, 1);

        // add_action('wp_enqueue_scripts', function () {
        //     wp_localize_script('jquery', 'CcaaTrabalheConosco', $this->variaveisJs);
        // }, 1);

        return $this;
    }

    private function addRouter() {
        if(file_exists($this->config->getRoutesPath())) {
            $router = $this->container->resolve(Router::class);
            require $this->config->getRoutesPath();
            $router->Publish();
        }

        return $this;
    }

    private function addControllers() {
        if(file_exists($this->config->getControllerDirPath())) {
            $controllers = array_filter(scandir($this->config->getControllerDirPath()), fn($item) => $item != '.' && $item != '..');
            
            foreach($controllers as $controller) {
                $className = "\App\Http\Controller\\" . explode('.', $controller)[0];
                $class = new $className($this->container);

                $this->container->register($class::class, fn() => $class);
            }
        }

        return $this;
    }

    private function addShortcodes() {
        if(file_exists($this->config->getShortcodesDirPath())) {
            $controllers = array_filter(scandir($this->config->getShortcodesDirPath()), fn($item) => $item != '.' && $item != '..');
            
            foreach($controllers as $controller) {
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
