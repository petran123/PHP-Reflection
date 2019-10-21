<?php

class BlogClass
{
    public function FetchAllPosts() 
    {        
        try {
            global $db;
            $q = "SELECT * FROM articles ORDER BY date_posted DESC";
            $stmt = $db->prepare($q);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function fetchPostById($id)
    {
        try {
            global $db;
            $q = "SELECT * FROM articles WHERE id = :id";
            $stmt = $db->prepare($q);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function addEntry($title, $content)
    {
        try {
            global $db;
            if (empty($title)) {
                return false;
            }
            if (empty($content)) {
                return false;
            }
            $q = "INSERT INTO articles (title, content) VALUES (:title, :content)";
            $stmt = $db->prepare($q);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":content", $content);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}