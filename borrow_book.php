<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 'admin') {
    header("Location: index.php");
    exit();
}

$book_id = (int)$_GET['book_id'];
$user_id = $_SESSION['user_id'];

$query = "UPDATE books SET available=0 WHERE id=$book_id AND available=1";
if (mysqli_query($conn, $query) && mysqli_affected_rows($conn) > 0) {
    $borrow_date = date('Y-m-d');
    mysqli_query($conn, "INSERT INTO borrowing (user_id, book_id, borrow_date) 
                         VALUES ($user_id, $book_id, '$borrow_date')");
}
header("Location: dashboard.php");
exit();
?>
