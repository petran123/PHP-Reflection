<?php
$username = 'petran123';
$url = 'https://api.github.com/users/petran123/repos';

$api = new Milo\Github\Api;
$response =  $api->get($url);
$user = $api->decode($response);
var_dump($user);