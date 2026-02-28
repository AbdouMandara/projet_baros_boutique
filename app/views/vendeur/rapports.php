<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Mes Rapports Soumis</h3>
        <button class="btn btn-primary" onclick="document.getElementById('add-rap-form').style.display=document.getElementById('add-rap-form').style.display==='none'?'block':'none'">Soumettre un rapport</button>
    </div>
    
    <div id="add-rap-form" style="display:none; background-color: var(--bg-color); padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid var(--border);">
        <h4 style="margin-top:0; color:var(--primary);">Nouveau rapport journalier</h4>
        <form hx-post="<?= BASE_URL ?>/vendeur/rapports/add" hx-target="#main-content">
            <div class="form-group mb-0">
                <label>Bilan de l'activité</label>
                <textarea name="bilan_activite" class="form-control" rows="5" required placeholder="Décrivez votre journée..."></textarea>
            </div>
            <div style="margin-top: 15px; text-align:right;">
                <button type="button" class="btn" onclick="document.getElementById('add-rap-form').style.display='none'" style="background-color:transparent; color:var(--text-main); border:1px solid var(--border);">Annuler</button>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </div>
        </form>
    </div>

    <div style="display: grid; gap: 20px;">
        <?php if(empty($rapports)): ?>
            <div style="padding:20px; text-align:center; border: 1px dashed var(--border); border-radius:8px;">
                <p>Vous n'avez soumis aucun rapport.</p>
            </div>
        <?php else: ?>
            <?php foreach($rapports as $rapport): ?>
                <div style="border: 1px solid var(--border); border-radius: 8px; padding: 20px; background-color: var(--surface);">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <strong>Soumis le :</strong> 
                            <span style="color:var(--text-muted);">
                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($rapport['date_soumission_du_rapport']))) ?>
                            </span>
                        </div>
                        <div>
                            <?php if($rapport['statut_approbation'] == 'validé'): ?>
                                <span style="color: var(--success); font-weight: bold;">Validé ✓</span>
                            <?php elseif($rapport['statut_approbation'] == 'rejeté'): ?>
                                <span style="color: var(--error); font-weight: bold;">Rejeté ✕</span>
                            <?php else: ?>
                                <span style="color: var(--warning); font-weight: bold;">En attente ⏳</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="background-color: var(--bg-color); padding: 15px; border-radius: 6px;">
                        <?= nl2br(htmlspecialchars($rapport['bilan_activite'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
