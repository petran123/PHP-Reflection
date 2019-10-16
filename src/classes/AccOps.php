<?php

class AccOps
{
    private $userId = 0;
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
            $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
            $repeat = filter_input(INPUT_POST, 'repeatpassword', FILTER_SANITIZE_STRING);

            // i know that these two are too short to be secure but this is just a proof of concept.
            if (strlen($name) < 4) {
                echo "username too short";
                return false;
            }

            if (strlen($password) < 4) {
                echo "password too short";
                return false;
            }
            
            $hashedPW = password_hash($password, PASSWORD_DEFAULT);
            $q1 = "SELECT username FROM accounts WHERE username = :name";
            $check = $db->prepare($q1);
            $check->bindParam(":name", $name);
            $check->execute();
            $results = $check->fetch(PDO::FETCH_ASSOC);
            // if name is unavailable, returns false.
            
            if ($results['username']) {
                return false;
            }
            
            
            if (!($password === $repeat)) {
                echo "passwords do not match.";
                
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
        $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

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
        //do not hardcode the path. use a variable
        $this->userId = $results['id'];
        
        $this->rank = $results['rank'];
        if ($location != null) {
            $this->createCookie($location);
            
            // self::createSession($results, $location);
        }
        return true;
    }

    // private function createSession($results, $location)
    // {
    //     $_SESSION['username'] = $results['username'];
    //     $_SESSION['rank'] = $results['rank'];
    //     header("location: $location");
    // }

    public function logout($location) 
    {
        // $_SESSION['username'] = null;
        // $_SESSION['rank'] = null;

        setcookie('access_token', "expired", time(), "/", \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getHost());
        
        header("location: $location");
    }

    public function createCookie($location = null)
    {
                //add jwt here?
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
                ], getenv("SECRET_KEY"),'HS256');
            
                setcookie('access_token', $jwt, time() + 900, "/", \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getHost(), false, true);
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
            
            //this is unfinished and i want to see what it dumps before i finish it.
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
}