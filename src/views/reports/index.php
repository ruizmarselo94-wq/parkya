<h1>Reportes</h1>

<div class="stack">
    <div class="card">
        <h2>Ocupación</h2>
        <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Estacionamiento</th><th>Ocupados</th><th>Libres</th><th>Total</th></tr></thead>
            <tbody>
                <?php foreach ($lots as $lot): ?>
                    <tr>
                        <td><?= e($lot['name']) ?></td>
                        <td><?= (int) $lot['occupied_spaces'] ?></td>
                        <td><?= (int) $lot['free_spaces'] ?></td>
                        <td><?= (int) $lot['total_spaces'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

    <div class="card">
        <h2>Ingresos</h2>
        <form method="get" class="inline-form">
            <label>Desde <input type="date" name="from" value="<?= e($from) ?>"></label>
            <label>Hasta <input type="date" name="to" value="<?= e($to) ?>"></label>
            <button type="submit">Filtrar</button>
        </form>

        <p class="total">Total del período: <strong><?= money($totalIncome) ?> Gs.</strong></p>

        <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Fecha</th><th>Método</th><th>Total</th></tr></thead>
            <tbody>
                <?php foreach ($income as $row): ?>
                    <tr>
                        <td><?= e($row['day']) ?></td>
                        <td><?= e(label($row['method'])) ?></td>
                        <td><?= money($row['total']) ?> Gs.</td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($income)): ?>
                    <tr><td colspan="3" class="muted">Sin pagos en este período.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
