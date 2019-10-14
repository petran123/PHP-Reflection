<?php

require_once __DIR__ . '/../src/bootstrap.php';

echo $twig->render('contact.html', ['l4' => 'selected']);