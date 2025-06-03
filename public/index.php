<?php
session_start();
ini_set('error_log', 'error_log.log');

include __DIR__ . '/../vendor/autoload.php';

use App\Routing\Router;
use App\Database\Install;

$router = new Router();
$router->handle();

$install = new Install(["host" => "localhost", "user" => "root", "password" => "", "database" => "mysql"]);
if (!$install->dbExists()){
    $install->create_db();
    $install->fillTables();
}