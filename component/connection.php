<?php

function connectToDatabase() {
    $host = 'localhost';
    $port = '3306';
    $dbname = 'qrattend';
    $user = 'root';
    $password = '';

    try {
        // Create string DSN (Data Source Name)
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";

        // Create PDO object
        $pdo = new PDO($dsn, $user, $password);

        // Set PDO attributes for error mode
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        // Handle connection errors
        echo "A connection error occurred: " . $e->getMessage();
        return null;
    }
}