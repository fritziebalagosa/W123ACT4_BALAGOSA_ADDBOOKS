<?php 
require_once '../classes/database.php';
require_once '../classes/books.php';
$bookObj = new Books();

$books = ["title"=>"", "author"=>"", "genre"=>"", "publication_year"=>""];
$errors = ["title"=>"", "author"=>"", "genre"=>"", "publication_year"=>""];


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
        if ($bookObj->titleExists($books["title"])) {
            $errors['title'] = "This title already exists. Please enter a different one.";
        } else {
            $bookObj->title = $books["title"];
            $bookObj->author = $books["author"];
            $bookObj->genre = $books["genre"];
            $bookObj->publication_year = $books["publication_year"];

            
        $result = $bookObj->addBook($books["title"], $books["author"], $books["genre"], $books["publication_year"]);
            if ($result) {
                $message = "<div class='alert success'>Book successfully submitted!</div>";
            } else {
                $message = "<div class='alert error'>Error: Failed to submit book.</div>";
                }
                echo $message;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Books</title>
    <style>
            * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .alert {
            width: 80%;
            margin: 15px auto;
            padding: 12px 20px;
            border-radius: 6px;
            font-family: Arial, sans-serif;
            font-size: 15px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-in-out;
        }
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            padding: 40px 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .header h1 {
            font-size: 32px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }
        
        .header p {
            color: #6b7280;
            font-size: 16px;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }
        
        .required-note {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 24px;
        }
        
        .required-note span {
            color: #ef4444;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        
        label .required {
            color: #ef4444;
            margin-left: 2px;
        }
        
        input, select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            transition: border-color 0.2s;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        input::placeholder {
            color: #9ca3af;
        }
        
        select {
            cursor: pointer;
        }
        
        .error {
            color: #ef4444;
            font-size: 14px;
            margin-top: 4px;
        }
        
        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }
        
        .btn-primary {
            flex: 1;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.2s;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-secondary {
            flex: 1;
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }
        
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Add New Book</h1>
            <p>Add a new book to your library collection</p>
        </div>
        
        <div class="form-card">
            <h2 class="section-title">Book Information</h2>
            <p class="required-note">Fields marked with <span>*</span> are required</p>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="title">Title <span class="required">*</span></label>
                    <input type="text" name="title" id="title" placeholder="Enter book title" value="<?php echo $books["title"]; ?>">
                    <?php if($errors["title"]): ?>
                        <div class="error"><?php echo $errors["title"]; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="author">Author <span class="required">*</span></label>
                    <input type="text" name="author" id="author" placeholder="Enter author name" value="<?php echo $books["author"]; ?>">
                    <?php if($errors["author"]): ?>
                        <div class="error"><?php echo $errors["author"]; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="genre">Genre <span class="required">*</span></label>
                    <select name="genre" id="genre">
                        <option value="">Select a genre</option>
                        <option value="History" <?php if($books["genre"] == "History") echo "selected"; ?>>History</option>
                        <option value="Science" <?php if($books["genre"] == "Science") echo "selected"; ?>>Science</option>
                        <option value="Fiction" <?php if($books["genre"] == "Fiction") echo "selected"; ?>>Fiction</option>
                    </select>
                    <?php if($errors["genre"]): ?>
                        <div class="error"><?php echo $errors["genre"]; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="publication_year">Publication Year <span class="required">*</span></label>
                    <input type="number" name="publication_year" id="publication_year" min="1000" placeholder="Enter year (e.g. 2024)" value="<?php echo $books["publication_year"]; ?>">
                    <?php if($errors["publication_year"]): ?>
                        <div class="error"><?php echo $errors["publication_year"]; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="buttons">
                    <button type="submit" class="btn-primary">
                        <span>+</span> Add Book
                    </button>
                    <a href="viewbook.php" class="btn-secondary">
                        <span>👁</span> View Books
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
