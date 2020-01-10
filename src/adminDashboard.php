<?php

if (!empty($_POST)) {
    if (isset($_POST['username'])) {
        $user = $auth->loginFromForm($refresh);
    }

    if (isset($_POST['promote'])) {
        $alteredId = filter_input(INPUT_POST, 'promote', FILTER_SANITIZE_STRING);
        $auth->promote($alteredId);
    }

    if (isset($_POST['demote'])) {
        $alteredId = filter_input(INPUT_POST, 'demote', FILTER_SANITIZE_STRING);
        $auth->demote($alteredId);
    }
}

if ($user->getRank() > 1) {
    $args['userList'] = $auth->getUsers();
}