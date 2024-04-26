<?php

require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'models/Session.php';

$database = new Database('data.json');
$user = new User($database, new Session());

echo json_encode(['name' => $_SESSION['user']['name']]);