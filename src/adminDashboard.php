<?php

if (!empty($_POST)) {
    if (isset($_POST['username'])) {
        $user = Auth::login($database);
    }

    if (isset($_POST['promote'])) {
        $alteredId = filter_input(INPUT_POST, 'promote', FILTER_SANITIZE_STRING);
        if ($auth->promote($alteredId)) {
            header('location:/admin.php?success');
        } else {
            header('location:/admin.php?failed');
        }
    }

    if (isset($_POST['demote'])) {
        $alteredId = filter_input(INPUT_POST, 'demote', FILTER_SANITIZE_STRING);
        if ($auth->demote($alteredId)) {
            header('location:/admin.php?success');
        } else {
            header('location:/admin.php?failed');
        }
    }
}

if ($user->getRank() >= 2) {
    $args['userList'] = $auth->getUsers();
}

if (isset($_GET['success'])) {
    $args['altered'] = 'success';
}

if (isset($_GET['failed'])) {
    $args['altered'] = 'failed';
}
