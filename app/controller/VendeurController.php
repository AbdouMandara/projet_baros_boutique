<?php
require_once __DIR__ . '/../utils/render.php';
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Log.php';
require_once __DIR__ . '/../model/Produit.php';
require_once __DIR__ . '/../model/Categorie.php';
require_once __DIR__ . '/../model/Rapport.php';
require_once __DIR__ . '/../model/Client.php';
require_once __DIR__ . '/../model/Vente.php';
require_once __DIR__ . '/../model/Pack.php';

class VendeurController {
    private $db;
    private $userModel;
    private $logModel;
    private $produitModel;
    private $categorieModel;
    private $rapportModel;
    private $clientModel;
    private $venteModel;
    private $packModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->logModel = new Log($db);
        $this->produitModel = new Produit($db);
        $this->categorieModel = new Categorie($db);
        $this->rapportModel = new Rapport($db);
        $this->clientModel = new Client($db);
        $this->venteModel = new Vente($db);
        $this->packModel = new Pack($db);
    }

    public function handleRequest($route) {
        $path = parse_url($route, PHP_URL_PATH);
        
        switch ($path) {
            case '/vendeur/dashboard': $this->dashboard(); break;
            case '/vendeur/produits': $this->produits(); break;
            case '/vendeur/clients': $this->clients(); break;
            case '/vendeur/clients/add': $this->addClient(); break;
            case '/vendeur/clients/delete': $this->deleteClient(); break;
            case '/vendeur/rapports': $this->rapports(); break;
            case '/vendeur/rapports/add': $this->addRapport(); break;
            case '/vendeur/pos': $this->pos(); break;
            case '/vendeur/pos/sell': $this->processVente(); break;
            case '/vendeur/pos/pack/add': $this->createPack(); break;
            default:
                http_response_code(404);
                echo "Page introuvable.";
                break;
        }
    }

    private function dashboard() {
        $data = [
            'page_title' => 'Tableau de bord - Vendeur',
            'current_page' => 'dashboard'
        ];
        renderView('vendeur/dashboard', $data);
    }

    private function produits() {
        $id_cat = $_GET['categorie'] ?? null;
        if ($id_cat === '') $id_cat = null;
        
        $produits = $this->produitModel->getAllProduits($id_cat);
        $categories = $this->categorieModel->getAllCategories();
        
        $data = [
            'page_title' => 'Stock de Produits',
            'current_page' => 'produits',
            'produits' => $produits,
            'categories' => $categories,
            'current_filter' => $id_cat
        ];
        renderView('vendeur/produits', $data);
    }

    private function clients() {
        $clients = $this->clientModel->getAllClients();
        $data = [
            'page_title' => 'Gestion des Clients',
            'current_page' => 'clients',
            'clients' => $clients
        ];
        renderView('vendeur/clients', $data);
    }

    private function addClient() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = sanitize_input($_POST['nom_client'] ?? '');
            $tel = sanitize_input($_POST['telephone'] ?? '');
            
            if (empty($nom) || empty($tel)) {
                $this->sendHtmxResponse(false, "Veuillez renseigner le nom et le téléphone du client.");
                return;
            }
            
            if ($this->clientModel->create($nom, $tel)) {
                $this->logModel->create($_SESSION['user_id'], "Ajout d'un client : $nom");
                $this->sendHtmxResponse(true, "Client ajouté avec succès.");
            } else {
                $this->sendHtmxResponse(false, "Erreur (téléphone peut-être déjà utilisé).");
            }
        }
    }

    private function deleteClient() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = sanitize_input($_POST['id_client'] ?? '');
            if ($this->clientModel->delete($id)) {
                $this->logModel->create($_SESSION['user_id'], "Suppression du client ID: $id");
                $this->sendHtmxResponse(true, "Client supprimé.");
            } else {
                $this->sendHtmxResponse(false, "Impossible de supprimer ce client (il a probablement des ventes associées).");
            }
        }
    }

    private function rapports() {
        $rapports = $this->rapportModel->getRapportsByUser($_SESSION['user_id']);
        $data = [
            'page_title' => 'Mes Rapports',
            'current_page' => 'rapports',
            'rapports' => $rapports
        ];
        renderView('vendeur/rapports', $data);
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

    private function pos() {
        $produits = $this->produitModel->getAllProduits();
        $clients = $this->clientModel->getAllClients();
        $packs = $this->packModel->getAllPacks();
        
        $data = [
            'page_title' => 'Point de Vente (POS)',
            'current_page' => 'pos',
            'produits' => $produits,
            'clients' => $clients,
            'packs' => $packs
        ];
        renderView('vendeur/pos', $data);
    }
    
    private function processVente() {
        // Logique simplifiée pour vendre un seul produit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_client = sanitize_input($_POST['id_client'] ?? '');
            $id_produit = sanitize_input($_POST['id_produit'] ?? '');
            $quantite = (int)($_POST['quantite'] ?? 1);
            
            if (empty($id_client) || empty($id_produit)) {
                $this->sendHtmxResponse(false, "Veuillez sélectionner un client et un produit.");
                return;
            }
            
            if ($quantite <= 0) {
                $this->sendHtmxResponse(false, "La quantité doit être supérieure à 0.");
                return;
            }
            
            // Verifier le stock et obtenir le prix
            // Cette logique serait idéalement dans un service ou model plus complexe
            $produits = $this->produitModel->getAllProduits();
            $prix_unitaire = 0;
            $stock_dispo = 0;
            $nom_prod = "";
            foreach($produits as $p) {
                if ($p['id_produit'] === $id_produit) {
                    $prix_unitaire = $p['prix_vente'];
                    $stock_dispo = $p['stock_actuel'];
                    $nom_prod = $p['designation'];
                    break;
                }
            }
            
            if ($stock_dispo < $quantite) {
                $this->sendHtmxResponse(false, "Stock insuffisant pour cette vente.");
            }
            
            // Transaction
            $this->db->beginTransaction();
            try {
                if ($this->venteModel->create($id_client, $id_produit, $quantite, $prix_unitaire)) {
                    if ($this->produitModel->decrementStock($id_produit, $quantite)) {
                        $this->logModel->create($_SESSION['user_id'], "Vente de $quantite x $nom_prod");
                        $this->db->commit();
                        $this->sendHtmxResponse(true, "Vente effectuée avec succès.");
                    } else {
                        throw new Exception("Erreur decrementation stock");
                    }
                } else {
                    throw new Exception("Erreur creation vente");
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $this->sendHtmxResponse(false, "Erreur lors de la vente.");
            }
        }
    }

    private function createPack() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom_pack = sanitize_input($_POST['nom_pack'] ?? '');
            $reduction = (float)($_POST['reduction'] ?? 0);
            
            $produits_ids = $_POST['produits_ids'] ?? [];
            $quantites = $_POST['quantites'] ?? [];
            
            if (empty($nom_pack)) {
                $this->sendHtmxResponse(false, "Veuillez renseigner le nom du pack.");
                return;
            }
            
            if (empty($produits_ids)) {
                $this->sendHtmxResponse(false, "Le pack doit contenir au moins un produit.");
            }
            
            $produits_data = [];
            $somme_totale = 0;
            
            $all_prods = $this->produitModel->getAllProduits();
            
            for ($i = 0; $i < count($produits_ids); $i++) {
                $id_p = sanitize_input($produits_ids[$i]);
                $qty = (int)$quantites[$i];
                
                // Obtenir le prix
                $prix = 0;
                foreach($all_prods as $ap) {
                    if ($ap['id_produit'] === $id_p) {
                        $prix = $ap['prix_vente'];
                        break;
                    }
                }
                
                $somme_totale += ($prix * $qty);
                $produits_data[] = [
                    'id_produit' => $id_p,
                    'quantite' => $qty
                ];
            }
            
            // le prix du pack sera donc (la somme d prix * quantité des produits) * pourcentage de reduction le tout divisé par 100
            // D'après la consigne, c'est ce calcul qui détermine la *réduction*, ou bien le *prix final*?
            // On suppose que le prix final = Total - (Total * reduction / 100)
            $prix_final = $somme_totale - ($somme_totale * $reduction / 100);
            
            if ($this->packModel->create($nom_pack, $prix_final, "Pack avec réduction de $reduction%", $produits_data)) {
                $this->logModel->create($_SESSION['user_id'], "Création du pack : $nom_pack");
                $this->sendHtmxResponse(true, "Pack créé avec succès.");
            } else {
                $this->sendHtmxResponse(false, "Erreur de création de pack.");
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
