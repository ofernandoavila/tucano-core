<?php

namespace Ofernandoavila\TucanoCore\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildAngularCommand extends Command
{
    public function __construct(
        private string $rootPath
    ) {
        $parent = parent::__construct('build:angular');

        return $parent;
    }

    protected function configure(): void
    {
        $this->setDescription('Build the web components');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!file_exists($this->rootPath . '/components')) {
            $output->writeln('Project components not found.');
            return Command::INVALID;
        }

        shell_exec("cd ./components && npm run build");

        $dist = $this->rootPath . '/components/dist/webcomponents/browser';
        $index = file_get_contents($dist . '/index.html');
        self::generate_php($this->rootPath . '/app/View/components-base.php', $index);
        shell_exec("cd " . $dist . " && mv *.js " . $this->rootPath . "/assets/js/");
        shell_exec("cd " . $dist . " && mv *.css " . $this->rootPath . "/assets/css/");

        if (file_exists($dist . '/media')) {
            shell_exec("cd " . $dist . " && rm -rf " . $this->rootPath . "/assets/css/media && mv ./media " . $this->rootPath . "/assets/css");
        }

        return Command::SUCCESS;
    }

    public static function generate_php(string $path, string $html, string $urlVar = '<?= $url; ?>')
    {
        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $saida = '';

        foreach ($dom->getElementsByTagName('style') as $style) {
            $saida .= $dom->saveHTML($style);
        }

        $saida .= '<link rel="stylesheet" href="<?= $url; ?>/css/styles.css" media="print" onload="this.media=\'all\'"><noscript><link rel="stylesheet" href="styles.css"></noscript>';

        foreach ($dom->getElementsByTagName('link') as $link) {
            if ($link->getAttribute('rel') === 'modulepreload') {
                $href = $link->getAttribute('href');
                $arquivo = basename($href);

                $saida .= "\n<link rel=\"modulepreload\" href=\"{$urlVar}/js/{$arquivo}\">";
            }
        }

        foreach ($dom->getElementsByTagName('script') as $script) {
            if ($script->getAttribute('type') === 'module') {
                $src = $script->getAttribute('src');
                $arquivo = basename($src);

                $saida .= "\n<script src=\"{$urlVar}/js/{$arquivo}\" type=\"module\"></script>";
            }
        }

        file_put_contents($path, $saida);

        return true;
    }
}
