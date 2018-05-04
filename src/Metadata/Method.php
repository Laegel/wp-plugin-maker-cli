<?php
namespace WP_Plugin_Maker_CLI\Metadata;

class Method extends Parser {

    private $methods = [];

    public function __construct($class) {
        parent::__construct($class);
		$this->methods = $this->reflected->getMethods(\ReflectionMethod::IS_PUBLIC);        
    }

    public function parse($callback) {
        $metadata = [];
        foreach ($this->methods as $method) {
            $parsed = $this->parse_method($method);
            $metadata[] = $callback($method, $parsed);            
        }
        return array_filter($metadata);
    }

    protected function parse_method(\ReflectionMethod $method) {
		$comment = $method->getDocComment();

		$params = [
			'priority', 'ajax', 'ajax_nopriv', 'namespace'
		];

		$metadata = (object)[];

		foreach ($params as $param) {
            // [\w]+
			if (!empty(preg_match('~@(?:' . $param . '\s*(?:\s*\(\s*(.*)\))?)\n*~', $comment, $parsed))) {
				$metadata->$param = isset($parsed[1]) ? $parsed[1] : true;
			}
        }
		return $metadata;
	}

}