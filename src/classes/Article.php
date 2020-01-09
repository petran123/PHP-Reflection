<?php

class Article 
{
    public $title;
    public $body;
    
    public function __construct(string $title, string $body)
    {
        $this->title = $title;
        $this->body = $body;
    }
}