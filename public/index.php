<?php

require_once __DIR__ . '/../src/bootstrap.php';

echo $twig->render('main.html', ['l1' => 'selected', 'username' => $creds->username, 'rank' => $creds->rank]);