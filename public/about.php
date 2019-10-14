<?php

require_once __DIR__ . '/../src/bootstrap.php';

echo $twig->render('about.html', ['l3' => 'selected', 'username' => $creds->username, 'rank' => $creds->rank]);