<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__. '/../src/create.php';

$args['sitekey'] = getenv('RECAPTCHA_SITEKEY');
$args['formId'] = 'register';
$args['button'] = 'Register';

echo $twig->render('register.html', $args);