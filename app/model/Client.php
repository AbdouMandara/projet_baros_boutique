<?php
class Client {
    private $conn;
    private $table_name = "clients";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllClients() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nom_client";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nom_client, $telephone) {
        $query = "INSERT INTO " . $this->table_name . " (id_client, nom_client, telephone) VALUES (:id, :nom, :tel)";
        $stmt = $this->conn->prepare($query);
        
        $id_client = generate_uuid();
        
        $stmt->bindParam(":id", $id_client);
        $stmt->bindParam(":nom", $nom_client);
        $stmt->bindParam(":tel", $telephone);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false; // probably duplicate telephone
        }
    }
    
    public function delete($id_client) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_client = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id_client);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false; // foreign key failure if client has sales
        }
    }
}
