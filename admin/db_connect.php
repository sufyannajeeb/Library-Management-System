<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

// MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check MySQLi connection
if ($conn->connect_error) {
    die("MySQLi Connection failed: " . $conn->connect_error);
}

// PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}

// Optionally return both connections in an array
return [
    'mysqli' => $conn,
    'pdo' => $pdo,
];
?>
