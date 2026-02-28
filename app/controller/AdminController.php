<?php
require_once __DIR__ . '/../utils/render.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Log.php';
require_once __DIR__ . '/../model/Produit.php';
require_once __DIR__ . '/../model/Categorie.php';
require_once __DIR__ . '/../model/Rapport.php';

class AdminController {
    private $db;
    private $userModel;
    private $logModel;
    private $produitModel;
    private $categorieModel;
    private $rapportModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->logModel = new Log($db);
        $this->produitModel = new Produit($db);
        $this->categorieModel = new Categorie($db);
        $this->rapportModel = new Rapport($db);
    }

    public function handleRequest($route) {
        $path = parse_url($route, PHP_URL_PATH);
        
        if ($path === '/admin/dashboard') $this->dashboard();
        elseif ($path === '/admin/categories') $this->categories();
        elseif ($path === '/admin/categories/add') $this->addCategorie();
        elseif ($path === '/admin/produits') $this->produits();
        elseif ($path === '/admin/produits/add') $this->addProduit();
        elseif ($path === '/admin/produits/delete') $this->deleteProduit();
        elseif ($path === '/admin/produits/stock') $this->updateStock();
        elseif ($path === '/admin/rapports') $this->rapports();
        elseif ($path === '/admin/rapports/add') $this->addRapport();
        else {
            http_response_code(404);
            echo "Page introuvable.";
        }
    }

    private function dashboard() {
        $data = [
            'page_title' => 'Tableau de bord - Admin',
            'current_page' => 'dashboard'
        ];
        renderView('admin/dashboard', $data);
    }

    private function categories() {
        $categories = $this->categorieModel->getAllCategories();
        $data = [
            'page_title' => 'Gestion des Catégories',
            'current_page' => 'categories',
            'categories' => $categories
        ];
        renderView('admin/categories', $data);
    }

    private function addCategorie() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = sanitize_input($_POST['nom_categorie'] ?? '');
            $desc = sanitize_input($_POST['description'] ?? '');
            
            if (empty($nom)) {
                $this->sendHtmxResponse(false, "Veuillez remplir le nom de la catégorie.");
                return;
            }
            
            if ($this->categorieModel->create($nom, $desc)) {
                $this->logModel->create($_SESSION['user_id'], "Ajout de la catégorie : $nom");
                $this->sendHtmxResponse(true, "Catégorie ajoutée.");
            } else {
                $this->sendHtmxResponse(false, "Erreur.");
            }
        }
    }

    private function produits() {
        $id_cat = $_GET['categorie'] ?? null;
        if ($id_cat === '') $id_cat = null;
        
        $produits = $this->produitModel->getAllProduits($id_cat);
        $categories = $this->categorieModel->getAllCategories();
        
        $data = [
            'page_title' => 'Gestion des Produits',
            'current_page' => 'produits',
            'produits' => $produits,
            'categories' => $categories,
            'current_filter' => $id_cat
        ];
        renderView('admin/produits', $data);
    }

    private function addProduit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $designation = sanitize_input($_POST['designation'] ?? '');
            $id_categorie = sanitize_input($_POST['id_categorie'] ?? '');
            $prix_achat = (float)($_POST['prix_achat'] ?? 0);
            $prix_vente = (float)($_POST['prix_vente'] ?? 0);
            $stock = (int)($_POST['stock_actuel'] ?? 0);
            
            if (empty($designation) || empty($id_categorie) || $prix_achat <= 0 || $prix_vente <= 0) {
                if (empty($id_categorie)) {
                    $this->sendHtmxResponse(false, "Veuillez d'abord créer une catégorie avant d'ajouter un produit.");
                } else {
                    $this->sendHtmxResponse(false, "Veuillez remplir tous les champs obligatoires avec des valeurs valides.");
                }
                return;
            }
            
            // Upload image
            $image_name = 'default.png';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['image']['tmp_name'];
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $image_name = uniqid() . '.' . $ext;
                $dest = __DIR__ . '/../../public/assets/images/' . $image_name;
                move_uploaded_file($tmp, $dest);
            }

            if ($this->produitModel->create($designation, $id_categorie, $prix_achat, $prix_vente, $stock, $image_name)) {
                $this->logModel->create($_SESSION['user_id'], "Ajout du produit : $designation");
                $this->sendHtmxResponse(true, "Produit ajouté.");
            } else {
                $this->sendHtmxResponse(false, "Erreur lors de l'ajout.");
            }
        }
    }

    private function deleteProduit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = sanitize_input($_POST['id_produit'] ?? '');
            if ($this->produitModel->softDelete($id)) {
                $this->logModel->create($_SESSION['user_id'], "Suppression du produit ID: $id");
                $this->sendHtmxResponse(true, "Produit supprimé.");
            } else {
                $this->sendHtmxResponse(false, "Erreur.");
            }
        }
    }

    private function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = sanitize_input($_POST['id_produit'] ?? '');
            $qty = (int)($_POST['quantite_a_ajouter'] ?? 0);
            if ($this->produitModel->updateStock($id, $qty)) {
                $this->logModel->create($_SESSION['user_id'], "Ajout de $qty au stock produit ID: $id");
                $this->sendHtmxResponse(true, "Stock mis à jour.");
            } else {
                $this->sendHtmxResponse(false, "Erreur.");
            }
        }
    }
    
    private function rapports() {
        // We will fetch user reports
        $rapports = $this->rapportModel->getRapportsByUser($_SESSION['user_id']);
        $data = [
            'page_title' => 'Mes Rapports',
            'current_page' => 'rapports',
            'rapports' => $rapports
        ];
        renderView('admin/rapports', $data);
    }
    
    private function addRapport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bilan = sanitize_input($_POST['bilan_activite'] ?? '');
            if ($this->rapportModel->create($_SESSION['user_id'], $bilan)) {
                $this->logModel->create($_SESSION['user_id'], "Soumission d'un rapport");
                $this->sendHtmxResponse(true, "Rapport soumis avec succès.");
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
