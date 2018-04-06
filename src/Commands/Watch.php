<?php
namespace WP_Plugin_Maker_CLI\Commands;
use Illuminate\Filesystem\Filesystem;
use JasonLewis\ResourceWatcher\Tracker;
use JasonLewis\ResourceWatcher\Watcher;

class Watch extends Command {

    private static $codesMap = [
        0 => 'deleted',
        1 => 'created',
        2 => 'modified'
    ];

    public function execute() {
        $files = new Filesystem();
        $tracker = new Tracker();
        $watcher = new Watcher($tracker, $files);

        $listener = $watcher->watch(self::$dir . DIRECTORY_SEPARATOR . 'src');

        $listener->anything(function($event, $resource, $filePath) {
            $code = $event->getCode();
            $folder = $this->getEnvFromFilePath($filePath);
            if ('all' === $folder || in_array($folder, self::$folders)) {
                $this->onChange(self::$codesMap[$code] . 'Handler', $filePath);
            }
        });

        $this->log('Watching for file changes in ' . self::$dir . DIRECTORY_SEPARATOR . 'src');
        $watcher->start();
    }

    private function onChange($handler, $filePath) {
        $folder = $this->getEnvFromFilePath($filePath);
        
        $build = new Build();
        $options = self::getStringOptions();
        if (in_array($folder, self::$folders)) {
            $options .= ' --folder="' . $folder . '"';
        } 
        exec(self::$program . ' ' . $options . ' build');
        $this->$handler($filePath);
    }

    private function deletedHandler($filePath) {
        $this->log('Deleted file: ' . $filePath);
    }

    private function createdHandler($filePath) {
        $this->log('Created file: ' . $filePath);
    }

    private function modifiedHandler($filePath) {
        $this->log('Modified file:' . $filePath);
    }

    private function getEnvFromFilePath($filePath) {
        $replaced = str_replace(self::$dir . DIRECTORY_SEPARATOR, '', $filePath);
        return explode(DIRECTORY_SEPARATOR, $replaced)[1];
    }
}