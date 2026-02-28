<?php
require_once __DIR__ . '/../utils/render.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Log.php';
require_once __DIR__ . '/../model/Rapport.php';

class SuperAdminController {
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
        switch ($route) {
            case '/superadmin/dashboard':
                $this->dashboard();
                break;
            case '/superadmin/rapports':
                $this->rapports();
                break;
            case '/superadmin/logs':
                $this->logs();
                break;
            case '/superadmin/acteurs':
                $this->acteurs();
                break;
            case '/superadmin/acteurs/add':
                $this->addActeur();
                break;
            case '/superadmin/acteurs/toggle_block':
                $this->toggleBlockActeur();
                break;
            case '/superadmin/rapports/valider':
                $this->validerRapport();
                break;
            case '/superadmin/rapports/rejeter':
                $this->rejeterRapport();
                break;
            default:
                http_response_code(404);
                echo "Page introuvable.";
                break;
        }
    }

    private function dashboard() {
        $data = [
            'page_title' => 'Tableau de bord - SuperAdmin',
            'current_page' => 'dashboard'
        ];
        renderView('super_admin/dashboard', $data);
    }

    private function rapports() {
        $status_filter = $_GET['status'] ?? 'en_attente';
        $order = $_GET['order'] ?? 'DESC';
        $rapports = $this->rapportModel->getAllRapports($status_filter, $order);
        
        $data = [
            'page_title' => 'Rapports soumis',
            'current_page' => 'rapports',
            'rapports' => $rapports,
            'status_filter' => $status_filter,
            'order' => $order
        ];
        renderView('super_admin/rapports', $data);
    }

    private function logs() {
        $logs = $this->logModel->getAllLogs();
        $data = [
            'page_title' => 'Logs Système',
            'current_page' => 'logs',
            'logs' => $logs
        ];
        renderView('super_admin/logs', $data);
    }

    private function acteurs() {
        $acteurs = $this->userModel->getAllOtherUsers($_SESSION['user_id']);
        
        require_once __DIR__ . '/../model/Role.php';
        $roleModel = new Role($this->db);
        $roles_list = $roleModel->getAllRoles();
        
        $data = [
            'page_title' => 'Gestion des Acteurs',
            'current_page' => 'acteurs',
            'acteurs' => $acteurs,
            'roles' => $roles_list
        ];
        renderView('super_admin/acteurs', $data);
    }

    private function addActeur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_complet = sanitize_input($_POST['nom_complet'] ?? '');
            $password = $_POST['password'] ?? '';
            $id_role = sanitize_input($_POST['id_role'] ?? '');
            
            if ($this->userModel->create($nom_complet, $password, $id_role)) {
                $this->logModel->create($_SESSION['user_id'], "Ajout d'un nouvel acteur : $nom_complet");
                $this->sendHtmxResponse(true, "Acteur ajouté avec succès.");
            } else {
                $this->sendHtmxResponse(false, "Erreur lors de l'ajout.");
            }
        }
    }

    private function toggleBlockActeur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = sanitize_input($_POST['id_user'] ?? '');
            $status = (int)($_POST['status'] ?? 0);
            
            if ($this->userModel->toggleBlockStatus($id_user, $status)) {
                $action = $status === 1 ? "Bloquage" : "Débloquage";
                $this->logModel->create($_SESSION['user_id'], "$action de l'utilisateur ID: $id_user");
                $this->sendHtmxResponse(true, "Statut de l'acteur mis à jour.");
            } else {
                $this->sendHtmxResponse(false, "Erreur lors de la mise à jour.");
            }
        }
    }

    private function validerRapport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_rapport = sanitize_input($_POST['id_rapport'] ?? '');
            if ($this->rapportModel->modifierStatut($id_rapport, 'validé')) {
                $this->logModel->create($_SESSION['user_id'], "Validation du rapport ID: $id_rapport");
                $this->sendHtmxResponse(true, "Rapport validé avec succès.");
            } else {
                $this->sendHtmxResponse(false, "Erreur lors de la validation du rapport.");
            }
        }
    }

    private function rejeterRapport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_rapport = sanitize_input($_POST['id_rapport'] ?? '');
            if ($this->rapportModel->modifierStatut($id_rapport, 'rejeté')) {
                $this->logModel->create($_SESSION['user_id'], "Rejet du rapport ID: $id_rapport");
                $this->sendHtmxResponse(true, "Rapport rejeté avec succès.");
            } else {
                $this->sendHtmxResponse(false, "Erreur lors du rejet du rapport.");
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
