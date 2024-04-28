<?php

class User
{

    private $database;
    private $session;

    public function __construct(Database $database, Session $session)
    {
        $this->database = $database;
        $this->session = $session;
    }

    public function register($userData)
    {
        $errors = [];

        if ($_SESSION) {
            $errors['auth'] = 'You are logged in';
        }

        if (!empty($errors)) {
            return $errors;
        }

        // Валидация полей
        if (strlen($userData['login']) < 6) {
            $errors['login'] = 'Login must be at least 6 characters long';
        }

        if (strlen($userData['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters long';
        }

        if ($userData['password'] !== $userData['confirm_password']) {
            $errors['confirm_password'] = 'Passwords must match';
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email';
        }

        if (strlen($userData['name']) < 2) {
            $errors['name'] = 'Name must be at least 2 characters long';
        }

        // Проверка на уникальность
        if ($this->database->getUserByLogin($userData['login'])) {
            $errors['login'] = 'Login already exists';
        }

        if ($this->database->getUserByEmail($userData['email'])) {
            $errors['email'] = 'Email already exists';
        }

        if (!empty($errors)) {
            return $errors;
        }

        $userData['salt'] = $this->generateSalt();

        // Защита пароля
        $userData['password'] = md5($userData['password'] . $userData['salt']);
        $userData['confirm_password'] = md5($userData['confirm_password'] . $userData['salt']);

        // Добавление в базу
        $this->database->addUser($userData);

        return $errors;
    }

    public function login($login, $password)
    {
        $user = $this->database->getUserByLogin($login);

        if ($_SESSION) {
            return ['auth' => 'You are logged in'];
        }

        if (!$user) {
            return ['login' => 'User not found'];
        }

        if ($user['password'] !== md5($password . $user['salt'])) {
            return ['password' => 'Incorrect password'];
        }

        // Установка кук и сессии
        setcookie('user_login', $user['login'], time() + 3600);
        $this->session->set('user', $user);

        return true;
    }

    public function logout()
    {
        setcookie('user_login', '', time() - 3600);
        $this->session->destroy();
    }

    public function isLoggedIn()
    {
        return $this->session->get('user') !== null;
    }

    public function getUserData($login)
    {
        $data = $this->database->getData();
        foreach ($data as $user) {
            if ($user['login'] === $login) {
                return $user;
            }
        }
        return null;
    }

    public function updateUser($id, $updateData)
    {
        $this->database->updateUser($id, $updateData);
    }

    private function generateSalt()
    {
        return substr(md5(microtime()), 0, 10);
    }
}
