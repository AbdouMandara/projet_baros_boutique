CREATE DATABASE IF NOT EXISTS projet_baros_retape CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE projet_baros_retape;
CREATE TABLE roles (
    id_role VARCHAR(36) PRIMARY KEY,
    nom_role VARCHAR(50) NOT NULL -- Super Admin, Admin, Vendeur, etc.
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE utilisateurs (
    id_user VARCHAR(36) PRIMARY KEY,
    id_role VARCHAR(36) NOT NULL,
    nom_complet VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    is_blocked TINYINT(1) DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_role) REFERENCES roles(id_role) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE logs_systeme (
    id_log VARCHAR(36) PRIMARY KEY,
    id_user VARCHAR(36) NOT NULL,
    action_detaillee TEXT NOT NULL,
    date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES utilisateurs(id_user) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rapports_journaliers (
    id_rapport VARCHAR(36) PRIMARY KEY,
    id_user VARCHAR(36) NOT NULL,
    bilan_activite TEXT NOT NULL,
    statut_approbation ENUM('en_attente', 'validé', 'rejeté') DEFAULT 'en_attente',
    date_soumission_du_rapport TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES utilisateurs(id_user) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------
-- 3. MODULE PRODUITS & PACKS
-- ------------------------------
CREATE TABLE categories (
    id_categorie VARCHAR(36) PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL,
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE produits (
    id_produit VARCHAR(36) PRIMARY KEY,
    id_categorie VARCHAR(36),
    designation VARCHAR(150) NOT NULL,
    prix_achat DECIMAL(12,2) NOT NULL,
    prix_vente DECIMAL(12,2) NOT NULL,
    stock_actuel INT DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE packs (
    id_pack VARCHAR(36) PRIMARY KEY,
    nom_pack VARCHAR(100) NOT NULL,
    prix_pack DECIMAL(12,2) NOT NULL,
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE avoir_pack_produit (
    id_pack VARCHAR(36) NOT NULL,
    id_produit VARCHAR(36) NOT NULL,
    quantite INT DEFAULT 1,
    prix_pack DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (id_pack, id_produit),
    FOREIGN KEY (id_pack) REFERENCES packs(id_pack) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------
-- 4. MODULE CLIENTS & VENTES
-- ------------------------------
CREATE TABLE clients (
    id_client VARCHAR(36) PRIMARY KEY,
    nom_client VARCHAR(100) NOT NULL,
    telephone VARCHAR(25) UNIQUE NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ventes (
    id_vente VARCHAR(36) PRIMARY KEY,
    id_client VARCHAR(36) NOT NULL,
    id_produit VARCHAR(36) NOT NULL,
    prix_total DECIMAL(12,2) NOT NULL,
    date_vente TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client) REFERENCES clients(id_client) ON DELETE RESTRICT,
    FOREIGN KEY (id_produit) REFERENCES produits(id_produit) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------
-- 5. PARAMÈTRES
-- ------------------------------
CREATE TABLE parametres_application (
    id_parametre VARCHAR(36) PRIMARY KEY,
    devise VARCHAR(10) DEFAULT 'CFA',
    logo VARCHAR(255) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    telephone VARCHAR(25) NOT NULL,
    email VARCHAR(100) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
