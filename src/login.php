<?php

session_start();

// to create an account:
// SQL::createAccount("petran", "1234", "2");
// 2 is the admin rank
if (request()->cookies->has('acccess_token')) {
    die("you have a cookie");
}
if (!empty($_POST)) {
    login();
}
if (!empty($_GET)) {
    getFunction();
}