<?php

require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__. '/../src/login.php';


echo $twig->render('admin.html', ['l5' => 'selected', 'username' => $creds->username, 'rank' => $creds->rank]);

//, 'rank' => $_SESSION['rank']
//, 'rank' => $creds->rank