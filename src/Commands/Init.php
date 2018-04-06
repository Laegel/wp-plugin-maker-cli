<?php
namespace WP_Plugin_Maker_CLI\Commands;
use WP_Plugin_Maker_CLI\Template\Template;

class Init extends Command {

    public function execute() {
        $dirName = self::getPluginDirName(self::$options->name);
        $pluginDir = self::$dir . DIRECTORY_SEPARATOR . $dirName;
        mkdir($pluginDir);
        $srcDir = self::$dir . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        mkdir($srcDir);
        foreach (['admin', 'all', 'cli', 'front', 'rest'] as $dir) {
            mkdir($srcDir . $dir);
        }

        $namespace = str_replace(' ', '', ucwords(str_replace(['-', ' '], '_', self::$options->name), '_'));

        $pluginFile = Template::get('plugin.file');
        
        $tmp = $pluginFile->render([
            'PLUGIN_NAME' => self::$options->name,
            'PLUGIN_NS' => $namespace
        ]);
        file_put_contents($pluginDir . DIRECTORY_SEPARATOR . 'plugin.php', $tmp);

        $classFile = Template::get('plugin.class');

        $tmp = $classFile->render([
            'PLUGIN_NS' => $namespace
        ]);
        file_put_contents($srcDir . 'Plugin.php', $tmp);
    }

}