<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__. '/../src/create.php';

echo $twig->render('register.html', ['form' => 'register']);