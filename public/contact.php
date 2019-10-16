<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/contactMethods.php';

$args['l4'] = 'selected';
echo $twig->render('contact.html', $args);
