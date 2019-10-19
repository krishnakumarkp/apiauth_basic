<?php
require 'vendor/autoload.php';

use Src\System\DatabaseConnector;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

// test code, should output:
// api://default
// when you run $ php bootstrap.php
$dbConnection = (new DatabaseConnector())->getConnection();