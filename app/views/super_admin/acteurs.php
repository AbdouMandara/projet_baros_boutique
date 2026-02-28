<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Gestion des Acteurs</h3>
        <button class="btn btn-primary" onclick="document.getElementById('add-actor-form').style.display=document.getElementById('add-actor-form').style.display==='none'?'block':'none'">+ Nouvel Acteur</button>
    </div>
    
    <!-- Zone formulaire d'ajout -->
    <div id="add-actor-form" style="display:none; background-color: var(--bg-color); padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid var(--border);">
        <h4 style="margin-top:0; color:var(--primary);">Ajouter un acteur</h4>
        <form hx-post="<?= BASE_URL ?>/superadmin/acteurs/add" hx-target="#main-content">
            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div class="form-group mb-0">
                    <label>Nom de l'utilisateur</label>
                    <input type="text" name="nom_complet" class="form-control" placeholder="ex: Abdou" required>
                </div>
                <div class="form-group mb-0">
                    <label>Mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group mb-0">
                    <label>Rôle</label>
                    <select name="id_role" class="form-control" required>
                        <option value="">-- Choisir un rôle --</option>
                        <?php foreach($roles as $role): ?>
                            <?php if($role['nom_role'] !== 'Super Admin'): ?>
                                <option value="<?= $role['id_role'] ?>"><?= htmlspecialchars($role['nom_role']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div style="margin-top: 15px; text-align:right;">
                <button type="button" class="btn" onclick="document.getElementById('add-actor-form').style.display='none'" style="background-color:transparent; color:var(--text-main); border:1px solid var(--border);">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>

    <!-- Tableau des acteurs -->
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border);">
                    <th style="padding: 12px;">Nom d'utilisateur</th>
                    <th style="padding: 12px;">Rôle</th>
                    <th style="padding: 12px;">Statut</th>
                    <th style="padding: 12px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($acteurs)): ?>
                    <tr><td colspan="4" style="padding:15px; text-align:center;">Aucun autre acteur enregistré.</td></tr>
                <?php else: ?>
                    <?php foreach($acteurs as $user): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px; font-weight:600;"><?= htmlspecialchars($user['nom_complet']) ?></td>
                            <td style="padding: 12px;">
                                <span style="background-color: var(--bg-color); padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                    <?= htmlspecialchars($user['nom_role']) ?>
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <?php if($user['is_blocked'] == 1): ?>
                                    <span style="color: var(--error); font-weight:bold; font-size:14px;">Bloqué</span>
                                <?php else: ?>
                                    <span style="color: var(--success); font-weight:bold; font-size:14px;">Actif</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px; text-align:right; gap:10px; display:flex; justify-content:flex-end;">
                                <?php if($user['is_blocked'] == 1): ?>
                                    <button hx-post="<?= BASE_URL ?>/superadmin/acteurs/toggle_block" hx-vals='{"id_user": "<?= $user['id_user'] ?>", "status": 0}' hx-target="#main-content" class="btn" style="background-color:var(--success); color:white; padding:6px 12px; font-size:13px;">Débloquer</button>
                                <?php else: ?>
                                    <button hx-post="<?= BASE_URL ?>/superadmin/acteurs/toggle_block" hx-vals='{"id_user": "<?= $user['id_user'] ?>", "status": 1}' hx-target="#main-content" class="btn" style="background-color:var(--warning); color:white; padding:6px 12px; font-size:13px;">Bloquer</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
