<?php

$db = new PDO("mysql:host=localhost;dbname=test",'root');
var_dump($db);
echo "This should appear at the top of the page.";