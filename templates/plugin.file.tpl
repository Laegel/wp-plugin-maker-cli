<?php
defined('ABSPATH') or die('Nothing to see here.');
/*
Plugin Name: {{PLUGIN_NAME}}
Version: 0.1.0
*/

require 'src' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

register_activation_hook(__FILE__, '{{PLUGIN_NS}}\Plugin::on_activate');
register_activation_hook(__FILE__, '{{PLUGIN_NS}}\Plugin::on_deactivate');

{{PLUGIN_NS}}\Plugin::init(realpath(dirname(__FILE__)));