<?php
include __DIR__ . '/../autoload.php';
include __DIR__ . '/../database.php';



$route = rtrim(ltrim(strtok($_SERVER['REQUEST_URI'], '?'), '/'), '/');
$method = $_SERVER['REQUEST_METHOD'];

$entryPoint = new \CSY2028\EntryPoint($pdo, $route, $method, new \josJobs\Routes($pdo));
$entryPoint->run();
