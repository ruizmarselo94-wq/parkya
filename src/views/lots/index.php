<h1>Estacionamientos</h1>

<div class="stack">
    <div class="card">
        <h2><?= $editLot ? 'Editar estacionamiento' : 'Nuevo estacionamiento' ?></h2>
        <form method="post" class="fields">
            <?php if ($editLot): ?>
                <input type="hidden" name="action" value="update_lot">
                <input type="hidden" name="id" value="<?= (int) $editLot['id'] ?>">
            <?php else: ?>
                <input type="hidden" name="action" value="create_lot">
            <?php endif; ?>
            <label>Nombre <input type="text" name="name" required value="<?= e($editLot['name'] ?? '') ?>"></label>
            <label>Dirección <input type="text" name="address" required value="<?= e($editLot['address'] ?? '') ?>"></label>
            <button type="submit"><?= $editLot ? 'Guardar cambios' : 'Crear' ?></button>
        </form>
        <?php if ($editLot): ?>
            <p><a href="lots.php">Cancelar edición</a></p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Listado</h2>
        <p class="muted">Elegí un estacionamiento para ver y agregar sus lugares.</p>
        <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Nombre</th><th>Dirección</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($lots as $lot): ?>
                    <tr class="<?= (int) $lot['id'] === $selectedLotId ? 'row-active' : '' ?>">
                        <td><a href="lots.php?lot_id=<?= (int) $lot['id'] ?>"><?= e($lot['name']) ?></a></td>
                        <td><?= e($lot['address']) ?></td>
                        <td>
                            <?php if ($lot['is_active']): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-muted">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="row-actions">
                                <a class="btn-action btn-edit" href="lots.php?lot_id=<?= (int) $lot['id'] ?>&edit_lot=<?= (int) $lot['id'] ?>">Editar</a>
                                <form method="post" onsubmit="return confirm('¿Eliminar este estacionamiento? Se borran también sus lugares y tarifas.');">
                                    <input type="hidden" name="action" value="delete_lot">
                                    <input type="hidden" name="id" value="<?= (int) $lot['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($lots)): ?>
                    <tr><td colspan="4" class="muted">Todavía no hay estacionamientos.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php
$selectedLot = null;
foreach ($lots as $lot) {
    if ((int) $lot['id'] === $selectedLotId) {
        $selectedLot = $lot;
        break;
    }
}

$spaceTypes = ['standard' => 'Estándar', 'handicap' => 'Discapacidad', 'compact' => 'Compacto'];
$spaceStatuses = ['available' => 'Disponible', 'reserved' => 'Reservado', 'out_of_service' => 'Fuera de servicio'];
?>

<?php if ($selectedLot): ?>
    <div class="stack">
        <div class="card">
            <h2><?= $editSpace ? 'Editar lugar de ' : 'Nuevo lugar en ' ?><?= e($selectedLot['name']) ?></h2>
            <form method="post" class="fields">
                <input type="hidden" name="lot_id" value="<?= $selectedLotId ?>">
                <?php if ($editSpace): ?>
                    <input type="hidden" name="action" value="update_space">
                    <input type="hidden" name="id" value="<?= (int) $editSpace['id'] ?>">
                <?php else: ?>
                    <input type="hidden" name="action" value="create_space">
                <?php endif; ?>
                <label>Código
                    <input type="text" name="code" required placeholder="A1" value="<?= e($editSpace['code'] ?? '') ?>">
                </label>
                <label>Tipo
                    <select name="type">
                        <?php foreach ($spaceTypes as $value => $typeLabel): ?>
                            <option value="<?= $value ?>" <?= ($editSpace['type'] ?? 'standard') === $value ? 'selected' : '' ?>>
                                <?= $typeLabel ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <?php if ($editSpace): ?>
                    <label>Estado
                        <select name="status">
                            <?php foreach ($spaceStatuses as $value => $statusLabel): ?>
                                <option value="<?= $value ?>" <?= ($editSpace['status'] ?? 'available') === $value ? 'selected' : '' ?>>
                                    <?= $statusLabel ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                <?php endif; ?>
                <button type="submit"><?= $editSpace ? 'Guardar cambios' : 'Agregar lugar' ?></button>
            </form>
            <?php if ($editSpace): ?>
                <p><a href="lots.php?lot_id=<?= $selectedLotId ?>">Cancelar edición</a></p>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Lugares de <?= e($selectedLot['name']) ?></h2>
            <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Código</th><th>Tipo</th><th>Estado</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($spaces as $space): ?>
                        <tr>
                            <td><?= e($space['code']) ?></td>
                            <td><?= e(label($space['type'])) ?></td>
                            <td>
                                <?php if ($space['is_occupied']): ?>
                                    <span class="badge">Ocupado</span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?= e(label($space['status'])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a class="btn-action btn-edit" href="lots.php?lot_id=<?= $selectedLotId ?>&edit_space=<?= (int) $space['id'] ?>">Editar</a>
                                    <form method="post" onsubmit="return confirm('¿Eliminar este lugar?');">
                                        <input type="hidden" name="action" value="delete_space">
                                        <input type="hidden" name="lot_id" value="<?= $selectedLotId ?>">
                                        <input type="hidden" name="id" value="<?= (int) $space['id'] ?>">
                                        <button type="submit" class="btn-action btn-delete">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($spaces)): ?>
                        <tr><td colspan="4" class="muted">Sin lugares cargados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
<?php endif; ?>
