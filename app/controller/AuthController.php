<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Log.php';

class AuthController {
    private $db;
    private $userModel;
    private $logModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->logModel = new Log($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_complet = sanitize_input($_POST['nom_complet'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($nom_complet) || empty($password)) {
                $this->sendResponse(false, "Veuillez remplir tous les champs.");
                return;
            }

            $result = $this->userModel->login($nom_complet, $password);

            if ($result['status']) {
                $user = $result['data'];
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['nom_complet'] = $user['nom_complet'];
                $_SESSION['user_role'] = $user['nom_role'];
                
                // Log de l'action
                $this->logModel->create($user['id_user'], "Connexion au système");

                $this->sendResponse(true, "Connexion réussie !", ['role' => $user['nom_role']]);
            } else {
                $this->sendResponse(false, $result['message']);
            }
        }
    }

    private function sendResponse($success, $message, $data = []) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}
