<?php


// to create an account:
// AccOps::createAccount("petran", "1234", "2");
// 2 is the admin rank
// if (request()->cookies->has('acccess_token')) {
//     die("this doesn't work in localhost");
// }

// session_start();


if (!empty($_POST)) {
    $acc = new AccOps();
    $acc->login('/admin.php');
}
if (!empty($_GET)) {
    $acc->logout('/admin.php');
}