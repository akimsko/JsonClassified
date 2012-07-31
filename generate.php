<?php

require_once __DIR__ . '/Builder.php';

if (count($argv) < 1) {
    die("<file> <template> <classname> <outputdir>\n");
}

$file = $argv[1];
$template = array_key_exists(2, $argv) ? ucfirst(strtolower($argv[2])).'Template' : 'JavaTemplate';
$classname = array_key_exists(3, $argv) ? $argv[3] : 'MyClass';
$ouputdir = array_key_exists(4, $argv) ? $argv[4] : 'output/';

require_once __DIR__ . "/$template.php";

$json = json_decode(file_get_contents($argv[1]));

$builder = new Builder($json, new $template(), $classname, $ouputdir);
$builder->writeClass();
?>
