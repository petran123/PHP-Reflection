<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__. '/../src/login.php';

$args['l5'] = 'selected';
echo $twig->render('admin.html', $args);