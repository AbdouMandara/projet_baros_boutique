<?php
class Categorie {
    private $conn;
    private $table_name = "categories";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllCategories() {
        $query = "SELECT c.*, 
                  (SELECT COUNT(*) FROM produits p WHERE p.id_categorie = c.id_categorie AND p.is_delete = 0) as total_produits
                  FROM " . $this->table_name . " c
                  ORDER BY c.nom_categorie";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nom_categorie, $description) {
        $query = "INSERT INTO " . $this->table_name . " (id_categorie, nom_categorie, description) VALUES (:id, :nom, :desc)";
        $stmt = $this->conn->prepare($query);
        
        $id = generate_uuid();
        
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nom", $nom_categorie);
        $stmt->bindParam(":desc", $description);
        
        return $stmt->execute();
    }
    
    public function delete($id_categorie) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_categorie = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id_categorie);
        return $stmt->execute();
    }
}
