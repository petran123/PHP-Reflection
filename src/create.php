<?php

if ($user->getUserId() > 0)
    header('location:/admin.php' );

if (!empty($_POST)) 
    $auth->register();
