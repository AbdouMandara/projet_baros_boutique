<?php
class Log {
    private $conn;
    private $table_name = "logs_systeme";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($id_user, $action) {
        $query = "INSERT INTO " . $this->table_name . " (id_log, id_user, action_detaillee) VALUES (:id_log, :id_user, :action)";
        $stmt = $this->conn->prepare($query);
        
        $id_log = generate_uuid();
        
        $stmt->bindParam(":id_log", $id_log);
        $stmt->bindParam(":id_user", $id_user);
        $stmt->bindParam(":action", $action);
        
        return $stmt->execute();
    }
    
    public function getAllLogs() {
        $query = "SELECT l.*, u.nom_complet 
                  FROM " . $this->table_name . " l
                  JOIN utilisateurs u ON l.id_user = u.id_user
                  ORDER BY l.date_action DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
