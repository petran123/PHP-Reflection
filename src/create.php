<?php

if (!empty($_POST)) {
    if (SQL::createAccount()){
        header('location /create.php?success');
    } else {
        header('location /create.php?failed');
    }
}