<?php

require_once __DIR__ . '/../src/bootstrap.php';

$args['l4'] = 'selected';
echo $twig->render('contact.html', $args);
