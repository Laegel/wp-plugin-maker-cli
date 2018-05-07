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
		$this->log('Updating require-' . strtolower($currentFolder) . '.php');
		$content = '<?php return ' . var_export($toAdd, true) . ';';
		file_put_contents(self::$dir . DIRECTORY_SEPARATOR . 'require-' . strtolower($currentFolder) . '.php', $content);
	}

    private function browseFolder($folder) {
		$files = array_values(array_diff(scandir($folder . DIRECTORY_SEPARATOR), ['.', '..']));

		if (!empty($files)) {
			foreach ($files as $file) {
				if (is_dir($folder . DIRECTORY_SEPARATOR . $file)) { // Recurs
					self::browseFolder($folder . DIRECTORY_SEPARATOR . $file);
				} elseif ((substr($file, -15) === '_Controller.php')) { // Process *_Controller.php files only
					$class = self::$currentNamespace . '\\' .
						str_replace('-', '_', ucwords(str_replace([$folder . DIRECTORY_SEPARATOR, '.php'], '', $file), '-'));
					if (!class_exists($class)) {
						require_once $folder . DIRECTORY_SEPARATOR . $file;
					}

					$parser = new Method($class);
					$parsed = $parser->parse(function($method, $metadata) { // REFACTOR
						$actions = [];

						$params = [
							'priority', 'ajax', 'ajax_nopriv', 'namespace'
						];
						$data = [];
						foreach ($metadata as $key => $value) {
							if (!in_array($key, $params)) {
								$data[$key] = $value;
							}
						}
						
						if (0 !== strpos($method->name, 'action_') && 0 !== strpos($method->name, 'filter_')) {
							return;
						}
						$name = isset($metadata->ajax) ? 'wp_ajax_' . $method->name : $method->name;

						$actions[] = [
							'name' => (isset($metadata->namespace) ? $metadata->namespace : '') . str_replace(['action_', 'filter_'], '', $name, $count = 1),
							'callback' => $method->class . '::' . $method->name,
							'priority' => isset($metadata->priority) ? (int)$metadata->priority : 10,
							'args_count' => count($method->getParameters()),
							'data' => $data
						];

						if (isset($metadata->ajax_nopriv)) {
							$actions[] = [
								'name' => (isset($metadata->namespace) ? $metadata->namespace : '') . str_replace(['action_', 'filter_'], '', isset($metadata->ajax) ? 'wp_ajax_nopriv_' . $method->name : $method->name, $count = 1),
								'callback' => $method->class . '::' . $method->name,
								'priority' => isset($metadata->priority) ? (int)$metadata->priority : 10,
								'args_count' => count($method->getParameters()),
								'data' => $data
							];
						}

						return $actions;
					});

					if (!empty($parsed)) {
						self::$dataRequire[$class] = [
							'actions' => array_values($parsed),
							// 'path' => str_replace(self::$dir, '', $folder . DIRECTORY_SEPARATOR . $file)
						];
					}
				}
			}
		}
	}
}