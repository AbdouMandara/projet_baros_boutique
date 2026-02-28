<?php
require 'config/database.php';
require 'app/utils/helpers.php';
require 'app/model/User.php';

$db = (new Database())->getConnection();
$userModel = new User($db);

$users = [
    ['nom_complet' => 'Root', 'password' => 'password123', 'id_role' => 'iid001'],
    ['nom_complet' => 'AdminAbdou', 'password' => 'password123', 'id_role' => 'iid002'],
    ['nom_complet' => 'LeVendeur', 'password' => 'password123', 'id_role' => 'iid003'],
    ['nom_complet' => 'LeTech', 'password' => 'password123', 'id_role' => 'iid004'],
];

foreach ($users as $u) {
    if ($userModel->create($u['nom_complet'], $u['password'], $u['id_role'])) {
        echo "Utilisateur " . $u['nom_complet'] . " créé avec succès.\n";
    } else {
        echo "Erreur lors de la création de " . $u['nom_complet'] . ".\n";
    }
}
