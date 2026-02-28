<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../utils/helpers.php';

class User {
    private $conn;
    private $table_name = "utilisateurs";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Authentifie un utilisateur
     */
    public function login($nom_complet, $password) {
        $query = "SELECT u.id_user, u.nom_complet, u.password_hash, u.id_role, u.is_blocked, r.nom_role 
                  FROM " . $this->table_name . " u
                  JOIN roles r ON u.id_role = r.id_role
                  WHERE u.nom_complet = :nom_complet LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nom_complet", $nom_complet);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password_hash'])){
                if($row['is_blocked'] == 1) {
                    return ["status" => false, "message" => "Votre compte est bloqué par un super-administrateur."];
                }
                return ["status" => true, "data" => $row];
            }
        }
        return ["status" => false, "message" => "Identifiants incorrects."];
    }

    /**
     * Récupère un utilisateur par ID
     */
    public function getUserById($id_user) {
        $query = "SELECT u.id_user, u.nom_complet, u.is_blocked, r.nom_role 
                  FROM " . $this->table_name . " u
                  JOIN roles r ON u.id_role = r.id_role
                  WHERE u.id_user = :id_user LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crée un nouvel acteur.
     */
    public function create($nom_complet, $password, $id_role) {
        $query = "INSERT INTO " . $this->table_name . " 
                 (id_user, id_role, nom_complet, password_hash) 
                 VALUES (:id_user, :id_role, :nom_complet, :password_hash)";
                 
        $stmt = $this->conn->prepare($query);
        
        $id_user = generate_uuid();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":id_user", $id_user);
        $stmt->bindParam(":id_role", $id_role);
        $stmt->bindParam(":nom_complet", $nom_complet);
        $stmt->bindParam(":password_hash", $password_hash);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Récupère tous les autres acteurs (pour le superadmin)
     */
    public function getAllOtherUsers($current_role_id) {
         $query = "SELECT u.id_user, u.nom_complet, u.is_blocked, r.nom_role 
                  FROM " . $this->table_name . " u
                  JOIN roles r ON u.id_role = r.id_role
                  WHERE u.id_role != :role_id
                  ORDER BY u.date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":role_id", $current_role_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Bloque ou débloque un utilisateur
     */
    public function toggleBlockStatus($id_user, $status) {
        $query = "UPDATE " . $this->table_name . " SET is_blocked = :status WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status, PDO::PARAM_INT);
        $stmt->bindParam(":id_user", $id_user);
        return $stmt->execute();
    }
}
