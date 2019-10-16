<?php

require_once __DIR__ . '/../src/bootstrap.php';

$args['l3'] = 'selected';
echo $twig->render('about.html', $args);