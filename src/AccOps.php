<?php

class AccOps
{
    public static function createAccount()
    {
        try {
            global $db;
            $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $repeat = filter_input(INPUT_POST, 'repeatpassword', FILTER_SANITIZE_STRING);
            if (self::checkUnavailableAccName($name)) {
                echo "username unavailable.";
                return false;
            }
            // i know that these two are too short to be secure but this is just a proof of concept.
            if (strlen($name) < 4) {
                echo "username too short";
                return false;
            }
            if (strlen($pass) < 4) {
                echo "password too short";
                return false;
            }


            if (!($pass === $repeat)) {
                echo "passwords do not match.";
                return false;
            }
            $hashedPW = password_hash($pass, PASSWORD_DEFAULT);
            $q = "INSERT INTO accounts (username, password, rank) VALUES (:name, :password, :rank)";
            $create = $db->prepare($q);
            $create->bindValue(":name", $name);
            $create->bindValue(":password", $hashedPW);
            $create->bindValue(":rank", 2);
            $create->execute();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function login($location)
    {
        $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        try {
            global $db;
            $q = "SELECT * FROM accounts WHERE username = :name";
            $login = $db->prepare($q);
            $login->bindValue(":name", $name);
            $login->execute();
            $results = $login->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
             die("Failed to log in");
        }
        if (!password_verify($pass, $results['password'])) {
            $results = [ 'username' => 'wrong',
                    'rank' => 'wrong'];
        }
        //do not hardcode the path. use a variable
        self::createCookie($results, $location);
        // self::createSession($results, $location);

    }

    private static function checkUnavailableAccName($name)
    {
        global $db;

        if (strlen($name) < 4) {
            return false;
        }
        $q = "SELECT username FROM accounts WHERE username = :name";
        $check = $db->prepare($q);
        $check->bindParam(":name", $name);
        $check->execute();
        $results = $check->fetch(PDO::FETCH_ASSOC);
        // if name exists, returns false.
        if (empty($results['username'])) {
            return false;
        }
        return true;
    }

    private function createSession($results, $location)
    {
        $_SESSION['username'] = $results['username'];
        $_SESSION['rank'] = $results['rank'];
        header("location: $location");
    }

    public static function Logout($location) 
    {
        $_SESSION['username'] = null;
        $_SESSION['rank'] = null;

        setcookie('access_token', "expired", time(), "/", self::request()->getHost());
        
        header("location: $location");
    }

    public static function createCookie($authenticated, $location)
    {
                //add jwt here?
                $expTime = time() + 3600;
                $jwt = \Firebase\JWT\JWT::encode([
                    'iss' => self::request()->getHost(),
                    'exp' => $expTime,
                    'iat' => time(),
                    'nbf' => time(),
                    'data' => [
                        'rank' => $authenticated['rank'],
                        'username' => $authenticated['username']
                    ]
                ], getenv("SECRET_KEY"),'HS256');
            
                setcookie('access_token', $jwt, time() + (86400 * 30), "/", self::request()->getHost());
                header("location: $location");
    }

    public static function request() 
    {
        return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    } 


}