<?php
namespace WP_Plugin_Maker_CLI\Commands;

class Generate extends Command {

    private static $types = [
        'controller' => 'Controller', 'custom_type' => 'Custom_Type'
    ];

    public function execute() {
        $type = !isset(self::$options->type) || !isset(self::$types[self::$options->type]) ?
            'controller' :
            self::$options->type
        ;

        if (!isset(self::$options->name)) {
            die('"name" parameter is required.');
        }

        if (!isset(self::$options->folder) || !in_array(self::$options->folder, self::$folders)) {
            $folder = 'All';
        } else {
            $folder = self::$options->folder;
        }

        $info = $this->getPluginInfo();

        $name = str_replace(' ', '', ucwords(str_replace(['-', ' '], '_', self::$options->name), '_'));

        $this->saveFileFromTemplate(
            $type . '.class',
            self::$dir . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $name . '_Controller.php',
            [
                'PLUGIN_DIR' => self::getPluginDirName(self::$options->name),
                'PLUGIN_NS' => $info->namespace,
                'DIRECTORY_NS' => $folder,
                'CONTROLLER_NAME' => $name . '_Controller',
                'CUSTOM_TYPE' => strtolower(str_replace(' ', '', str_replace(['-', ' '], '_', self::$options->name)))
            ]
        );
    }

}