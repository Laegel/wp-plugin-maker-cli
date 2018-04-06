<?php
namespace WP_Plugin_Maker_CLI\Metadata;

abstract class Parser {
    
    protected $class;

    public function __construct($class) {
        $this->class = $class;
        $this->reflected = new \ReflectionClass($class);
    }
}