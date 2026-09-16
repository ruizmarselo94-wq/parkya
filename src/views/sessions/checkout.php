<h1>Registrar salida</h1>

<?php if ($selected): ?>
    <div class="card card-narrow">
        <h2><?= e($selected['plate']) ?></h2>
        <p><?= e($selected['lot_name']) ?> — Lugar <?= e($selected['space_code']) ?></p>
        <p>Entrada: <?= e($selected['entry_time']) ?></p>

        <?php if ($costError): ?>
            <p class="alert alert-error"><?= e($costError) ?></p>
            <a href="checkout.php">Volver</a>
        <?php else: ?>
            <p class="total">Total a cobrar: <strong><?= money($estimatedCost) ?> Gs.</strong></p>
            <form method="post">
                <input type="hidden" name="session_id" value="<?= (int) $selected['id'] ?>">
                <label>Método de pago
                    <select name="method" required>
                        <option value="cash">Efectivo</option>
                        <option value="card">Tarjeta</option>
                        <option value="pos">POS</option>
                        <option value="transfer">Transferencia</option>
                    </select>
                </label>
                <label>Referencia (opcional) <input type="text" name="reference"></label>
                <button type="submit">Confirmar cobro</button>
            </form>
        <?php endif; ?>
    </div>
<?php endif; ?>

<h2>Vehículos estacionados</h2>
<div class="table-wrap">
<table class="table">
    <thead>
        <tr>
            <th>Placa</th>
            <th>Estacionamiento</th>
            <th>Lugar</th>
            <th>Entrada</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($activeSessions as $s): ?>
            <tr>
                <td><?= e($s['plate']) ?></td>
                <td><?= e($s['lot_name']) ?></td>
                <td><?= e($s['space_code']) ?></td>
                <td><?= e($s['entry_time']) ?></td>
                <td><a href="checkout.php?session_id=<?= (int) $s['id'] ?>">Cobrar</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($activeSessions)): ?>
            <tr><td colspan="5" class="muted">No hay vehículos estacionados ahora mismo.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
