<?php
$role = $_SESSION['user_role'] ?? '';
// variable défini par la méthode renderView
$current_page = $current_page ?? ''; 
?>
<aside class="sidebar">
    <div class="sidebar-header">
        <h2>Baros</h2>
    </div>
    
    <nav class="sidebar-nav">
        <?php if ($role === 'Super Admin'): ?>
            <a href="<?= BASE_URL ?>/superadmin/dashboard" class="nav-item <?= $current_page === 'dashboard' ? 'active' : '' ?>">Tableau de bord</a>
            <a href="<?= BASE_URL ?>/superadmin/rapports" class="nav-item <?= $current_page === 'rapports' ? 'active' : '' ?>">Rapports soumis</a>
            <a href="<?= BASE_URL ?>/superadmin/logs" class="nav-item <?= $current_page === 'logs' ? 'active' : '' ?>">Logs Système</a>
            <a href="<?= BASE_URL ?>/superadmin/acteurs" class="nav-item <?= $current_page === 'acteurs' ? 'active' : '' ?>">Gestion Acteurs</a>
            
        <?php elseif ($role === 'Admin'): ?>
            <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-item <?= $current_page === 'dashboard' ? 'active' : '' ?>">Tableau de bord</a>
            <a href="<?= BASE_URL ?>/admin/produits" class="nav-item <?= $current_page === 'produits' ? 'active' : '' ?>">Produits & Stocks</a>
            <a href="<?= BASE_URL ?>/admin/categories" class="nav-item <?= $current_page === 'categories' ? 'active' : '' ?>">Catégories</a>
            <a href="<?= BASE_URL ?>/admin/rapports" class="nav-item <?= $current_page === 'rapports' ? 'active' : '' ?>">Mes Rapports</a>
            
        <?php elseif ($role === 'Vendeur'): ?>
            <a href="<?= BASE_URL ?>/vendeur/dashboard" class="nav-item <?= $current_page === 'dashboard' ? 'active' : '' ?>">Tableau de bord</a>
            <a href="<?= BASE_URL ?>/vendeur/pos" class="nav-item <?= $current_page === 'pos' ? 'active' : '' ?>">Vendre</a>
            <a href="<?= BASE_URL ?>/vendeur/produits" class="nav-item <?= $current_page === 'produits' ? 'active' : '' ?>">Stock</a>
            <a href="<?= BASE_URL ?>/vendeur/clients" class="nav-item <?= $current_page === 'clients' ? 'active' : '' ?>">Clients</a>
            <a href="<?= BASE_URL ?>/vendeur/rapports" class="nav-item <?= $current_page === 'rapports' ? 'active' : '' ?>">Mes Rapports</a>
            
        <?php elseif ($role === 'Technicien'): ?>
            <a href="<?= BASE_URL ?>/technicien/dashboard" class="nav-item <?= $current_page === 'dashboard' ? 'active' : '' ?>">Tableau de bord</a>
            <a href="<?= BASE_URL ?>/technicien/rapports" class="nav-item <?= $current_page === 'rapports' ? 'active' : '' ?>">Mes Rapports</a>
        <?php endif; ?>
    </nav>
</aside>
