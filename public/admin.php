<?php

require_once __DIR__ . '/../bootstrap.php';
// requireAuth(2);
echo $twig->render('admin.html', ['l5' => 'selected']);