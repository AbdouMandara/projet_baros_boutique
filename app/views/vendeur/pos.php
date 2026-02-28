<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Point de Vente (POS)</h3>
        <div>
            <button class="btn btn-primary" onclick="showTab('vente')">Vente Rapide</button>
            <button class="btn" style="background-color:var(--info); color:white;" onclick="showTab('pack')">Créer un Pack</button>
        </div>
    </div>
    
    <!-- Zone Vente -->
    <div id="tab-vente">
        <h4 style="color:var(--primary);">Nouvelle Vente</h4>
        <form hx-post="<?= BASE_URL ?>/vendeur/pos/sell" hx-target="#main-content" id="form-vente">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Client</label>
                    <select name="id_client" class="form-control" required>
                        <option value="">-- Choisir un client --</option>
                        <?php foreach($clients as $cli): ?>
                            <option value="<?= $cli['id_client'] ?>"><?= htmlspecialchars($cli['nom_client']) ?> (<?= htmlspecialchars($cli['telephone']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Produit (en stock)</label>
                    <select id="select-produit" name="id_produit" class="form-control" required onchange="updateTotalVente()">
                        <option value="" data-prix="0" data-stock="0">-- Choisir un produit --</option>
                        <?php foreach($produits as $prod): ?>
                            <?php if($prod['stock_actuel'] > 0 || (isset($_GET['produit']) && $_GET['produit'] === $prod['id_produit'])): ?>
                            <option value="<?= $prod['id_produit'] ?>" 
                                    data-prix="<?= $prod['prix_vente'] ?>" 
                                    data-stock="<?= $prod['stock_actuel'] ?>"
                                    <?= (isset($_GET['produit']) && $_GET['produit'] === $prod['id_produit']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prod['designation']) ?> - <?= number_format($prod['prix_vente'], 0, ',', ' ') ?> CFA (Stock: <?= $prod['stock_actuel'] ?>)
                            </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Quantité</label>
                    <input type="number" id="input-qty" name="quantite" class="form-control" value="1" min="1" required oninput="updateTotalVente()">
                    <small id="qty-warning" style="color:var(--error); display:none; margin-top:5px;">La quantité dépasse le stock disponible !</small>
                </div>
                
                <div class="form-group" style="text-align: right; padding-top: 25px;">
                    <h2 style="margin:0; color:var(--primary); font-size:28px;">Total : <span id="total-vente">0</span> CFA</h2>
                </div>
            </div>
            
            <div style="text-align:right; margin-top:20px;">
                <button type="submit" class="btn btn-primary" id="btn-submit-vente" style="padding:12px 30px; font-size:16px;">Vendre</button>
            </div>
        </form>
    </div>
    
    <!-- Zone Création Pack -->
    <div id="tab-pack" style="display:none;">
        <h4 style="color:var(--info);">Créer un Pack de produits</h4>
        <form hx-post="<?= BASE_URL ?>/vendeur/pos/pack/add" hx-target="#main-content" id="form-pack">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group mb-0">
                    <label>Nom du Pack</label>
                    <input type="text" name="nom_pack" class="form-control" required placeholder="ex: Pack Rentrée scolaire">
                </div>
                <div class="form-group mb-0">
                    <label>Pourcentage de réduction (%)</label>
                    <input type="number" id="pack-reduction" name="reduction" class="form-control" value="10" min="0" max="100" required oninput="updateTotalPack()">
                </div>
            </div>
            
            <div style="border: 1px solid var(--border); border-radius: 8px; padding: 15px; background-color: var(--bg-color);">
                <h5>Produits du pack</h5>
                <div id="pack-produits-list">
                    <div class="pack-row d-flex align-items-center mb-3" style="gap:15px;">
                        <select name="produits_ids[]" class="form-control pack-prod-select" style="flex:2;" required onchange="updateTotalPack()">
                            <option value="" data-prix="0">-- Choisir un produit --</option>
                            <?php foreach($produits as $prod): ?>
                                <option value="<?= $prod['id_produit'] ?>" data-prix="<?= $prod['prix_vente'] ?>">
                                    <?= htmlspecialchars($prod['designation']) ?> - <?= number_format($prod['prix_vente'], 0, ',', ' ') ?> CFA
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="quantites[]" class="form-control pack-qty-input" value="1" min="1" style="flex:1;" required oninput="updateTotalPack()">
                        <button type="button" class="btn" style="background-color: var(--error); color: white;" onclick="this.parentElement.remove(); updateTotalPack();">X</button>
                    </div>
                </div>
                <button type="button" class="btn" style="background-color: var(--info); color: white; margin-top:10px;" onclick="addPackRow()">+ Ajouter un produit</button>
            </div>
            
            <div style="text-align: right; margin-top:20px; background-color: var(--surface); padding: 15px; border-radius: 8px;">
                <p style="margin:5px 0;">Sous-total : <span id="pack-subtotal" style="font-weight:bold;">0</span> CFA</p>
                <h3 style="margin:5px 0; color:var(--info);">Prix final du pack : <span id="pack-total">0</span> CFA</h3>
            </div>
            
            <div style="text-align:right; margin-top:20px;">
                <button type="submit" class="btn" style="background-color:var(--info); color:white; padding:12px 30px; font-size:16px;">Créer le Pack</button>
            </div>
        </form>
    </div>
</div>

<script>
function showTab(tabName) {
    if(tabName === 'vente') {
        document.getElementById('tab-vente').style.display = 'block';
        document.getElementById('tab-pack').style.display = 'none';
        updateTotalVente();
    } else {
        document.getElementById('tab-vente').style.display = 'none';
        document.getElementById('tab-pack').style.display = 'block';
    }
}

function updateTotalVente() {
    const select = document.getElementById('select-produit');
    const option = select.options[select.selectedIndex];
    const prix = parseFloat(option.getAttribute('data-prix')) || 0;
    const stock = parseInt(option.getAttribute('data-stock')) || 0;
    
    const qtyInput = document.getElementById('input-qty');
    const qty = parseInt(qtyInput.value) || 0;
    
    const warning = document.getElementById('qty-warning');
    const btnSubmit = document.getElementById('btn-submit-vente');
    
    if (qty > stock && select.value !== "") {
        warning.style.display = 'block';
        btnSubmit.disabled = true;
    } else {
        warning.style.display = 'none';
        btnSubmit.disabled = false;
    }
    
    const total = prix * qty;
    document.getElementById('total-vente').innerText = total.toLocaleString('fr-FR');
}

function addPackRow() {
    const container = document.getElementById('pack-produits-list');
    const optionsHTML = document.querySelector('.pack-prod-select').innerHTML;
    const rowHTML = `
        <div class="pack-row d-flex align-items-center mb-3" style="gap:15px;">
            <select name="produits_ids[]" class="form-control pack-prod-select" style="flex:2;" required onchange="updateTotalPack()">
                ${optionsHTML}
            </select>
            <input type="number" name="quantites[]" class="form-control pack-qty-input" value="1" min="1" style="flex:1;" required oninput="updateTotalPack()">
            <button type="button" class="btn" style="background-color: var(--error); color: white;" onclick="this.parentElement.remove(); updateTotalPack();">X</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', rowHTML);
}

function updateTotalPack() {
    const rows = document.querySelectorAll('.pack-row');
    let subtotal = 0;
    
    rows.forEach(row => {
        const select = row.querySelector('.pack-prod-select');
        const qty = row.querySelector('.pack-qty-input');
        
        if (select && qty) {
            const option = select.options[select.selectedIndex];
            const prix = parseFloat(option.getAttribute('data-prix')) || 0;
            const quantite = parseInt(qty.value) || 0;
            subtotal += (prix * quantite);
        }
    });
    
    const reductionInput = document.getElementById('pack-reduction');
    const reduction = parseFloat(reductionInput.value) || 0;
    
    const total = subtotal - (subtotal * reduction / 100);
    
    document.getElementById('pack-subtotal').innerText = subtotal.toLocaleString('fr-FR');
    document.getElementById('pack-total').innerText = total.toLocaleString('fr-FR');
}

// Initialise
document.addEventListener('DOMContentLoaded', () => {
    updateTotalVente();
});
</script>
