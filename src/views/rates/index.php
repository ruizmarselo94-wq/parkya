<h1>Tarifas</h1>

<form method="get" class="inline-form">
    <label>Estacionamiento
        <select name="lot_id" onchange="this.form.submit()">
            <?php foreach ($lots as $lot): ?>
                <option value="<?= (int) $lot['id'] ?>" <?= $lot['id'] == $selectedLotId ? 'selected' : '' ?>>
                    <?= e($lot['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</form>

<div class="grid">
    <div class="card">
        <h2>Nueva tarifa</h2>
        <form method="post">
            <input type="hidden" name="lot_id" value="<?= $selectedLotId ?>">
            <label>Tipo
                <select name="type">
                    <option value="hourly">Por hora</option>
                    <option value="daily">Por día</option>
                    <option value="monthly">Mensual</option>
                </select>
            </label>
            <label>Monto (Gs.) <input type="number" name="amount" step="1" min="1" required></label>
            <label>Vigente desde <input type="date" name="valid_from" value="<?= date('Y-m-d') ?>" required></label>
            <label>Vigente hasta (opcional) <input type="date" name="valid_to"></label>
            <button type="submit">Crear tarifa</button>
        </form>
    </div>

    <div class="card">
        <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Tipo</th><th>Monto</th><th>Desde</th><th>Hasta</th></tr></thead>
            <tbody>
                <?php foreach ($rates as $rate): ?>
                    <tr>
                        <td><?= e(label($rate['type'])) ?></td>
                        <td><?= money($rate['amount']) ?> Gs.</td>
                        <td><?= e($rate['valid_from']) ?></td>
                        <td><?= e($rate['valid_to'] ?? 'indefinido') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($rates)): ?>
                    <tr><td colspan="4" class="muted">Sin tarifas cargadas para este estacionamiento.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
