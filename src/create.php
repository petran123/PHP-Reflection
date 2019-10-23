<?php

if (!empty($_POST)) {
    $acc = new AccOps();
    $acc->createAccount();
    // headers and errors are handled by the method
}

if (!empty($_GET)) {
    switch (filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING)) {
        case "usnlen":
            $args['regErr'] = "Username is too short.";
            break;
        case "pwdlen":
            $args['regErr'] = "Password is too short.";
            break;
        case "rep":
            $args['regErr'] = "Passwords do not match.";
            break;
        case "captcha":
            $args['regErr'] = "Please complete the captcha";
            break;
        case "usntaken":
            $args['regErr'] = "Username Already Exists";
            break;
        default:
            $args['regErr'] = "An error has occurred. Please try again.";
            break;
    }
}
