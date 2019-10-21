<?php

require_once __DIR__ . '/../src/bootstrap.php';
require __DIR__ . '/../src/projectsAPI.php';

$args['l3'] = 'selected';
echo $twig->render('projects.html', $args);

