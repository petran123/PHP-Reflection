<?php

class SQL 
{
    public static function createAccount($name, $pass, $rank = 1)
    {
        try {
            global $db;
            $hashedPW = password_hash($pass, PASSWORD_DEFAULT);
            $q = "INSERT INTO accounts (username, password, rank) VALUES (:name, :password, :rank)";
            $create = $db->prepare($q);
            $create->bindValue(":name", $name);
            $create->bindValue(":password", $hashedPW);
            $create->bindValue(":rank", $rank);
            $create->execute();
        } catch (Exception $e) {
            die("Account Creation Failed");
        }
    }

    public static function logIn($name, $pass)
    {
        //handles the SQL part of the login process and returns it to the main login function
        global $db;
        try {
            $q = "SELECT * FROM accounts WHERE username = :name";
            $login = $db->prepare($q);
            $login->bindValue(":name", $name);
            $login->execute();
            return $login->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
             die("Failed to log in");
        }
    }
}