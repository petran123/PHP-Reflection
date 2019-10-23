<?php

class BlogClass
{
    public function fetchAllPosts()
    {
        try {
            global $db;
            $q = "SELECT * FROM articles ORDER BY date_posted DESC";
            $stmt = $db->prepare($q);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
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

    public function editEntry($id, $title, $content)
    {
        // I'm using a javascript alert to confirm before calling this
        try {
            global $db;
            $q = "UPDATE articles SET title = :title, content = :content WHERE id = :id";
            $stmt = $db->prepare($q);
            $stmt->bindParam(":title", $title);
            $stmt->bindParam(":content", $content);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete($id)
    {
        // I'm using a javascript alert to confirm before calling this
        try {
            global $db;
            $q = "DELETE FROM articles WHERE id = :id";
            $stmt = $db->prepare($q);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
