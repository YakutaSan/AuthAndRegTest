<?php

require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'models/Session.php';

$database = new Database('data.json');
$user = new User($database, new Session());

$userData = [
    'login' => $_POST['login'],
    'password' => $_POST['password'],
    'confirm_password' => $_POST['confirm_password'],
    'email' => $_POST['email'],
    'name' => $_POST['name']
];

$errors = $user->register($userData);

// print_r($_SESSION);

if ($errors) {
    echo json_encode(['success' => false, 'errors' => $errors]);
} else {
    echo json_encode(['success' => true]);
}
