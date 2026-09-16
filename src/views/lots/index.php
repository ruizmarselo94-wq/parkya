<h1>Estacionamientos</h1>

<div class="grid">
    <div class="card">
        <h2>Nuevo estacionamiento</h2>
        <form method="post">
            <input type="hidden" name="action" value="create_lot">
            <label>Nombre <input type="text" name="name" required></label>
            <label>Dirección <input type="text" name="address" required></label>
            <button type="submit">Crear</button>
        </form>
    </div>

    <div class="card">
        <h2>Listado</h2>
        <ul class="plain-list">
            <?php foreach ($lots as $lot): ?>
                <li>
                    <a href="lots.php?lot_id=<?= (int) $lot['id'] ?>"><?= e($lot['name']) ?></a>
                    <span class="muted"><?= e($lot['address']) ?></span>
                    <?= $lot['is_active'] ? '' : '<span class="badge">inactivo</span>' ?>
                </li>
            <?php endforeach; ?>
            <?php if (empty($lots)): ?>
                <li class="muted">Todavía no hay estacionamientos.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<?php if ($selectedLotId > 0): ?>
    <h2>Lugares</h2>
    <div class="grid">
        <div class="card">
            <h3>Nuevo lugar</h3>
            <form method="post">
                <input type="hidden" name="action" value="create_space">
                <input type="hidden" name="lot_id" value="<?= $selectedLotId ?>">
                <label>Código <input type="text" name="code" required placeholder="A1"></label>
                <label>Tipo
                    <select name="type">
                        <option value="standard">Estándar</option>
                        <option value="handicap">Discapacidad</option>
                        <option value="compact">Compacto</option>
                    </select>
                </label>
                <button type="submit">Agregar lugar</button>
            </form>
        </div>

        <div class="card">
            <table class="table">
                <thead><tr><th>Código</th><th>Tipo</th><th>Estado</th></tr></thead>
                <tbody>
                    <?php foreach ($spaces as $space): ?>
                        <tr>
                            <td><?= e($space['code']) ?></td>
                            <td><?= e($space['type']) ?></td>
                            <td><?= $space['is_occupied'] ? 'Ocupado' : e($space['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($spaces)): ?>
                        <tr><td colspan="3" class="muted">Sin lugares cargados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
