<?php
class Pack {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllPacks() {
        $query = "SELECT * FROM packs ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getProduitsDuPack($id_pack) {
        $query = "SELECT ap.*, p.designation 
                  FROM avoir_pack_produit ap
                  JOIN produits p ON ap.id_produit = p.id_produit
                  WHERE ap.id_pack = :id_pack";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_pack", $id_pack);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nom_pack, $prix_pack, $description, $produits) {
        try {
            $this->conn->beginTransaction();
            
            $id_pack = generate_uuid();
            $query_pack = "INSERT INTO packs (id_pack, nom_pack, prix_pack, description) VALUES (:id, :nom, :prix, :desc)";
            $stmt_pack = $this->conn->prepare($query_pack);
            $stmt_pack->execute([
                ':id' => $id_pack,
                ':nom' => $nom_pack,
                ':prix' => $prix_pack,
                ':desc' => $description
            ]);
            
            $query_assoc = "INSERT INTO avoir_pack_produit (id_pack, id_produit, quantite, prix_pack) VALUES (:id_pack, :id_prod, :qty, :prix_pack)";
            $stmt_assoc = $this->conn->prepare($query_assoc);
            
            foreach ($produits as $prod) {
                $stmt_assoc->execute([
                    ':id_pack' => $id_pack,
                    ':id_prod' => $prod['id_produit'],
                    ':qty' => $prod['quantite'],
                    ':prix_pack' => $prix_pack
                ]);
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
