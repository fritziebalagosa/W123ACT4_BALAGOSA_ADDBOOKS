<?php 
require_once '../classes/database.php';
require_once '../classes/books.php';

$bookObj = new Books();

$books = ["title"=>"", "author"=>"", "genre"=>"", "publication_year"=>""];
$errors = ["title"=>"", "author"=>"", "genre"=>"", "publication_year"=>""];
$message = "";


if (!isset($_GET['title']) || empty($_GET['title'])) {
    die("Error: Book title is required.");
}
$oldTitle = urldecode($_GET['title']);

$existingBook = $bookObj->getBookByTitle($oldTitle);
if (!$existingBook) {
    die("Error: Book not found.");
}
$books = $existingBook;


if($_SERVER["REQUEST_METHOD"]== "POST") {
    $books["title"] =  trim(htmlspecialchars($_POST["title"])) ;
    $books["author"] = trim(htmlspecialchars($_POST["author"]));
    $books["genre"] = trim(htmlspecialchars($_POST["genre"]));
    $books["publication_year"] = trim(htmlspecialchars($_POST["publication_year"]));

    if(empty($books["title"])){
        $errors['title'] = "Title is required";
    }

    if(empty($books["author"])){
        $errors['author'] = "Author is required";
    }

    if(empty($books["genre"])){
        $errors['genre'] = "Please select a genre";
    }

    if (empty($books["publication_year"])) {
        $errors['publication_year'] = "Publication year is required";
    } elseif (!is_numeric($books["publication_year"])) {
        $errors['publication_year'] = "Publication year must be a number";
    } elseif ((int)$books["publication_year"] > (int)date("Y")) {
        $errors['publication_year'] = "Publication year can't be in the future";
    }

    if(empty(array_filter($errors))){
        if ($books["title"] !== $oldTitle && $bookObj->titleExists($books["title"])) {
            $errors['title'] = "This title already exists. Please enter a different one.";
        } else {
            $result = $bookObj->updateBook($oldTitle, $books["title"], $books["author"], $books["genre"], $books["publication_year"]);
            if ($result) {
                $message = "<div class='alert success'>Book successfully updated!</div>";
                $oldTitle = $books["title"];
            } else {
                $message = "<div class='alert error'>Error: Failed to update book.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
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
            max-width: 700px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2rem;
            color: #333;
            font-weight: 600;
        }
        .header p {
            color: #666;
            font-size: 1rem;
        }
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 24px;
        }
        .form-card h2 {
            font-size: 1.25rem;
            margin-bottom: 12px;
            color: #333;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #333;
        }
        .form-group input, 
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
        }
        .form-group input:focus, 
        .form-group select:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }
        .error {
            margin-top: 5px;
            color: #dc2626;
            font-size: 0.875rem;
        }
        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 0.95rem;
            text-align: center;
        }
        .alert.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        .buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1rem;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s;
            border: none;
        }
        .btn-primary {
            background-color: #2563eb;
            color: white;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Edit Book</h1>
            <p>Update the book details</p>
        </div>

        <?php echo $message; ?>

        <div class="form-card">
            <h2>Book Details </h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?title=' . urlencode($oldTitle); ?>" method="POST">
                <div class="form-group">
                    <label for="title">Title <span style="color:red">*</span></label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($books["title"]); ?>">
                    <?php if($errors["title"]): ?><div class="error"><?php echo $errors["title"]; ?></div><?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="author">Author <span style="color:red">*</span></label>
                    <input type="text" name="author" id="author" value="<?php echo htmlspecialchars($books["author"]); ?>">
                    <?php if($errors["author"]): ?><div class="error"><?php echo $errors["author"]; ?></div><?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="genre">Genre <span style="color:red">*</span></label>
                    <select name="genre" id="genre">
                        <option value="">Select a genre</option>
                        <option value="History" <?php if($books["genre"]=="History") echo "selected"; ?>>History</option>
                        <option value="Science" <?php if($books["genre"]=="Science") echo "selected"; ?>>Science</option>
                        <option value="Fiction" <?php if($books["genre"]=="Fiction") echo "selected"; ?>>Fiction</option>
                    </select>
                    <?php if($errors["genre"]): ?><div class="error"><?php echo $errors["genre"]; ?></div><?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="publication_year">Publication Year <span style="color:red">*</span></label>
                    <input type="number" name="publication_year" id="publication_year" value="<?php echo htmlspecialchars($books["publication_year"]); ?>">
                    <?php if($errors["publication_year"]): ?><div class="error"><?php echo $errors["publication_year"]; ?></div><?php endif; ?>
                </div>
                
                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Update Book</button>
                    <a href="viewbook.php" class="btn btn-secondary">Return to List</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
