<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Stock de Produits</h3>
    </div>
    
    <div class="mb-4">
        <form hx-get="<?= BASE_URL ?>/vendeur/produits" hx-target="#main-content" hx-push-url="true">
            <select name="categorie" class="form-control" onchange="this.form.requestSubmit()" style="width:250px;">
                <option value="">Toutes les catégories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= $current_filter === $cat['id_categorie'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border);">
                    <th style="padding: 12px; width: 60px;">Image</th>
                    <th style="padding: 12px;">Désignation</th>
                    <th style="padding: 12px;">Catégorie</th>
                    <th style="padding: 12px;">Prix de vente</th>
                    <th style="padding: 12px;">Stock actuel</th>
                    <th style="padding: 12px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($produits)): ?>
                    <tr><td colspan="6" style="padding:15px; text-align:center;">Aucun produit trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach($produits as $prod): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px;">
                                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($prod['image']) ?>" alt="img" style="width:40px; height:40px; object-fit:cover; border-radius:4px;">
                            </td>
                            <td style="padding: 12px; font-weight:600;"><?= htmlspecialchars($prod['designation']) ?></td>
                            <td style="padding: 12px; color:var(--text-muted);"><?= htmlspecialchars($prod['nom_categorie']) ?></td>
                            <td style="padding: 12px;"><?= number_format($prod['prix_vente'], 0, ',', ' ') ?> CFA</td>
                            <td style="padding: 12px;">
                                <span style="font-weight:bold; <?= $prod['stock_actuel'] <= 5 ? 'color:var(--error);' : 'color:var(--success);' ?>">
                                    <?= $prod['stock_actuel'] ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align:right;">
                                <!-- "il y a un bouton 'vendre' quand il clique dessus une side bar à gauche sort c'est celle vendre les produits en stock au client" -->
                                <!-- We can redirect to POS page with product selected -->
                                <a href="<?= BASE_URL ?>/vendeur/pos?produit=<?= $prod['id_produit'] ?>" class="btn btn-primary" style="padding:6px 12px; font-size:13px; text-decoration:none;">Vendre</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
