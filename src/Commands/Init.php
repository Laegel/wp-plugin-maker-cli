<?php
namespace WP_Plugin_Maker_CLI\Commands;

class Init extends Command {

    public function execute() {
        $dirName = self::getPluginDirName(self::$options->name);
        $pluginDir = self::$dir . DIRECTORY_SEPARATOR . $dirName;
        mkdir($pluginDir);
        $srcDir = self::$dir . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        mkdir($srcDir);
        foreach (array_merge(self::$folders, ['All']) as $dir) {
            mkdir($srcDir . $dir);
        }

        $namespace = str_replace(' ', '', ucwords(str_replace(['-', ' '], '_', self::$options->name), '_'));

        $this->saveFileFromTemplate(
            'plugin.file',
            $pluginDir . DIRECTORY_SEPARATOR . 'plugin.php',
            [
                'PLUGIN_NAME' => self::$options->name,
                'PLUGIN_NS' => $namespace
            ]
        );

        $this->saveFileFromTemplate(
            'composer.json',
            $pluginDir . DIRECTORY_SEPARATOR . 'composer.json',
            [
                'PLUGIN_DIR' => $dirName,
                'PLUGIN_NS' => $namespace
            ]
        );

        $this->saveFileFromTemplate(
            'plugin.class',
            $srcDir . 'Plugin.php',
            ['PLUGIN_NS' => $namespace]
        );

        $this->saveFileFromTemplate('gitignore', $pluginDir . DIRECTORY_SEPARATOR . '.gitignore');

        $this->saveFileFromTemplate(
            'wpm-info.file',
            $pluginDir . DIRECTORY_SEPARATOR . 'wpm-info.php',
            ['PLUGIN_NS' => $namespace]
        );
    }
}