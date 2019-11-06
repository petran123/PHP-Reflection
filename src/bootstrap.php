<?php

header('Content-type: text/html; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';

spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', '/', $class);
    include __DIR__.'/classes/'.$classPath.'.php';
});

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

ini_set('session.use_strict_mode', 1);
session_start();

$refresh = strtok($_SERVER['REQUEST_URI'], '?');

if (request()->cookies->has('access_token')) {
    try {
        $loginCookie = \Firebase\JWT\JWT::decode($_COOKIE["access_token"], getenv('COOKIES_SECRET_KEY'), array('HS256'));
        $details = $loginCookie->data;
        $acc = new AccOps($details->userId, $details->rank);
    } catch (Exception $e) {
        //currently it simply refreshes the page if 15 minutes has passed. I could fix this in other ways but i'm okay with that for an admin panel.
        throw $e;
        header("Refresh:0");
    }
}

if (!isset($acc)) {
    $acc = new AccOps();
}


if (isset($_GET['logout'])) {
    $acc->logout($refresh);
}

$args = [ 'username' => $acc->getUsername(),
        'rank' => $acc->getRank(),
        'refresh' => $refresh];

$loader = new \Twig\Loader\FilesystemLoader('../templates/');
$twig = new \Twig\Environment($loader);
