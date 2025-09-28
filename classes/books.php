<?php 

require_once 'database.php'; 

class Books {
    public $title;
    public $author;
    public $genre;
    public $publication_year;   

    protected $db;

    public function __construct() {
        $this->db = new Database();
        $this->db = $this->db->connect();
    }

   public function addBook($title, $author, $genre, $publication_year) {
    $sql = "INSERT INTO books (title, author, genre, publication_year) 
            VALUES (:title, :author, :genre, :publication_year)";
    $query = $this->db->prepare($sql);
    $query->bindParam(':title', $this->title);
    $query->bindParam(':author', $this->author);
    $query->bindParam(':genre', $this->genre);
    $query->bindParam(':publication_year', $this->publication_year);

    return $query->execute(); 
}

    public function titleExists($title) {
    $sql = "SELECT COUNT(*) FROM books WHERE title = :title";
    $query = $this->db->prepare($sql);
    $query->execute([':title' => $title]);
    return $query->fetchColumn() > 0;
    }

    public function getAllBooks() {
    $sql = "SELECT title, author, genre, publication_year FROM books ORDER BY title ASC";
    $query = $this->db->prepare($sql);
    $query->execute();
    return $query->fetchAll();
    }

    public function searchBooks($search) {
        $query = $this->db->prepare("SELECT * FROM books WHERE title LIKE :search"); 
        $searchTerm = '%' . $search . '%';
        $query->bindParam(':search', $searchTerm);  $query->execute(); 
        $books = $query->fetchAll(); 
        return $books;
    }

    public function getBookByTitle($title) {
        $sql = "SELECT * FROM books WHERE title = :title";
        $query = $this->db->prepare($sql); 
        $query->bindParam(":title", $title); 
        $query->execute(); 
        return $query->fetch();
    }

    public function updateBook($oldTitle, $newTitle, $author, $genre, $publication_year) {
        $sql = "UPDATE books SET title = :newTitle, author = :author, genre = :genre, publication_year = :publication_year 
                WHERE title = :oldTitle";
        $query = $this->db->prepare($sql);
        $query->bindParam(":newTitle", $newTitle);
        $query->bindParam(":author", $author);
        $query->bindParam(":genre", $genre); 
        $query->bindParam(":publication_year", $publication_year);
        $query->bindParam(":oldTitle", $oldTitle);
        return $query->execute();
    }
      
   public function deleteBook($title) {
        $sql = "DELETE FROM books WHERE title = :title";
        $query = $this->db->prepare($sql);
        $query->bindParam(':title', $title);
        return $query->execute();
    }


    
}
$obj = new Books(); 