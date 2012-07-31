<?php

require_once __DIR__ . '/Builder.php';

if (count($argv) < 1) {
    die("<file> <template> <outputdir>\n");
}

$file = $argv[1];
$template = array_key_exists(2, $argv) ? $argv[2].'Template' : 'JavaTemplate';
$ouputdir = array_key_exists(3, $argv) ? $argv[3] : 'output/';

require_once __DIR__ . "/$template.php";

$json = json_decode(file_get_contents($argv[1]));

$builder = new Builder($json, new $template());
$builder->writeClass();
?>
