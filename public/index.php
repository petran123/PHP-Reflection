<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/blogMethods.php';


$args['l1'] = 'selected';
echo $twig->render('index.html', $args);
