<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/articleMethods.php';


//this l2 is intentional
$args['l2'] = "selected";
echo $twig->render('article.html', $args);