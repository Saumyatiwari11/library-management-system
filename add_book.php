<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $edit_id = isset($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;

    if ($edit_id) {
        $query = "UPDATE books SET title='$title', author='$author', isbn='$isbn' WHERE id=$edit_id";
    } else {
        $query = "INSERT INTO books (title, author, isbn) VALUES ('$title', '$author', '$isbn')";
    }
    mysqli_query($conn, $query);
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM books WHERE id=$delete_id");
    header("Location: dashboard.php");
    exit();
}

$edit_book = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $result = mysqli_query($conn, "SELECT * FROM books WHERE id=$edit_id");
    $edit_book = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add/Edit Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $edit_book ? 'Edit Book' : 'Add New Book'; ?></h1>
        <form method="POST">
            <label>Title:</label>
            <input type="text" name="title" value="<?php echo $edit_book ? $edit_book['title'] : ''; ?>" required><br>
            <label>Author:</label>
            <input type="text" name="author" value="<?php echo $edit_book ? $edit_book['author'] : ''; ?>" required><br>
            <label>ISBN:</label>
            <input type="text" name="isbn" value="<?php echo $edit_book ? $edit_book['isbn'] : ''; ?>" required><br>
            <?php if ($edit_book): ?>
                <input type="hidden" name="edit_id" value="<?php echo $edit_book['id']; ?>">
            <?php endif; ?>
            <button type="submit"><?php echo $edit_book ? 'Update' : 'Add'; ?> Book</button>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
