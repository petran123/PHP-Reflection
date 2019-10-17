<?php

if (!empty($_POST)) {
    $acc = new AccOps();
    $acc->login('/admin.php');
}