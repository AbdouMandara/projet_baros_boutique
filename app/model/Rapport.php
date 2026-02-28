<?php
class Rapport {
    private $conn;
    private $table_name = "rapports_journaliers";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllRapports($status = null, $order = 'DESC') {
        $query = "SELECT r.*, u.nom_complet, ro.nom_role 
                  FROM " . $this->table_name . " r
                  JOIN utilisateurs u ON r.id_user = u.id_user
                  JOIN roles ro ON u.id_role = ro.id_role";
                  
        if ($status && $status !== 'tous') {
            $query .= " WHERE r.statut_approbation = :status";
        }
        
        $query .= " ORDER BY r.date_soumission_du_rapport " . ($order === 'ASC' ? 'ASC' : 'DESC');
        
        $stmt = $this->conn->prepare($query);
        
        if ($status && $status !== 'tous') {
            $stmt->bindParam(":status", $status);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRapportsByUser($id_user) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_user = :id_user ORDER BY date_soumission_du_rapport DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($id_user, $bilan_activite) {
        $query = "INSERT INTO " . $this->table_name . " (id_rapport, id_user, bilan_activite, statut_approbation) VALUES (:id_rapport, :id_user, :bilan_activite, 'en_attente')";
        $stmt = $this->conn->prepare($query);
        
        $id_rapport = generate_uuid();
        
        $stmt->bindParam(":id_rapport", $id_rapport);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->bindParam(":bilan_activite", $bilan_activite);
        
        return $stmt->execute();
    }
    
    public function modifierStatut($id_rapport, $nouveau_statut) {
        $query = "UPDATE " . $this->table_name . " SET statut_approbation = :statut WHERE id_rapport = :id_rapport";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":statut", $nouveau_statut);
        $stmt->bindParam(":id_rapport", $id_rapport);
        
        return $stmt->execute();
    }
}
