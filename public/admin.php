<?php

require_once __DIR__ . '/../bootstrap.php';

echo $twig->render('admin.html', ['l5' => 'selected', 'authenticated' => $authenticated]);