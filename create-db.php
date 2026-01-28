<?php
$conn = @new mysqli('127.0.0.1', 'root', '', '');
if (!$conn->connect_error) {
    $conn->query('CREATE DATABASE IF NOT EXISTS 360winestate');
    echo "Database created successfully\n";
} else {
    echo "Could not connect to MySQL: " . $conn->connect_error . "\n";
}
