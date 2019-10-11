<?php

header('Content-type: text/html; charset=utf-8');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/connection.php';

spl_autoload_register(function ($class) {
	$classPath = str_replace('\\', '/', $class);
	include __DIR__.'/src/'.$classPath.'.php';
});

// considering separation of concerns, you should only have includables in here, and maybe loader, twig, cookies, login etc should be moved elsewhere.

$loader = new \Twig\Loader\FilesystemLoader('../templates/');
$twig = new \Twig\Environment($loader);


