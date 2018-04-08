<?php
namespace WP_Plugin_Maker_CLI\Commands;
use WP_Plugin_Maker_CLI\Template\Template;

abstract class Command {
    public static $dir;
    public static $program;
    public static $options = [];
    
    protected static $folders = [
        'Admin', 'CLI', 'Front', 'REST'
    ];

    public static function init($program, $options) {
        self::$dir = getcwd();
        self::$program = $program;
        self::$options = (object)$options;
        if (isset(self::$options->p) || isset($options['polyfills'])) {
            require self::$dir . DIRECTORY_SEPARATOR . 'polyfills.php';
        }
    }

    protected static function getStringOptions() {
        $options = [];
        foreach (self::$options as $key => $value) {
            if (is_bool($value)) {
                $options[] = '-' . $key;
            } else {
                $options[] = '--' . $key . '="' . $value . '"';
            }
        }
        return implode(' ', $options);
    }

    protected function log($message) {
        print $message . PHP_EOL;
    }
    
    abstract public function execute();

    public static function getPluginDirName($name) {
        return strtolower(str_replace(' ', '', ucwords(str_replace(['_', ' '], '-', $name))));
    }

    public static function pluginDirNameToNamespace() {
        $e = explode(DIRECTORY_SEPARATOR, self::$dir);
        return str_replace(' ', '', ucwords(str_replace(['-', ' '], '_', $e[count($e) - 1]), '_'));
    }

    protected function saveFileFromTemplate($name, $path, array $vars = []) {
        $template = Template::get($name);
        file_put_contents($path, $template->render($vars));
    }

    protected function getEnvFromFilePath($filePath) {
        $replaced = str_replace(self::$dir . DIRECTORY_SEPARATOR, '', $filePath);
        return explode(DIRECTORY_SEPARATOR, $replaced)[1];
    }

    protected function getPluginInfo() {
        return require self::$dir . DIRECTORY_SEPARATOR . 'wpm-info.php';
    }
}