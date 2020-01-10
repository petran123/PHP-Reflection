<?php


/** This class will be responsible for user authentication validation */
class Auth
{

    private $database;
    private $user;

    public function __construct($database)
    {
        $this->database = $database;
        $this->user = new User();
    }

    public function register()
    {
        //check fields
        $username = filter_input(INPUT_POST, 'regUsername', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'regPassword', FILTER_SANITIZE_STRING);
        $repeat = filter_input(INPUT_POST, 'regRepeat', FILTER_SANITIZE_STRING);

        if (!($username && $password && $repeat))
            return $this->refresh('bad credentials');

        if ($password != $repeat)
            return $this->refresh('passwords do not match');

        if (!$this->verifyCaptcha())
            return $this->refresh('could not verify captcha');

        if ($this->database->getUserByName($username))
            return $this->refresh('username already exists');

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $rank = 1;

        if ($this->database->createuser($username, $hashedPassword, $rank))
            return $this->doLogin($username, $password);

        return $this->refresh('could not create account');
    }

    public function loginFromForm()
    {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);


        if (!($username && $password))
            return $this->refresh('bad credentials');

        return $this->doLogin($username, $password);
    }

    public function logout()
    {
        setcookie('access_token', "expired", time(), "/", \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getHost());

        $this->refresh();
    }

    public function authenticateFromCookie($data)
    {

        if ($this->database->authenticateUserFromCookie($data))
            $this->user = new User($data->userId, $data->username, $data->rank);


        return $this->getUser();
    }

    public function getUser()
    {
        return $this->user;
    }

    private function verifyCaptcha()
    {
        $postData = [
            'secret' => getenv("RECAPTCHA_SECRET_KEY"),
            'response' => $_POST['g-recaptcha-response']
        ];
        $recaptcha = http_build_query($postData);

        $decoded = json_decode($this->sendCaptcha($recaptcha));

        return $decoded->success;
    }

    private function sendCaptcha($recaptcha)
    {
        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            'https://www.google.com/recaptcha/api/siteverify'
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $recaptcha);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        curl_close($ch);

        return $server_output;
    }

    private function doLogin($username, $password)
    {
        // doLogin is meant to be called from forms only. cookie authentication is done by authenticateFromCookie

        $result = $this->database->getUserByName($username);

        if (
            $result &&
            password_verify($password, $result['password'])
        ) {
            $this->user = new User($result['id'], $result['username'], $result['rank']);
            $this->createCookie();
        }

        $this->refresh();
    }

    private function refresh(string $error = "")
    {
        //TODO implement these errors
        if ($error) {
            $_SESSION['error'] = $error;
            return header("Refresh:0");
            die();
        }

        return header("Location:" . strtok($_SERVER['REQUEST_URI'], '?'));
        die();
    }

    private function createCookie()
    {

        $expTime = time() + 3600;
        $jwt = \Firebase\JWT\JWT::encode([
            'iss' => \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getHost(),
            'exp' => $expTime,
            'iat' => time(),
            'nbf' => time(),
            'data' => [
                'rank' => $this->user->getRank(),
                'userId' => $this->user->getUserId(),
                'username' => $this->user->getUserName()
            ]
        ], getenv("COOKIES_SECRET_KEY"), 'HS256');

        setcookie('access_token', $jwt, $expTime, "/", \Symfony\Component\HttpFoundation\Request::createFromGlobals()->getHost(), false, true);
    }

    public function getUsers()
    {
        if ($this->isMod())
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
        return ($this->user->getRank() == 3);
    }

    private function isMod()
    {
        return ($this->user->getRank() > 1);
    }
}
