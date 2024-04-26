<?php

require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'models/Session.php';

$database = new Database('data.json');
$user = new User($database, new Session());


if ($user->isLoggedIn()) {
    require_once 'views/home.html';
} else {
    require_once 'views/hello.html';
}
