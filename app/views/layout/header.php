<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'Baros') ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    
    <!-- Scripts -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= BASE_URL ?>/assets/js/main.js" defer></script>
</head>
<body hx-boost="true">
    
    <!-- Header principal -->
    <header class="app-header">
        <h2 class="page-title"><?= htmlspecialchars($page_title ?? 'Tableau de bord') ?></h2>
        
        <div class="header-actions">
            <!-- Bouton thème -->
            <button class="theme-btn" id="theme-toggle-btn" title="Changer le thème" style="display:flex; align-items:center;"></button>
            
            <!-- Menu Utilisateur -->
            <div class="user-menu-container">
                <button class="user-btn">
                    <span class="user-avatar"><?= strtoupper(substr($_SESSION['nom_complet'] ?? 'U', 0, 1)) ?></span>
                    <?= htmlspecialchars($_SESSION['nom_complet'] ?? 'Utilisateur') ?>
                </button>
                <div class="user-dropdown">
                    <!-- HTMX doit être désactivé pour la déconnexion afin de forcer un Full Page Reload vers la mire de login -->
                    <a href="<?= BASE_URL ?>/logout" class="dropdown-item" hx-boost="false">Se déconnecter</a>
                </div>
            </div>
        </div>
    </header>
