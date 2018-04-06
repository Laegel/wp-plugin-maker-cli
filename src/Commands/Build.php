<?php
namespace WP_Plugin_Maker_CLI\Commands;

use WP_Plugin_Maker_CLI\Metadata\Method;

class Build extends Command {

    private static $currentNamespace;
    private static $dataRequire = [];

    public function execute() {
		if (isset(self::$options->folder) && in_array(self::$options->folder, self::$folders)) {
			self::browseFolders(self::$options->folder);
			return;
		}

        foreach (self::$folders as $folder) {
            self::browseFolders($folder);
        }
    }
    
    public function browseFolders($currentFolder) {
		$folders = ['all', $currentFolder];
        $toAdd = [];

		foreach ($folders as $folder) {
			self::$dataRequire = [];
			self::$currentNamespace = self::pluginDirNameToNamespace() . '\\' . ucfirst($folder);
			self::browseFolder(self::$dir . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $folder);

			$toAdd = array_merge($toAdd, self::$dataRequire);
		}
		$this->saveHooks($currentFolder, $toAdd);
    }

	public function saveHooks($currentFolder, $toAdd) {
		$this->log('Updating require-' . $currentFolder . '.php');
		$content = '<?php return ' . var_export($toAdd, true) . ';';
		file_put_contents(self::$dir . DIRECTORY_SEPARATOR . 'require-' . $currentFolder . '.php', $content);
	}

    private function browseFolder($folder) {
		$files = array_values(array_diff(scandir($folder . DIRECTORY_SEPARATOR), ['.', '..']));

		if (!empty($files)) {
			foreach ($files as $file) {
				if (is_dir($folder . DIRECTORY_SEPARATOR . $file)) { // Recurs
					self::browseFolder($folder . DIRECTORY_SEPARATOR . $file);
				} elseif ((substr($file, -15) === '_Controller.php')) { // Process *_Controller.php files only
					require_once $folder . DIRECTORY_SEPARATOR . $file;
					$class = self::$currentNamespace . '\\' .
						str_replace('-', '_', ucwords(str_replace([$folder . DIRECTORY_SEPARATOR, '.php'], '', $file), '-'));

					$parser = new Method($class);
					$parsed = $parser->parse(function($method, $metadata) {
						/*
									// Is AJAX
			if (isset($metadata->ajax) || isset($metadata->ajax_nopriv)) {
				$params = $reflection_method->getParameters();

				$action = [
					'callback'   => $method->class . '::' . $method->name,
					'priority'   => isset($metadata->priority) ? (int)$metadata->priority : 10,
					'args_count' => count($params)
				];

				if (isset($metadata->ajax)) {
					$action['name'] = 'wp_ajax_' . $method->name;
					$data['actions'][] = $action;
				}

				if (isset($metadata->ajax_nopriv)) {
					$action['name'] = 'wp_ajax_nopriv_' . $method->name;
					$data['actions'][] = $action;
				}
			}
						*/
						return [
							'name' => (isset($metadata->namespace) ? $metadata->namespace : '') . str_replace(['action_', 'filter_'], '', $method->name, $count = 1),
							'callback' => $method->class . '::' . $method->name,
							'priority' => isset($metadata->priority) ? (int)$metadata->priority : 10,
							'args_count' => count($method->getParameters())
						];
					});

					self::$dataRequire[$class] = [
						'actions' => $parsed,
						'path' => str_replace(self::$dir, '', $folder . DIRECTORY_SEPARATOR . $file)
					];
				}
			}
		}
	}
}