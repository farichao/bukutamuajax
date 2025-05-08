<?php
$servername = "localhost: 3307";
$username = "root";
$password = "";
$dbname = "bukutamu";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
