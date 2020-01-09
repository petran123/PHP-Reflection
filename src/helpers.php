<?php

function request()
{
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}

function registerAutoload()
{
    spl_autoload_register(function ($class) {
        $classPath = str_replace('\\', '/', $class);
        include __DIR__ . '/classes/' . $classPath . '.php';
    });
}

function startSession()
{
    ini_set('session.use_strict_mode', "On");
    ini_set('session.cookie_httponly', "On");
    ini_set('session.cookie_samesite', "Strict");
    session_start();
}

function authenticate($database)
{
    if (request()->cookies->has('access_token')) {
        try {
            $loginCookie = \Firebase\JWT\JWT::decode($_COOKIE["access_token"], getenv('COOKIES_SECRET_KEY'), array('HS256'));
            $details = $loginCookie->data;
            return new User($details->userId, $details->username, $details->rank);
        } catch (Exception $e) {
            //currently it simply refreshes the page if 15 minutes has passed. I could fix this in other ways but i'm okay with that for an admin panel.
            throw $e;

            // TODO is this ever called?
            die('header refresh 0 is being called');
            header("Refresh:0");
        }
    }

    return Auth::login($database);
}
