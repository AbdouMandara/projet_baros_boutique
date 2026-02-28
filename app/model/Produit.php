<?php
class Produit {
    private $conn;
    private $table_name = "produits";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllProduits($id_categorie = null) {
        $query = "SELECT p.*, c.nom_categorie 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categories c ON p.id_categorie = c.id_categorie
                  WHERE p.is_delete = 0";
                  
        if ($id_categorie) {
            $query .= " AND p.id_categorie = :id_categorie";
        }
        $query .= " ORDER BY p.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        if ($id_categorie) {
            $stmt->bindParam(":id_categorie", $id_categorie);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($designation, $id_categorie, $prix_achat, $prix_vente, $stock_actuel, $image) {
        $query = "INSERT INTO " . $this->table_name . " 
                 (id_produit, id_categorie, designation, image, prix_achat, prix_vente, stock_actuel) 
                 VALUES (:id, :id_cat, :des, :img, :pa, :pv, :stk)";
        $stmt = $this->conn->prepare($query);
        
        $id_produit = generate_uuid();
        
        $stmt->bindParam(":id", $id_produit);
        $stmt->bindParam(":id_cat", $id_categorie);
        $stmt->bindParam(":des", $designation);
        $stmt->bindParam(":img", $image);
        $stmt->bindParam(":pa", $prix_achat);
        $stmt->bindParam(":pv", $prix_vente);
        $stmt->bindParam(":stk", $stock_actuel);
        
        return $stmt->execute();
    }
    
    public function softDelete($id_produit) {
        $query = "UPDATE " . $this->table_name . " SET is_delete = 1 WHERE id_produit = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id_produit);
        return $stmt->execute();
    }
    
    public function updateStock($id_produit, $quantite_a_ajouter) {
        $query = "UPDATE " . $this->table_name . " SET stock_actuel = stock_actuel + :qty WHERE id_produit = :id AND is_delete = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":qty", $quantite_a_ajouter, PDO::PARAM_INT);
        $stmt->bindParam(":id", $id_produit);
        return $stmt->execute();
    }
    
    public function decrementStock($id_produit, $quantite_vendue) {
        $query = "UPDATE " . $this->table_name . " SET stock_actuel = stock_actuel - :qty WHERE id_produit = :id AND is_delete = 0 AND stock_actuel >= :qty";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":qty", $quantite_vendue, PDO::PARAM_INT);
        $stmt->bindParam(":id", $id_produit);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
