<?php

require_once __DIR__ . '/../bootstrap.php';

// i have created a rudimentary login method with sessions. I will switch to JWT shortly.

session_start();


// SQL::createAccount("petran", "1234");
if (!empty($_POST)) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    // echo $username;
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    // echo $password;
    $authenticated = SQL::logIn($username, $password);
    $_SESSION['username'] = $authenticated['username'];
    $_SESSION['rank'] = $authenticated['rank'];
    var_dump($_SESSION);
    // SQL::logIn("petran", "1234");
    header('location: /admin.php');
}
if (!empty($_GET)) {
    session_destroy();
    session_start();
    $_SESSION['username'] = $authenticated[null];
    $_SESSION['rank'] = $authenticated[0];
    header('location: /admin.php');
}


if (empty($authenticated)) {
    $authenticated = false;
}

echo $twig->render('admin.html', ['l5' => 'selected', 'authenticated' => $_SESSION['rank']]);