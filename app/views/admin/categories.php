<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Gestion des Catégories</h3>
        <button class="btn btn-primary" onclick="document.getElementById('add-cat-form').style.display=document.getElementById('add-cat-form').style.display==='none'?'block':'none'">+ Nouvelle Catégorie</button>
    </div>
    
    <div id="add-cat-form" style="display:none; background-color: var(--bg-color); padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid var(--border);">
        <h4 style="margin-top:0; color:var(--primary);">Ajouter une catégorie</h4>
        <form hx-post="<?= BASE_URL ?>/admin/categories/add" hx-target="#main-content">
            <div class="form-group">
                <label>Nom de la catégorie</label>
                <input type="text" name="nom_categorie" class="form-control" required>
            </div>
            <div class="form-group mb-0">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div style="margin-top: 15px; text-align:right;">
                <button type="button" class="btn" onclick="document.getElementById('add-cat-form').style.display='none'" style="background-color:transparent; color:var(--text-main); border:1px solid var(--border);">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border);">
                    <th style="padding: 12px;">Nom</th>
                    <th style="padding: 12px;">Description</th>
                    <th style="padding: 12px;">Nombre de produits</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($categories)): ?>
                    <tr><td colspan="3" style="padding:15px; text-align:center;">Aucune catégorie enregistrée.</td></tr>
                <?php else: ?>
                    <?php foreach($categories as $cat): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px; font-weight:600;"><?= htmlspecialchars($cat['nom_categorie']) ?></td>
                            <td style="padding: 12px; color:var(--text-muted);"><?= htmlspecialchars($cat['description']) ?></td>
                            <td style="padding: 12px;"><span style="background-color:var(--primary); color:white; padding:4px 8px; border-radius:12px; font-size:12px;"><?= $cat['total_produits'] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
