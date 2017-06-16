<?php

// Datenbankverbindungen
$config = include 'dbconfig.php';

try {
    $dbc = new PDO('mysql:host=' . $config['server'] . ';dbname=' . $config['dbname'],
            $config['dbuser'], $config['dbpassword']);
} catch (PDOException $e) {
    echo $e->getMessage();
}