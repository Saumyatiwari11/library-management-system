<?php
$host = "localhost";
$db_user = "root"; // Default XAMPP/WAMP user
$db_pass = "";     // Default password (empty)
$db_name = "library_db";

$conn = mysqli_connect($host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
