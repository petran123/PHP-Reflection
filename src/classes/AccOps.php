<?php

class AccOps
{
    
    private $userId = 0;
    //rank of 1 for normal users, 2 for promoted users (can post and edit articles),
    //and 3 for the admin (can promote/demote and delete articles)
    private $rank = 0;


    public function __construct($username = -1, $rank = -1)
    {
        $this->userId = $username;
        $this->rank = $rank;
    }

    public function createAccount()
    {
        try {
            global $db;
            $name = filter_input(INPUT_POST, 'regUsername', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'regPassword', FILTER_SANITIZE_STRING);
            $repeat = filter_input(INPUT_POST, 'regRepeat', FILTER_SANITIZE_STRING);

            

            //if captcha is wrong, return error
            //I'd like to place this in its own method at some point
            $postData = ['secret' => getenv("RECAPTCHA_SECRET_KEY"), 'response' => $_POST['g-recaptcha-response']];
            $recaptcha = http_build_query($postData);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $recaptcha);
          
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          
            $server_output = curl_exec($ch);
          
            curl_close($ch);
        
            $decoded = json_decode($server_output);

            if ($decoded->success === false) {
                header('location: register.php?error=captcha');
                return false;
            }

            //end of captcha

            if (strlen($name) < 4) {
                header('location: register.php?error=usnlen');
                return false;
            }

            // i know that the password can be too short to be secure.
            if (strlen($password) < 4) {
                header('location: register.php?error=pwdlen');
                return false;
            }
            
            $hashedPW = password_hash($password, PASSWORD_DEFAULT);
            $q1 = "SELECT username FROM accounts WHERE username = :name";
            $check = $db->prepare($q1);
            $check->bindParam(":name", $name);
            $check->execute();
            $results = $check->fetch(PDO::FETCH_ASSOC);

            // if name is unavailable, method ends and returns false.
            if ($results['username']) {
                header('location: register.php?error=usntaken');
                return false;
            }
            
            if (($password !== $repeat)) {
                header('location: register.php?error=rep');
                return false;
            }
            $q2 = "INSERT INTO accounts (username, password, rank) VALUES (:name, :password, :rank)";
            $create = $db->prepare($q2);
            $create->bindValue(":name", $name);
            $create->bindValue(":password", $hashedPW);
            $create->bindValue(":rank", 2);
            $create->execute();
            
            //you don't need to pass any details because the post is still available until you redirect.
            $this->logIn('/admin.php');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function login($location)
    {
        //there should be a better way to write this
        if (!empty(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING))) {
            $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        } else {
            $name = filter_input(INPUT_POST, 'regUsername', FILTER_SANITIZE_STRING);
        }
                
        if (!empty(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING))) {
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        } else {
            $password = filter_input(INPUT_POST, 'regPassword', FILTER_SANITIZE_STRING);
        }

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
        if (!password_verify($password, $results['password'])) {
            $results = [ 'id' => 'wrong',
                    'rank' => 'wrong'];
        }
        
        $this->userId = $results['id'];
        
        $this->rank = $results['rank'];
        if ($location != null) {
            $this->createCookie($location);
        }
        return true;
    }

    
    // Not sure if sessions are secure enough so i haven't implemented them here.

    // private function createSession($results, $location)
    // {
    //     $_SESSION['username'] = $results['username'];
    //     $_SESSION['rank'] = $results['rank'];
    //     header("location: $location");
    // }

    public function logout($location = null)
    {
        // $_SESSION['username'] = null;
        // $_SESSION['rank'] = null;

        setcookie('access_token', "expired", time(), "/", \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getHost());
        
        header("location: $location");
    }

    public function createCookie($location = null)
    {

                $expTime = time() + 3600;
                $jwt = \Firebase\JWT\JWT::encode([
                    'iss' => \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getHost(),
                    'exp' => $expTime,
                    'iat' => time(),
                    'nbf' => time(),
                    'data' => [
                        'rank' => $this->rank,
                        'userId' => $this->userId
                    ]
                ], getenv("COOKIES_SECRET_KEY"), 'HS256');
            
                setcookie('access_token', $jwt, $expTime, "/", \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getHost(), false, true);
        if (!empty($location)) {
            header("location: $location");
        }
    }

    public function getUsername()
    {
        try {
            global $db;
            $q = "SELECT username FROM accounts WHERE id = :id";
            $stmt = $db->prepare($q);
            $stmt->bindValue(':id', $this->userId);
            $stmt->execute();
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($results['username'] === null) {
                return false;
            }
            return $results['username'];
        } catch (Exception $e) {
            die("getUsername failed");
        }
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function getUsers()
    {
        try {
            global $db;
            $q = "SELECT id, username, rank FROM accounts";
            $stmt = $db->prepare($q);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function promote($id)
    {
        if ($this->getRank() < 3) {
            return false;
        }
        try {
            global $db;
            $q = "UPDATE accounts SET rank = 2 WHERE id = :id";
            $stmt = $db->prepare($q);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function demote($id)
    {
        /*
        * Demotes the user specified
        */

        if ($this->getRank() < 3) {
            return false;
        }
        try {
            global $db;
            $q = "UPDATE accounts SET rank = 1 WHERE id = :id";
            $stmt = $db->prepare($q);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
