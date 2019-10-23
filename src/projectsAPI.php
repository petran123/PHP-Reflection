<?php
$username = 'petran123';
$url = 'https://api.github.com/users/petran123/repos';

$api = new Milo\Github\Api;
$token = new Milo\Github\OAuth\Token(getenv("GITHUB_KEY"));
$api->setToken($token);
$response =  $api->get($url);
$projectList = $api->decode($response);


$args['projects'] =  $projectList;
