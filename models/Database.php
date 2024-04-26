<?php

class Database
{

    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function getData()
    {
        $jsonData = file_get_contents($this->filename);
        return json_decode($jsonData, true);
    }

    public function saveData($data)
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->filename, $jsonData);
    }

    public function getUserByLogin($login)
    {
        $data = $this->getData();
        foreach ($data as $user) {
            if ($user['login'] === $login) {
                return $user;
            }
        }
        return null;
    }

    public function getUserByEmail($email)
    {
        $data = $this->getData();
        foreach ($data as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }

    public function addUser($data)
    {
        $oldData = $this->getData();
        $oldData[] = $data;
        $this->saveData($oldData);
    }

    public function updateUser($login, $updateData)
    {
        $data = $this->getData();
        foreach ($data as $key => $user) {
            if ($user['login'] === $login) {
                $data[$key] = array_merge($user, $updateData);
                break;
            }
        }
        $this->saveData($data);
    }
}
