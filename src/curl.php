<?php
$username = 'petran123';
$url = 'https://api.github.com/users/petran123/repos';

$api = new Milo\Github\Api;
$token = new Milo\Github\OAuth\Token('4a36d40fef945c186be4e0238e23907f64af562a');
$api->setToken($token);
$response =  $api->get($url);
$projectList = $api->decode($response);

//this adds the api results into twig's arguments
$args['projects'] =  $projectList;
