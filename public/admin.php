<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__. '/../src/login.php';

if (isset($acc)) {
    // var_dump($acc->getUsername());
    echo $twig->render('admin.html', ['l5' => 'selected', 'username' => $acc->getUsername(), 'rank' => $acc->getRank()]);
} else {
    $acc = new AccOps();
    echo $twig->render('admin.html', ['l5' => 'selected', 'username' => $acc->getUsername(), 'rank' => $acc->getRank()]);
}
