<?php
namespace WP_Plugin_Maker_CLI\Template;

class Template {

    private $template;

    private function __construct($template) {
        $this->template = $template;
    }

    public static function get($name) {
        return new self(file_get_contents(ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . '.tpl'));
    }

    public function render($data) {
        $rendered = $this->template;
        foreach ($data as $key => $value) {
            $rendered = str_replace('{{' . $key . '}}', $value, $rendered);
        }
        return $rendered;
    }
}