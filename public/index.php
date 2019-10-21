<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/blogMethods.php';

// this page should contain the latest blog post and a link to "all posts"


$args['l1'] = 'selected';
echo $twig->render('main.html', $args);