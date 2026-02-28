<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Logs Système</h3>
        <!-- TODO: Log Filter functionality if necessary -->
    </div>
    
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border);">
                    <th style="padding: 12px;">Date</th>
                    <th style="padding: 12px;">Utilisateur</th>
                    <th style="padding: 12px;">Action détaillée</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($logs)): ?>
                    <tr><td colspan="3" style="padding:15px; text-align:center;">Aucun log trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach($logs as $log): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px;"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($log['date_action']))) ?></td>
                            <td style="padding: 12px; font-weight:600; color:var(--primary);"><?= htmlspecialchars($log['nom_complet']) ?></td>
                            <td style="padding: 12px;"><?= htmlspecialchars($log['action_detaillee']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
