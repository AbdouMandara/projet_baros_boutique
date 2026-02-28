<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Gestion des Clients</h3>
        <button class="btn btn-primary" onclick="document.getElementById('add-cli-form').style.display='block'">+ Nouveau Client</button>
    </div>
    
    <div id="add-cli-form" style="display:none; background-color: var(--bg-color); padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid var(--border);">
        <h4 style="margin-top:0; color:var(--primary);">Ajouter un client</h4>
        <form hx-post="<?= BASE_URL ?>/vendeur/clients/add" hx-target="#main-content">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group mb-0">
                    <label>Nom du client</label>
                    <input type="text" name="nom_client" class="form-control" required>
                </div>
                <div class="form-group mb-0">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" class="form-control" required>
                </div>
            </div>
            <div style="margin-top: 15px; text-align:right;">
                <button type="button" class="btn" onclick="document.getElementById('add-cli-form').style.display='none'" style="background-color:transparent; color:var(--text-main); border:1px solid var(--border);">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border);">
                    <th style="padding: 12px;">Nom</th>
                    <th style="padding: 12px;">Téléphone</th>
                    <th style="padding: 12px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($clients)): ?>
                    <tr><td colspan="3" style="padding:15px; text-align:center;">Aucun client enregistré.</td></tr>
                <?php else: ?>
                    <?php foreach($clients as $cli): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px; font-weight:600;"><?= htmlspecialchars($cli['nom_client']) ?></td>
                            <td style="padding: 12px;"><?= htmlspecialchars($cli['telephone']) ?></td>
                            <td style="padding: 12px; text-align:right;">
                                <button hx-post="<?= BASE_URL ?>/vendeur/clients/delete" hx-vals='{"id_client": "<?= $cli['id_client'] ?>"}' hx-target="#main-content" hx-confirm="Êtes-vous sûr de vouloir supprimer ce client ?" class="btn" style="background-color:var(--error); color:white; padding:6px 12px; font-size:13px;">Supprimer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
