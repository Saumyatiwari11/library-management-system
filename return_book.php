<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'admin') {
    header("Location: index.php");
    exit();
}

$book_id = (int)$_GET['book_id'];
$user_id = $_SESSION['user_id'];

$return_date = date('Y-m-d');
mysqli_query($conn, "UPDATE borrowing SET return_date='$return_date' 
                     WHERE user_id=$user_id AND book_id=$book_id AND return_date IS NULL");
mysqli_query($conn, "UPDATE books SET available=1 WHERE id=$book_id");

header("Location: dashboard.php");
exit();
?>
