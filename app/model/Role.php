<?php
class Role {
    private $conn;
    private $table_name = "roles";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllRoles() {
        $query = "SELECT id_role, nom_role FROM " . $this->table_name . " ORDER BY nom_role";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
