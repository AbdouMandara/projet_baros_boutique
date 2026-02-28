<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Gestion des Produits</h3>
        <button class="btn btn-primary" onclick="document.getElementById('add-prod-form').style.display=document.getElementById('add-prod-form').style.display==='none'?'block':'none'">+ Nouveau Produit</button>
    </div>
    
    <div class="mb-4">
        <form hx-get="<?= BASE_URL ?>/admin/produits" hx-target="#main-content" hx-push-url="true">
            <select name="categorie" class="form-control" onchange="this.form.requestSubmit()" style="width:250px;">
                <option value="">Toutes les catégories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= $current_filter === $cat['id_categorie'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    
    <!-- Zone formulaire d'ajout -->
    <div id="add-prod-form" style="display:none; background-color: var(--bg-color); padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid var(--border);">
        <h4 style="margin-top:0; color:var(--primary);">Ajouter un produit</h4>
        <form hx-post="<?= BASE_URL ?>/admin/produits/add" hx-target="#main-content" hx-encoding="multipart/form-data">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group mb-0">
                    <label>Désignation</label>
                    <input type="text" name="designation" class="form-control" required>
                </div>
                <div class="form-group mb-0">
                    <label>Catégorie</label>
                    <select name="id_categorie" class="form-control" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label>Prix d'achat (CFA)</label>
                    <input type="number" step="0.01" name="prix_achat" class="form-control" required>
                </div>
                <div class="form-group mb-0">
                    <label>Prix de vente (CFA)</label>
                    <input type="number" step="0.01" name="prix_vente" class="form-control" required>
                </div>
                <div class="form-group mb-0">
                    <label>Stock initial</label>
                    <input type="number" name="stock_actuel" class="form-control" value="0" required>
                </div>
                <div class="form-group mb-0">
                    <label>Image du produit</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>
            </div>
            <div style="margin-top: 15px; text-align:right;">
                <button type="button" class="btn" onclick="document.getElementById('add-prod-form').style.display='none'" style="background-color:transparent; color:var(--text-main); border:1px solid var(--border);">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>

    <!-- Tableau des produits -->
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
                                <?php if($prod['stock_actuel'] <= 5): ?> ⚠️ <?php endif; ?>
                            </td>
                            <td style="padding: 12px; text-align:right; gap:10px; display:flex; justify-content:flex-end;">
                                <button onclick="addStock('<?= $prod['id_produit'] ?>', '<?= addslashes(htmlspecialchars($prod['designation'])) ?>')" class="btn" style="background-color:var(--info); color:white; padding:6px 12px; font-size:13px;">+ Stock</button>
                                <button hx-post="<?= BASE_URL ?>/admin/produits/delete" hx-vals='{"id_produit": "<?= $prod['id_produit'] ?>"}' hx-target="#main-content" hx-confirm="Êtes-vous sûr de vouloir supprimer ce produit ?" class="btn" style="background-color:var(--error); color:white; padding:6px 12px; font-size:13px;">Retirer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function addStock(id, nom) {
    Swal.fire({
        title: 'Ajout de Stock',
        text: 'Quantité à ajouter pour : ' + nom,
        input: 'number',
        inputAttributes: {
            min: 1, step: 1
        },
        showCancelButton: true,
        confirmButtonText: 'Valider',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#8A2BE2'
    }).then((result) => {
        if (result.isConfirmed && result.value > 0) {
            const formData = new FormData();
            formData.append('id_produit', id);
            formData.append('quantite_a_ajouter', result.value);
            
            fetch('<?= BASE_URL ?>/admin/produits/stock', {
                method: 'POST',
                body: formData
            }).then(() => {
                window.location.reload();
            });
        }
    });
}
</script>
