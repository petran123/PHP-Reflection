<?php

if (!empty($_POST)) {
    if (isset($_POST['username'])) {
    $acc = new AccOps();
    $acc->login('/admin.php');
    }

    if (isset($_POST['promote'])) {
        $alteredId = filter_input(INPUT_POST, 'promote', FILTER_SANITIZE_STRING);
        if ($acc->promote($alteredId)) {
            header('location:/admin.php?success');
        } else {
            header('location:/admin.php?failed');
        }
    }

    if (isset($_POST['demote'])) {
        $alteredId = filter_input(INPUT_POST, 'demote', FILTER_SANITIZE_STRING);
        if ($acc->demote($alteredId)) {
            header('location:/admin.php?success');
        }
        else {
            header('location:/admin.php?failed');
        }
    }
}

if ($acc->getRank() >= 2) {
    $args['userList'] = $acc->getUsers();
}

if (isset($_GET['success'])){
    $args['altered'] = 'success';
}

if (isset($_GET['failed'])){
    $args['altered'] = 'failed';
}