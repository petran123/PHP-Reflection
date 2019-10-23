<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/blogMethods.php';


$args['l2'] = "selected";
echo $twig->render('blog.html', $args);
