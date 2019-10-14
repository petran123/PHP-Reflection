<?php

function login() 
{
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $authenticated = SQL::logIn($username, $password);
    

    if (!password_verify($password, $authenticated['password'])) {
        $authenticated = [ 'username' => 'wrong',
                'rank' => 'wrong'];
    }
    createCookie($authenticated);
}

function getFunction() 
{
    global $authenticated;
    if (null !== filter_input(INPUT_GET, 'logout', FILTER_SANITIZE_STRING)) {
        // Don't use sessions. there has to be a better way.
        session_destroy();
        session_start();
        $_SESSION['username'] = $authenticated[null];
        $_SESSION['rank'] = $authenticated[0];
        redirect('/admin.php');
    }

}

function request() 
{
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
} 

function redirect($path, $extra = [])
{
    $response = \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND, ['location' => $path]);
    if (key_exists('cookies', $extra)) {
        foreach($extra['cookies'] as $cookie) {
            $response->headers->setCookie($cookie);
        }
    }
}

function createCookie($authenticated)
{
            //add jwt here?
            $expTime = time() + 3600;

            $jwt = \Firebase\JWT\JWT::encode([
                'iss' => request()->getBaseUrl(),
                'sub' => "{$authenticated['username']}",
                'exp' => $expTime,
                'iat' => time(),
                'nbf' => time(),
                'rank' => $authenticated['rank']
            ], getenv("SECRET_KEY"),'HS256');
        
            $accessToken = new \Symfony\Component\HttpFoundation\Cookie('access_token', $jwt, $expTime, '/', "");
            redirect('/admin.php', ['cookies' => [$accessToken]]);
}