<?php

header('Content-type: text/html; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';

spl_autoload_register(function ($class) {
	$classPath = str_replace('\\', '/', $class);
	include __DIR__.'/'.$classPath.'.php';
});

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$loader = new \Twig\Loader\FilesystemLoader('../templates/');
$twig = new \Twig\Environment($loader);