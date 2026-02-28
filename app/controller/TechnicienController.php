<?php
require_once __DIR__ . '/../utils/render.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Log.php';
require_once __DIR__ . '/../model/Rapport.php';

class TechnicienController {
    private $db;
    private $userModel;
    private $logModel;
    private $rapportModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->logModel = new Log($db);
        $this->rapportModel = new Rapport($db);
    }

    public function handleRequest($route) {
        $path = parse_url($route, PHP_URL_PATH);
        
        switch ($path) {
            case '/technicien/dashboard': $this->dashboard(); break;
            case '/technicien/rapports': $this->rapports(); break;
            case '/technicien/rapports/add': $this->addRapport(); break;
            default:
                http_response_code(404);
                echo "Page introuvable.";
                break;
        }
    }

    private function dashboard() {
        $data = [
            'page_title' => 'Tableau de bord - Technicien',
            'current_page' => 'dashboard'
        ];
        renderView('technicien/dashboard', $data);
    }
    
    private function rapports() {
        $rapports = $this->rapportModel->getRapportsByUser($_SESSION['user_id']);
        $data = [
            'page_title' => 'Mes Rapports',
            'current_page' => 'rapports',
            'rapports' => $rapports
        ];
        renderView('technicien/rapports', $data);
    }
    
    private function addRapport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bilan = sanitize_input($_POST['bilan_activite'] ?? '');
            if ($this->rapportModel->create($_SESSION['user_id'], $bilan)) {
                $this->logModel->create($_SESSION['user_id'], "Soumission d'un rapport");
                $this->sendHtmxResponse(true, "Rapport soumis.");
            } else {
                $this->sendHtmxResponse(false, "Erreur lors de la soumission.");
            }
        }
    }

    private function sendHtmxResponse($success, $message) {
        $icon = $success ? 'success' : 'error';
        $title = $success ? 'Succès' : 'Erreur';
        echo "<script>
            Swal.fire({
                icon: '$icon',
                title: '$title',
                text: '$message',
                timer: 1500,
                showConfirmButton: false,
                confirmButtonColor: '#8A2BE2'
            }).then(() => {
                window.location.reload();
            });
        </script>";
        exit;
    }
}
