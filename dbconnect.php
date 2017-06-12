<?php
// Datenbankverbindungen

// Datenbank Host (Standard: localhost)
$server = "localhost";

// Datenbank-Benutzer (Standard: root)
$dbuser = "root";

// Datenbank-Benutzer-Passwort (Standard: "")
$dbpassword = "";

// Name der Datenbank (Standard: boosterdraft)
$dbname = "boosterdraft";

try {
    $dbc = new PDO("mysql:host=$server;dbname=$dbname", $dbuser, $dbpassword);
    /*** echo a message saying we have connected ***/
    //echo 'Connected to database';
}
catch(PDOException $e) {
    echo $e->getMessage();
}