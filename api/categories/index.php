<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        require 'read_single.php';
    } else {
        require 'read.php';
    }
}

if ($method === 'POST') {
    require 'create.php';
}

if ($method === 'PUT') {
    require 'update.php';
}

if ($method === 'DELETE') {
    require 'delete.php';
}