<?php

require_once __DIR__ . '/../src/bootstrap.php';

if (isset($acc)) {
    // var_dump($acc->getUsername());
    echo $twig->render('about.html', ['l3' => 'selected', 'username' => $acc->getUsername(), 'rank' => $acc->getRank()]);
} else {
    $acc = new AccOps();
    echo $twig->render('about.html', ['l3' => 'selected', 'username' => $acc->getUsername(), 'rank' => $acc->getRank()]);
}