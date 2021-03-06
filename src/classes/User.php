<?php

class User 
{
    private $userId;
    private $username;
    //rank of 1 for normal users, 2 for promoted users (can post and edit articles),
    //and 3 for the admin (can promote/demote and delete articles)
    private $rank;
    

    public function __construct(int $userId = -1, string $username = 'Guest', int $rank = -1)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->rank = $rank;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function getUserId()
    {
        return $this->userId;
    }

}