<?php

if (!empty($_POST)) {
    $acc = new AccOps();
    if ($acc->createAccount()){
        header('location /create.php?success');
    } else {
        header('location /create.php?failed');
    }
}