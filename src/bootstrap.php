<?php

header('Content-type: text/html; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/deprecated.php';

spl_autoload_register(function ($class) {
	$classPath = str_replace('\\', '/', $class);
	include __DIR__.'/'.$classPath.'.php';
});

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

if (AccOps::request()->cookies->has('access_token')) {
	$decoded = \Firebase\JWT\JWT::decode($_COOKIE["access_token"], getenv('SECRET_KEY'),array('HS256'));
	$creds = $decoded->data;
}  else {
	// this should be your "accops" class and the login variables should be its object properties. ie null or 0 or 'wrong' or whatever
	$creds = new stdClass;
	$creds->username = 0;
	$creds->rank = 0;
}
//creds is now an object with your credentials


$loader = new \Twig\Loader\FilesystemLoader('../templates/');
$twig = new \Twig\Environment($loader);