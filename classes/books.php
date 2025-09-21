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
    return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    
}
$obj = new Books(); 