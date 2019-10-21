<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/contactMethods.php';

$args['l5'] = 'selected';
$args['sitekey'] = getenv('RECAPTCHA_SITEKEY');
$args['formId'] = 'contact';
$args['button'] = 'Send';

echo $twig->render('contact.html', $args);