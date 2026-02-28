<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Rapports Soumis</h3>
        
        <form hx-get="<?= BASE_URL ?>/superadmin/rapports" hx-target="#main-content" hx-push-url="true" class="d-flex" style="gap:10px;">
            <select name="status" class="form-control" onchange="this.form.requestSubmit()" style="width: auto;">
                <option value="tous" <?= $status_filter === 'tous' ? 'selected' : '' ?>>Tous les statuts</option>
                <option value="en_attente" <?= $status_filter === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                <option value="validé" <?= $status_filter === 'validé' ? 'selected' : '' ?>>Validés</option>
                <option value="rejeté" <?= $status_filter === 'rejeté' ? 'selected' : '' ?>>Rejetés</option>
            </select>
            
            <select name="order" class="form-control" onchange="this.form.requestSubmit()" style="width: auto;">
                <option value="DESC" <?= $order === 'DESC' ? 'selected' : '' ?>>Plus récents en premier</option>
                <option value="ASC" <?= $order === 'ASC' ? 'selected' : '' ?>>Plus anciens en premier</option>
            </select>
        </form>
    </div>
    
    <div style="display: grid; gap: 20px;">
        <?php if(empty($rapports)): ?>
            <div style="padding:20px; text-align:center; border: 1px dashed var(--border); border-radius:8px;">
                <p>Aucun rapport trouvé pour ces critères.</p>
            </div>
        <?php else: ?>
            <?php foreach($rapports as $rapport): ?>
                <div style="border: 1px solid var(--border); border-radius: 8px; padding: 20px; background-color: var(--surface);">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <strong><?= htmlspecialchars($rapport['nom_complet']) ?></strong> 
                            <span style="color:var(--text-muted); font-size:14px;">(<?= htmlspecialchars($rapport['nom_role']) ?>)</span>
                        </div>
                        <div>
                            <span style="font-size:14px; color:var(--text-muted);">
                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($rapport['date_soumission_du_rapport']))) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div style="background-color: var(--bg-color); padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                        <?= nl2br(htmlspecialchars($rapport['bilan_activite'])) ?>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Statut: 
                            <?php if($rapport['statut_approbation'] == 'validé'): ?>
                                <span style="color: var(--success); font-weight: bold;">Validé ✓</span>
                            <?php elseif($rapport['statut_approbation'] == 'rejeté'): ?>
                                <span style="color: var(--error); font-weight: bold;">Rejeté ✕</span>
                            <?php else: ?>
                                <span style="color: var(--warning); font-weight: bold;">En attente ⏳</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($rapport['statut_approbation'] == 'en_attente'): ?>
                        <div style="display:flex; gap:10px;">
                            <button hx-post="<?= BASE_URL ?>/superadmin/rapports/valider" hx-vals='{"id_rapport": "<?= $rapport['id_rapport'] ?>"}' hx-target="#main-content" class="btn" style="background-color:var(--success); color:white; padding: 8px 15px;">✓ Valider</button>
                            <button hx-post="<?= BASE_URL ?>/superadmin/rapports/rejeter" hx-vals='{"id_rapport": "<?= $rapport['id_rapport'] ?>"}' hx-target="#main-content" class="btn" style="background-color:var(--error); color:white; padding: 8px 15px;">✕ Rejeter</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
