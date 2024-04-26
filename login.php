<?php

require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'models/Session.php';

$database = new Database('data.json');
$user = new User($database, new Session());

$login = $_POST['login'];
$password = $_POST['password'];

$loginResult = $user->login($login, $password);

if ($loginResult === true) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'errors' => $loginResult]);
}
