<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__. '/../src/adminDashboard.php';

$args['l6'] = 'selected';
echo $twig->render('admin.html', $args);