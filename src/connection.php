<?php

try {
    $db = new PDO("mysql:host=localhost;dbname=test",'root');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Failed to connect to database.");
}