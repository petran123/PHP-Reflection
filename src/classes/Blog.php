<?php

class Blog
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function fetchAllPosts()
    {
        return $this->database->fetchAllPosts();
    }

    public function fetchPostById($id)
    {
        return $this->database->fetchPostById($id);
    }

    public function addEntry($title, $content)
    {
        $title = trim($title);
        $content = trim($content);

        if ($this->verifyValues($title, $content))
            return $this->database->addEntry($title, $content);

        return false;
    }

    public function editEntry($id, $title, $content)
    {
        $title = trim($title);
        $content = trim($content);

        if ($this->verifyValues($title, $content))
            return $this->database->editEntry($id, $title, $content);

        return false;
    }

    public function deleteEntry($id)
    {
        return $this->database->deleteEntry($id);
    }

    private function verifyValues($title, $content)
    {
        if ((strlen($title) < 5) || (strlen($content) < 5))
            return false;

        return true;
    }
}
