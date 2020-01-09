<?php
// TODO this should be a service that logs you in through methods int he database serviceand return the user model. nothing more
class Auth
{
    private $database;
    private $user;


    public function __construct($database, $user)
    {
        // TODO see if I can skip this step

        $this->database = $database;
        $this->user = $user;
    }

    private function verifyCaptcha()
    {
        // $postData = ['secret' => getenv("RECAPTCHA_SECRET_KEY"), 
        //     'response' => $_POST['g-recaptcha-response']];
        // $recaptcha = http_build_query($postData);

        // return json_decode($this->curlCaptcha($recaptcha));
        die("go back and test verify");
    }

    private function curlCaptcha($recaptcha)
    {
        // $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, 
        //         'https://www.google.com/recaptcha/api/siteverify');
        //     curl_setopt($ch, CURLOPT_POST, 1);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $recaptcha);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //     $server_output = curl_exec($ch);
        //     curl_close($ch);

        //     return $server_output;
        die("go back and test curl");
    }

    public function createAccount()
    {
        try {
            $name = filter_input(INPUT_POST, 'regUsername', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'regPassword', FILTER_SANITIZE_STRING);
            $repeat = filter_input(INPUT_POST, 'regRepeat', FILTER_SANITIZE_STRING);


            $decoded = $this->verifyCaptcha();

            if ($decoded->success === false) {
                header('location: register.php?error=captcha');
                return false;
            }


            if (strlen($name) < 4) {
                header('location: register.php?error=usnlen');
                return false;
            }

            if (strlen($password) < 4) {
                header('location: register.php?error=pwdlen');
                return false;
            }

            $hashedPW = password_hash($password, PASSWORD_DEFAULT);
            $q1 = "SELECT username FROM accounts WHERE username = :name";
            $check = $this->database->db->prepare($q1);
            $check->bindParam(":name", $name);
            $check->execute();
            $results = $check->fetch(PDO::FETCH_ASSOC);

            if ($results['username']) {
                header('location: register.php?error=usntaken');
                return false;
            }

            if (($password !== $repeat)) {
                header('location: register.php?error=rep');
                return false;
            }
            $q2 = "INSERT INTO accounts (username, password, rank) VALUES (:name, :password, :rank)";
            $create = $this->database->db->prepare($q2);
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

    public static function login($location)
    {
        // TODO what is this
        if (!empty(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING)))
            $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        else
            $name = filter_input(INPUT_POST, 'regUsername', FILTER_SANITIZE_STRING);


        if (!empty(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING)))
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        else
            $password = filter_input(INPUT_POST, 'regPassword', FILTER_SANITIZE_STRING);


        $results = $this->database->login($name);


        if (password_verify($password, $results['password'])) {
            if ($location != null)
                $this->createCookie($location);

            return new User($results['id'], $results['username'], $results['rank']);
        }
        return new User(-1, 'Guest', -1);
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
                'userId' => $this->userId,
                'username' => $this->username
            ]
        ], getenv("COOKIES_SECRET_KEY"), 'HS256');

        setcookie('access_token', $jwt, $expTime, "/", \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getHost(), false, true);
        if (!empty($location)) {
            header("location: $location");
        }
    }

    // TODO saving this while i'm making it obsolete
    // public function getUsername()
    // {
    //     if ($this->userId < 1)
    //         return false;
    //     try {
    //         $results = $this->database->getUsername();

    //         // $q = "SELECT username FROM accounts WHERE id = :id";
    //         // $stmt = $this->database->db->prepare($q);
    //         // $stmt->bindValue(':id', $this->userId);
    //         // $stmt->execute();
    //         // $results = $stmt->fetch(PDO::FETCH_ASSOC);

    //         if ($results['username'] === null) {
    //             return false;
    //         }
    //         return $results['username'];
    //     } catch (Exception $e) {
    //         die("getUsername failed");
    //     }
    // }

    public function getUsers()
    {
        
        if ($this->isAdmin())
            return $this->database->getUsers();
        
            return false;
    }

    public function promote($id)
    {
        if ($this->isAdmin())
            return $this->database->promote($id);

        return false;
    }

    public function demote($id)
    {
        if ($this->isAdmin())
            return $this->database->demote($id);

        return false;
    }

    private function isAdmin()
    {
        if ($this->user->getRank() < 3)
            return false;

        return true;
    }
}
