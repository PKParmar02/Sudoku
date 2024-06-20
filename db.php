<?php

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    $host = "localhost";
    $dbname = "Sudoku";
    $user = "root";
    $password = "";
} else {
    $host = "localhost";
    $dbname = "Sudoku";
    $user = "root";
    $password = "";
}

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>