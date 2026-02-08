<?php

namespace Ofernandoavila\TucanoCore\Console\Controller;

use Ofernandoavila\TucanoCore\Util\File;

class AngularController
{
    private array $paths = [];

    public function __construct(
        private string $appName
    ) {
        $this->paths = [
            'jsPath' => __DIR__ . '/../../../dist/' . $this->appName . '/browser',
            'mediaPath' => __DIR__ . '/../../../dist/' . $this->appName . '/browser/media',
            'jsAssetsPath' => __DIR__ . '/../../../assets/js',
            'cssAssetsPath' => __DIR__ . '/../../../assets/css',
            'mediaAssetsPath' => __DIR__ . '/../../../assets/css/media',
            'phpContentFilePath' => __DIR__ . '/../../../views/' . $this->appName . '-base.php',
            'indexPath' => __DIR__ . '/../../../dist/' . $this->appName . '/browser/index.html',
        ];
    }

    public function build()
    {
        $content = file_get_contents($this->paths['indexPath']);

        AngularController::generate_php($this->paths['phpContentFilePath'], $content);

        if (!file_exists(__DIR__ . '/../../../assets/js'))
            mkdir(__DIR__ . '/../../../assets/js');

        if (!file_exists(__DIR__ . '/../../../assets/css'))
            mkdir(__DIR__ . '/../../../assets/css');

        if (!file_exists(__DIR__ . '/../../../assets/css/media'))
            mkdir(__DIR__ . '/../../../assets/css/media');

        $this->move_angular_files();
        $this->move_assets_files();
    }

    protected function move_angular_files()
    {
        foreach (File::list_dir($this->paths['jsPath']) as $file) {
            if (substr($file, -4) == '.css')
                rename($this->paths['jsPath'] . '/' . $file, $this->paths['cssAssetsPath'] . '/' . $file);

            if (substr($file, -3) == '.js')
                rename($this->paths['jsPath'] . '/' . $file, $this->paths['jsAssetsPath'] . '/' . $file);
        }
    }

    protected function move_assets_files()
    {
        foreach (File::list_dir($this->paths['mediaPath']) as $file) {
            if ($file != 'media' && $file != 'index.html')
                rename($this->paths['mediaPath'] . '/' . $file, $this->paths['mediaAssetsPath'] . '/' . $file);
        }
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
