<?php
$servername = "localhost";
$username = "rjprhiwg_admin";
$password = "ici123!@#";
$database = 'rjprhiwg_icientrancedb';

// $password = "admin123!@#";
//admin123!@#

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    die();
}
