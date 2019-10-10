<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/connection.php';

spl_autoload_register(function ($class) {
	$classPath = str_replace('\\', '/', $class);
	include __DIR__.'/src/'.$classPath.'.php';
});

// considering separation of concerns, you should only have includables in here, and loader, twig, cookies, login etc should be moved elsewhere.

$authenticated = false;
// THESE ARE UNSAFE AND ONLY FOR TESTING
if (!empty($_POST)) {
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
// echo $username;

$password = password_hash(
    filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)
    , PASSWORD_DEFAULT);
// echo $password;
header('location: index.php');
}


$loader = new \Twig\Loader\FilesystemLoader('../templates/');
$twig = new \Twig\Environment($loader);


