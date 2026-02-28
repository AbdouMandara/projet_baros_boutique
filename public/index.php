<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/utils/helpers.php';

// Définir l'URL de base dynamiquement (utile pour WAMP)
$base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
define('BASE_URL', $base_url);

// Analyser la requête (décoder pour gérer les caractères accentués comme "é" dans l'URL)
$request_uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$script_name = dirname($_SERVER['SCRIPT_NAME']);

// Extraire la route en supprimant la partie correspondant au script
if (strpos($request_uri, $script_name) === 0) {
    $route = substr($request_uri, strlen($script_name));
} else {
    $route = $request_uri;
}

if (empty($route) || $route === '/' || $route === '/index.php') {
    $route = '/login';
}

// Initialisation de la base de données
$database = new Database();
$db = $database->getConnection();

// Routage
$is_authenticated = isset($_SESSION['user_id']);
$user_role = $_SESSION['user_role'] ?? null;

switch ($route) {
    case '/login':
        if ($is_authenticated) {
            redirect_to_dashboard($user_role);
            exit;
        }
        require_once __DIR__ . '/../app/views/auth/login.php';
        break;
        
    case '/logout':
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
        
    case '/auth/login_process':
        require_once __DIR__ . '/../app/controller/AuthController.php';
        $authController = new AuthController($db);
        $authController->login();
        break;
        
    default:
        // Middleware : Vérifie l'authentification
        if (!$is_authenticated) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        // Routage en fonction des préfixes et rôles
        if (strpos($route, '/superadmin') === 0 && $user_role === 'Super Admin') {
            require_once __DIR__ . '/../app/controller/SuperAdminController.php';
            $controller = new SuperAdminController($db);
            $controller->handleRequest($route);
        } elseif (strpos($route, '/admin') === 0 && $user_role === 'Admin') {
            require_once __DIR__ . '/../app/controller/AdminController.php';
            $controller = new AdminController($db);
            $controller->handleRequest($route);
        } elseif (strpos($route, '/vendeur') === 0 && $user_role === 'Vendeur') {
            require_once __DIR__ . '/../app/controller/VendeurController.php';
            $controller = new VendeurController($db);
            $controller->handleRequest($route);
        } elseif (strpos($route, '/technicien') === 0 && $user_role === 'Technicien') {
            require_once __DIR__ . '/../app/controller/TechnicienController.php';
            $controller = new TechnicienController($db);
            $controller->handleRequest($route);
        } else {
            http_response_code(404);
            echo "Page introuvable ou accès refusé. <a href='" . BASE_URL . "/login'>Retour</a>";
        }
        break;
}

function redirect_to_dashboard($role) {
    switch ($role) {
        case 'Super Admin':
            header('Location: ' . BASE_URL . '/superadmin/dashboard');
            break;
        case 'Admin':
            header('Location: ' . BASE_URL . '/admin/dashboard');
            break;
        case 'Vendeur':
            header('Location: ' . BASE_URL . '/vendeur/dashboard');
            break;
        case 'Technicien':
            header('Location: ' . BASE_URL . '/technicien/dashboard');
            break;
        default:
            header('Location: ' . BASE_URL . '/login');
            break;
    }
}
