<?php

require_once __DIR__ . '/../bootstrap.php';

echo $twig->render('about.html', ['l3' => 'selected']);