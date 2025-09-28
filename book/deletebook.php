<?php
require_once '../classes/books.php';

$bookObj = new Books();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['title'])) {
        $title = trim($_GET['title']);
        $result = $bookObj->deleteBook($title);

        if ($result) {
            header("Location: viewbook.php?msg=deleted");
            exit();
        } else {
            exit("Failed to delete book. <a href='viewbook.php'>Back to list</a>");
        }
    } else {
        exit("Book not found. <a href='viewbook.php'>Back to list</a>");
    }
}
?>
