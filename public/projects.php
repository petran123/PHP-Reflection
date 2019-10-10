<?php

require_once __DIR__ . '/../bootstrap.php';

echo $twig->render('projects.html', ['l2' => 'selected']);