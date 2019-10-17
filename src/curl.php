<?php
$username = 'petran123';
$url = 'https://api.github.com/users/petran123/repos';

$api = new Milo\Github\Api;
$token = new Milo\Github\OAuth\Token('f5f410644df987b427283651c0c5124030213830');
$api->setToken($token);
$response =  $api->get($url);
$projectList = $api->decode($response);

//this adds the api results into twig's arguments
$args['projects'] =  $projectList;
