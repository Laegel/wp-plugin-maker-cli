#!/usr/bin/env php
<?php
require 'polyfills.php';

use CLIParser\CLIParser;

define('ROOT', realpath(dirname(__FILE__)));

$shortOptions = 'p';

$longOptions = [
    ['polyfills', false, 'p'],
    ['folder', true],
    ['name', true],
    ['type', true],
];

$commands = ['build', 'init', 'generate', 'watch'];

$cli = new CLIParser($shortOptions, $longOptions);

$program = $cli->program();
$options = $cli->options();
$arguments = $cli->arguments();

$command = $arguments[0];
if (!in_array($command, $commands)) {
    die('Invalid command "' . $command . '"');
}

$class = 'WP_Plugin_Maker_CLI\Commands\\' . ucfirst($command);
$class::init($program, $options);
$instance = new $class();
$instance->execute();