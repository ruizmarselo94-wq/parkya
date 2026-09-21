<?php
$rateTypes = ['hourly' => 'Por hora', 'daily' => 'Por día', 'monthly' => 'Mensual'];

$selectedLot = null;
foreach ($lots as $lot) {
    if ((int) $lot['id'] === $selectedLotId) {
        $selectedLot = $lot;
        break;
    }
}
$lotName = $selectedLot['name'] ?? '';
?>

<h1>Tarifas</h1>

<div class="stack">
    <div class="card">
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
        <p class="muted">Las tarifas que ves y creás abajo son solo de este estacionamiento.</p>
    </div>

    <div class="card">
        <h2>
            <?= $editRate ? 'Editar tarifa' : 'Nueva tarifa' ?>
            <?= $lotName !== '' ? 'de ' . e($lotName) : '' ?>
        </h2>
        <form method="post" class="fields fields-4">
            <input type="hidden" name="lot_id" value="<?= $selectedLotId ?>">
            <?php if ($editRate): ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= (int) $editRate['id'] ?>">
            <?php endif; ?>
            <label>Tipo
                <select name="type">
                    <?php foreach ($rateTypes as $value => $typeLabel): ?>
                        <option value="<?= $value ?>" <?= ($editRate['type'] ?? 'hourly') === $value ? 'selected' : '' ?>>
                            <?= $typeLabel ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>Monto (Gs.)
                <input type="number" name="amount" step="1" min="1" required value="<?= e($editRate['amount'] ?? '') ?>">
            </label>
            <label>Vigente desde
                <input type="date" name="valid_from" value="<?= e($editRate['valid_from'] ?? date('Y-m-d')) ?>" required>
            </label>
            <label>Vigente hasta (opcional)
                <input type="date" name="valid_to" value="<?= e($editRate['valid_to'] ?? '') ?>">
            </label>
            <button type="submit"><?= $editRate ? 'Guardar cambios' : 'Crear tarifa' ?></button>
        </form>
        <?php if ($editRate): ?>
            <p><a href="rates.php?lot_id=<?= $selectedLotId ?>">Cancelar edición</a></p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Tarifas<?= $lotName !== '' ? ' de ' . e($lotName) : '' ?></h2>
        <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Tipo</th><th>Monto</th><th>Desde</th><th>Hasta</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($rates as $rate): ?>
                    <tr>
                        <td><?= e(label($rate['type'])) ?></td>
                        <td><?= money($rate['amount']) ?> Gs.</td>
                        <td><?= e($rate['valid_from']) ?></td>
                        <td><?= e($rate['valid_to'] ?? 'indefinido') ?></td>
                        <td>
                            <div class="row-actions">
                                <a class="btn-action btn-edit" href="rates.php?lot_id=<?= $selectedLotId ?>&edit_rate=<?= (int) $rate['id'] ?>">Editar</a>
                                <form method="post" onsubmit="return confirm('¿Eliminar esta tarifa?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="lot_id" value="<?= $selectedLotId ?>">
                                    <input type="hidden" name="id" value="<?= (int) $rate['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($rates)): ?>
                    <tr><td colspan="5" class="muted">Sin tarifas cargadas para este estacionamiento.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
