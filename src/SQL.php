<?php

class SQL 
{
    public static function createAccount($name, $pass)
    {
        try {
            global $db;
            $hashedPW = password_hash($pass, PASSWORD_DEFAULT);
            $q = "INSERT INTO accounts (username, password) VALUES (:name, :password)";
            $create = $db->prepare($q);
            $create->bindValue(":name", $name);
            $create->bindValue(":password", $hashedPW);
            $create->execute();
            echo "success";
        } catch (Exception $e) {
            die($e);
        }
    }

    public static function logIn($name, $pass)
    {
        global $db;
        try {
            $q = "SELECT * FROM accounts WHERE username = :name";
            $login = $db->prepare($q);
            $login->bindValue(":name", $name);
            $login->execute();
            $results = $login->fetch(PDO::FETCH_ASSOC);
            if (password_verify($pass, $results['password'])) {
                //THIS IS WRONG. YEAH YOU'RE LOGGED IN BUT WHO ARE YOU?
                $rank = $results['rank'];
                $arr = [ 'username' => $name,
                        'rank' => $rank];
                return $arr;
            } else {
                $arr = [username => null,
                        rank => 0];
                return false;
            }
        } catch (Exception $e) {
             die("Failed to log in");
        }
    }
}