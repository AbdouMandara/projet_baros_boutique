# Projet : Application de ventes

## Pré-requ
## Acteurs et leurs roles
- superadmin :
    -  Accès au rapport soumis par les différents employés
    -  accès au logs 
    - Ajouter les autres acteurs , les modifier et bloquer leur accès à l'application
- admin :
    - Soumettre un rapport journalier
    - voir/ajouter/retirer les produits
    - ajouter/retirer les catégories
    - Gérer les stocks des produits donc seul lui peut augmenté 
    - reçoit les alertes lorsqu'un produit atteint le seuil
    - 
- vendeur :
    - Soumettre un rapport journalier
    - consulter les produits en stock  sans pour autant pouvoir les modifier
    - Il peut ajouter les clients (nom, numero de telephone)
    - vendre les produits en stock ou pack au client
    - Ajout de pack des produits 

- technicien :
    - Soumettre un rapport journalier

## Fonctionnalités

### SuperAdmin
#### Accès au rapport soumis par les différents employés
Il peut voir les rapports que **admin**, **vendeur** et **technicien** ont soumis, il vera **le nom du user qui a soumis le rapport** (il fera cela à travers l'id_user qui joue le role de clé étrangère), **bilan_activite**, **statut_approbation** et **date_soumission_du_rapport**.
Il peut valider ou rejeter un rapport s'il valide alors la valeur du champ **statut_approbation** de la table rapports_journaliers, pour l'action de **valider** il y a une icone de validation et pour **rejeter** une icone pour rejeter ; et en arrière plan une requete est faite pour changer  la valeur du champ **statut_approbation**
Si un rapport est validé alors une notif s'affiche qu'il a validé sinon alors le user en question sera signalé

Il peut choisir les rapports qu'il veut voir par rapport à  leur statut 'en_attente', 'validé', 'rejeté' et  par défaut ne s'affiche qui sont en en_attente
Les rapports sont filtrés selon le temps **heure**, **jour**, **mois** de manière décroissante par défaut, il peut chaneer l'ordre aussi en croissant

#### accès au logs 
Il peut accéder aux logs de l'application qui possède tout ce qui se passe sur l'app (connexion d'un user, ventes, ajout du stock, ajout client en fait tout)
Les logs sont affichés par ordre décroissante selon la date_action
Ils sont filtrés selon le temps **heure**, **jour**, **mois** de manière décroissante par défaut, il peut chaneer l'ordre aussi en croissant

#### Ajouter les autres acteurs , les modifier et bloquer leur accès à l'application
Il peut avoir tous les autres user qui n'ont pas le même role que lui donc **
Il peut ajouter le nom, le password et choisir le role de l'acteur à travers un select qui montrera leur nom  (tous ces champs étant obligatoires) 
Les rôles possibles au choix sont admin, vendeur, technicien
Il peut modifier un user et le bloquer dans ce cas le user dont is_blocked = true ou 1 car ce champ est tinyint est redirigé sur une route où la view dit qu'il ne peut accéder à son poste de travail

#### 

### admin
#### voir/ajouter/retirer les produits
Il y aura un filtre où on affiche les produits au niveau de la catégorie
Il peut voir les produits déjà connus
Il peut retirer les produits si il retire un produit la valeur du champ is_delete passe de TRUE à FALSE
Il peut ajouter les produits en remplissant designation, image (image doit etre un fichier image qui s'affiche coté front pour montrer celui qui est selectionné ce fichier sera déplacé dans le dossier public/assets/images/), prix achat, prix de vente, stock, la catégorie (tous ces champs étant obligatoires) 

####  ajouter/retirer les catégories
Il peut ajouter une catégorie des produits ou retirer

#### Gérer les stocks des produits donc seul lui peut augmenté 
donc il peut modifier la colonne stock_actuel pour ajouter 

#### reçoit les alertes lorsqu'un produit atteint le seuil
Il y a une icone de notif pour lui dire si le stock d'un produit est déjà <= au seuil d'alerte du produit

### vendeur
#### consulter les produits en stock  sans pour autant pouvoir les modifier
Donc il voit les produits en stock (il y a un bouton 'vendre' quand il clique dessus une side bar à gauche sort c'est celle "vendre les produits en stock au client")

####  Il peut ajouter les clients (nom, numero de telephone)
Il peut les ajouter (tous ces champs sont obligatoires) et il peut aussi les supprimer

#### vendre les produits ou packs  en stock au client
Après avoir cliqué sur le bouton "vendre" du produit il choisit le client , il peut modifier la quantité d'un produit et en temps réelle le stock de ce prooduit diminue , la quantité à vendre ne doit pas dépasser le seuil alerte, En bas il voit le montant total que le user doit payer

#### Ajout de pack des produits 
Sur cette partie le vendeur à un select où il choisit le produit du pack  (ce select propose tous les produits déjà en bdd) et à coté un input de type number où il choisit la quantité; il y a un bouton "ajouter produit" qui va lui permettre de prendre encore un autre produit et il fera pareil; il y a  un input de type number où il choisit le pourcentage de reduction et le prix du pack sera donc (la somme d prix * quantité des produits)*pourcentage de reduction le tout divisé par 100

### admin & vendeur & technicien
#### Soumettre un rapport journalier
Il envoie le bilan_activite (ce champ étant obligatoire) 

## Implémentation
- Utilise de bonnes couleurs qui ne tapent pas trop aux yeux , la principale est le violet qui est beau
- le design doit etre cohérent dans toute l'application
- utilise des tokens de connexion lorsque le user se connecte
- je veux un design épuré et professionnel, je veux pas de transform quand je hover mes élements
- Je veux le white et black mode
- utilise les skills dans .agent\skills dont t'as besoin que ce soit pour la secu et le design comme .agent\skills\skills\webapp-testing ; .agent\skills\skills\web-security-testing ; .agent\skills\skills\web-design-guidelines ; .agent\skills\skills\ui-visual-validator; .agent\skills\skills\ui-ux-pro-max ; .agent\skills\skills\ui-ux-designer ; .agent\skills\skills\ui-skills ; .agent\skills\skills\tool-design ; .agent\skills\skills\security-auditor ; .agent\skills\skills\performance-engineer
- Utilises des couleurs adéquates selon la notif (ex : succès c'est couleur verte) 
- Assure toi que coté front l'app est bien optimisé et quelle ne pèse pas
- utilise strip tags, trim etc ...
- avant de soumettre un formulaire pour une interaction avec la bdd on doit faire la vérification coté back-end et front-end mais surtout back-end
- Penses toujours à la sécurité premièrement
- Utilises des noms de variables explicites et simples en français comme ex : **nom_user** ou **password_user**
- utilises des commentaires explicites où la logique est complexe
- Utilises des middlewares sur les routes que tu créeras dans public/index.php pour les protéger
- Utilise la POO coté PHP car on utilisera le design pattern MVC
- Utilise les requetes préparés pour la protection contre les injection SQL
- Ajoutes les fichiers sensibles dans .gitignore (toujours)
- Crées des fonctions réutilisables pour gagner en temps
- Chaque **acteur** à son interface la seule chose qui leur est commune est la page de connexion et chacun sera redirigé sur son interface à travers le fichier public/index.php
- Utilise HTMX au niveau des endroits où il y a des redirections pour ne pas que la page soit rafraichit, les vérifications, l'affichage des erreurs ...
- Utilise SweetAlert pour afficher des notifs comme lorsque j'ai pu me connecter, j'ai ajouté un produit un client un pack

## Technologies 

- HTML
- CSS
- JS 
-  PHP 8.4+
- SweetAlert
- HTMX pour utiliser AJAX
- googlefonts pour les polices et icônes qui conviennent 
- Tu peux utiliser htmx et sweetalert à travers le dossier public/assets/js 

## Apparence de l'app
Pour tous les acteurs, l'interface est d'ab en 2 une side bar à gauche où est affiché ce que le user peut faire (ex : pour admin on a rapport où il pourra voir les rapports soumis) et il verra le resultat dans la 2 e colonne de notre interface
Dans le header on a icone lune et soleil selon le mode, ainsi que l'icone user que quand il hover ça affiche qui est connecté et il y a un bouton déconnecter sur cette div qui va apparaitre
Chaque acteur a son interface qui lui est propre
utilise la police noto sans et poppins , utilise chaque police selon les titres adéquats 
joue sur les gras et le contraste des couleurs

Pour les view de chaque acteur tu la fais en fonction de chhque dossier dans app\views