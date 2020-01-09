<?php

header('Content-type: text/html; charset=utf-8');

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

require_once __DIR__ . '/helpers.php';

registerAutoload();

startSession();

$refresh = strtok($_SERVER['REQUEST_URI'], '?');
$database = new Database();
$user = authenticate($database);
$auth = new Auth($database, $user);


if (isset($_GET['logout'])) {
    $auth->logout($refresh);
}

$args = [
    'user' => $user->getUsername(),
    'rank' => $user->getRank(),
    'refresh' => $refresh
];

$loader = new \Twig\Loader\FilesystemLoader('../templates/');
$twig = new \Twig\Environment($loader);
