<?php

require_once __DIR__ . '/../src/bootstrap.php';

$args['l1'] = 'selected';
echo $twig->render('main.html', $args);
