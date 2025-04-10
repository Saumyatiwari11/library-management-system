<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Fetch books
$books_query = "SELECT * FROM books";
$books_result = mysqli_query($conn, $books_query);

// Fetch borrowed books for the user
$borrowed_query = "SELECT b.title, br.borrow_date, br.return_date 
                   FROM borrowing br 
                   JOIN books b ON br.book_id = b.id 
                   WHERE br.user_id = $user_id AND br.return_date IS NULL";
$borrowed_result = mysqli_query($conn, $borrowed_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Library Management System</h1>
        <p>Welcome, <?php echo ($role == 'admin') ? 'Admin' : 'User'; ?>! <a href="logout.php">Logout</a></p>

        <?php if ($role == 'admin'): ?>
            <h2>Add New Book</h2>
            <a href="add_book.php">Add Book</a>
        <?php endif; ?>

        <h2>Available Books</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Action</th>
            </tr>
            <?php while ($book = mysqli_fetch_assoc($books_result)): ?>
                <tr>
                    <td><?php echo $book['title']; ?></td>
                    <td><?php echo $book['author']; ?></td>
                    <td><?php echo $book['isbn']; ?></td>
                    <td>
                        <?php if ($book['available'] && $role != 'admin'): ?>
                            <a href="borrow_book.php?book_id=<?php echo $book['id']; ?>">Borrow</a>
                        <?php elseif ($role == 'admin'): ?>
                            <a href="add_book.php?edit_id=<?php echo $book['id']; ?>">Edit</a> |
                            <a href="add_book.php?delete_id=<?php echo $book['id']; ?>" 
                               onclick="return confirm('Are you sure?');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <?php if ($role != 'admin'): ?>
            <h2>Your Borrowed Books</h2>
            <table>
                <tr>
                    <th>Title</th>
                    <th>Borrow Date</th>
                    <th>Action</th>
                </tr>
                <?php while ($borrowed = mysqli_fetch_assoc($borrowed_result)): ?>
                    <tr>
                        <td><?php echo $borrowed['title']; ?></td>
                        <td><?php echo $borrowed['borrow_date']; ?></td>
                        <td><a href="return_book.php?book_id=<?php echo $borrowed['book_id']; ?>">Return</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
