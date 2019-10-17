<?php

require_once __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/curl.php';

$args['l2'] = 'selected';
echo $twig->render('projects.html', $args);

