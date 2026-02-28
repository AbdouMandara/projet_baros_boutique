<?php
class Vente {
    private $conn;
    private $table_name = "ventes";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllVentes() {
        $query = "SELECT v.*, c.nom_client, p.designation 
                  FROM " . $this->table_name . " v
                  JOIN clients c ON v.id_client = c.id_client
                  JOIN produits p ON v.id_produit = p.id_produit
                  ORDER BY v.date_vente DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($id_client, $id_produit, $quantite, $prix_unitaire) {
        // Dans le cas de ventes, database.sql ne gère pas la quantité vendue directement dans `ventes`
        // Mais il y a le `prix_total` qui est = quantité * prix_unitaire
        
        $query = "INSERT INTO " . $this->table_name . " (id_vente, id_client, id_produit, prix_total) VALUES (:id, :idc, :idp, :pt)";
        $stmt = $this->conn->prepare($query);
        
        $id_vente = generate_uuid();
        $prix_total = $quantite * $prix_unitaire;
        
        $stmt->bindParam(":id", $id_vente);
        $stmt->bindParam(":idc", $id_client);
        $stmt->bindParam(":idp", $id_produit);
        $stmt->bindParam(":pt", $prix_total);
        
        return $stmt->execute();
    }
}
