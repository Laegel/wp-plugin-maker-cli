<?php
namespace WP_Plugin_Maker_CLI\Commands;

abstract class Command {
    public static $dir;
    public static $program;
    public static $options = [];
    
    protected static $folders = [
        'admin', 'cli', 'front', 'rest'
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
}