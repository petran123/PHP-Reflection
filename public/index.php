<?php

require_once __DIR__ . '/../bootstrap.php';

echo $twig->render('main.html', ['l1' => 'selected']);