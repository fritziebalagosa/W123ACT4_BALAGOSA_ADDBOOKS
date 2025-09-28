<?php
require_once '../classes/database.php';
require_once '../classes/books.php';

$booktObj = new Books();
$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $books = $booktObj->searchBooks($search);
} else {
    $books = $booktObj->getAllBooks();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            padding: 40px 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .header p {
            color: #666;
            font-size: 1.1rem;
        }

        .books-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            padding: 24px;
            border-bottom: 1px solid #eee;
        }

        .card-header h2 {
            font-size: 1.5rem;
            color: #333;
            font-weight: 600;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 16px 24px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #555;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            color: #333;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .no-books {
            padding: 60px 24px;
            text-align: center;
            color: #666;
            font-size: 1.1rem;
        }

        .actions {
            padding: 24px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn:hover {
            background-color: #1d4ed8;
        }

        .btn-secondary {
            background-color: #6b7280;
            margin-left: 12px;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .search-bar {
            max-width: 600px;
            margin: 2rem auto;
            padding: 1rem;
        }

        .search-bar form {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-bar input[type="text"] {
            flex: 1;
            min-width: 250px;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            background-color: #ffffff;
        }

        .search-bar input[type="text"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-bar input[type="text"]::placeholder {
            color: #94a3b8;
        }

        /* Fixed button sizing to ensure consistent dimensions */
        .search-bar .btn {
            min-width: 100px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .search-bar .btn-secondary {
            min-width: 100px;
            height: 48px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Book Library</h1>
            <p>Manage your book collection</p>
        </div>

        <div class="search-bar">
            <form method="get" action="viewbook.php">
                <input type="text" name="search" placeholder="Search books..." 
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn">Search</button>
                <a href="viewbook.php" class="btn btn-secondary">Reset</a>
            </form>
        </div>

        <div class="books-card">
            <div class="card-header">
                <h2>Book Collection</h2>
            </div>

            <?php if (!empty($books)): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Publication Year</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td><?php echo htmlspecialchars($book['genre']); ?></td>
                                    <td><?php echo htmlspecialchars($book['publication_year']); ?></td>
                                    <td>
                                        <a href="editbook.php?title=<?php echo urlencode($book['title']); ?>" class="btn btn-secondary">Edit</a>
                                        <a href="deletebook.php?title=<?php echo urlencode($book['title']); ?>" class="btn btn-secondary"
                                        onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($book['title']); ?>?');">
                                        Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-books">
                    <p>No books found in your library.</p>
                </div>
            <?php endif; ?>

            <div class="actions">
                <a href="addbook.php" class="btn">
                    + Add New Book
                </a>
            </div>
        </div>
    </div>
</body>
</html>
