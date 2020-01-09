<?php

class Database
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                getenv("DB_CONNECTION") . ":host=" . getenv("DB_IP") . ";"
                    . "dbname=" . getenv("DB_DATABASE"),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD')
            );

            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die("Failed to connect to the database.");
        }
    }

    public function fetchAllPosts()
    {
        $q = "SELECT * FROM articles ORDER BY date_posted DESC";
        return $this->get($q);
    }

    public function fetchPostById($id)
    {
        $q = "SELECT * FROM articles WHERE id = :id";
        return $this->get($q, null, $id);
    }

    public function editEntry($id, $title, $content)
    {
        $q = "UPDATE articles SET title = :title, content = :content WHERE id = :id";
        return $this->execute($q, ['title' => $title, 'content' => $content], $id);
    }

    public function addEntry($title, $content)
    {
        $q = "INSERT INTO articles (title, content) VALUES (:title, :content)";
        return $this->execute($q, [
            'title' => $title,
            'content' => $content
        ]);
    }

    public function deleteEntry($id)
    {
        $q = "DELETE FROM articles WHERE id = :id";

        return $this->execute($q, null, $id);
    }

    public function login($username)
    {
        $q = "SELECT * FROM accounts WHERE username = :name";

        $result = $this->get($q, ['name' => $username], null, true);

        return $result;
    }

    public function getUsers()
    {
        $q = "SELECT id, username, rank FROM accounts";
        return $this->get($q);
    }

    public function demote($id)
    {
        $q = "UPDATE accounts SET rank = 1 WHERE id = :id";
        return $this->execute($q, null, $id);
    }

    public function promote($id)
    {
        $q = "UPDATE accounts SET rank = 2 WHERE id = :id";
        return $this->execute($q, null, $id);
    }

    private function execute($q, $args = null, $id = null)
    {
        try {
            $stmt = $this->db->prepare($q);

            foreach ((array) $args as $key => &$value) {
                $stmt->bindParam(':' . $key, $value);
            }

            if (isset($id))
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function get(string $q, $args = null, $id = null, $isLogin = false)
    {
        try {
            $stmt = $this->db->prepare($q);

            foreach ((array) $args as $key => $value)
                $stmt->bindParam(":" . $key, $value);

            if (isset($id)) {
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }

            $stmt->execute();

            if ($isLogin)
                return $stmt->fetch(PDO::FETCH_ASSOC);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
