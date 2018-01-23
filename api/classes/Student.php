<?php

class Student
{
    private $username;
    private $homeUrl;
    private $index;

    public function __construct($username)
    {
        if (empty(trim($username))) {
            throw new Exception('No username passed as constructor parameter');
        }

        $this->username = strtolower(trim($username));
        $this->homeUrl = 'https://fc.lnu.se/~' . $this->username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getHomeUrl()
    {
        return $this->homeUrl;
    }

    public function hasIndex()
    {
        return $this->index;
    }

    public function setHasIndex($index)
    {
        $this->index = $index;
    }
}
