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
            $returned = $callback($method, $parsed);
            for ($i = 0; $i < count($returned); ++$i) {
                $metadata[] = $returned[$i];
            }
        }
        return array_filter($metadata);
    }

    protected function parse_method(\ReflectionMethod $method) {
		$comment = $method->getDocComment();

		$metadata = (object)[];

		if (!empty(preg_match_all('~@(?:([\w]+)\s*(?:\s*\(\s*(.*)\))?)\n*~', $comment, $parsed, PREG_SET_ORDER))) {
            foreach ($parsed as $match) {
                $metadata->{$match[1]} = isset($match[2]) ? $match[2] : true;
            }
        }
		return $metadata;
	}

}