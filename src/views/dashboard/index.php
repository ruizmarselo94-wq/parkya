<h1>Dashboard</h1>

<div class="kpi-grid">
    <div class="kpi">
        <span class="kpi-value"><?= count($lots) ?></span>
        <span class="kpi-label">Estacionamientos</span>
    </div>
    <div class="kpi">
        <span class="kpi-value kpi-error"><?= (int) $totalOccupied ?></span>
        <span class="kpi-label">Ocupados ahora</span>
    </div>
    <div class="kpi">
        <span class="kpi-value kpi-success"><?= (int) $totalFree ?></span>
        <span class="kpi-label">Lugares libres</span>
    </div>
    <div class="kpi">
        <span class="kpi-value"><?= (int) $totalSpaces ?></span>
        <span class="kpi-label">Capacidad total</span>
    </div>
</div>

<section class="stat-grid">
    <?php foreach ($lots as $lot): ?>
        <div class="card">
            <h3><?= e($lot['name']) ?></h3>
            <p class="muted"><?= e($lot['address']) ?></p>
            <p>
                <strong><?= (int) $lot['occupied_spaces'] ?></strong> ocupados /
                <strong><?= (int) $lot['free_spaces'] ?></strong> libres /
                <?= (int) $lot['total_spaces'] ?> total
            </p>
        </div>
    <?php endforeach; ?>
    <?php if (empty($lots)): ?>
        <p class="muted">No hay estacionamientos cargados todavía.</p>
    <?php endif; ?>
</section>

<div class="card">
    <h2>Sesiones activas</h2>
    <div class="table-wrap">
    <table class="table">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Estacionamiento</th>
                <th>Lugar</th>
                <th>Entrada</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activeSessions as $s): ?>
                <tr>
                    <td><?= e($s['plate']) ?></td>
                    <td><?= e($s['lot_name']) ?></td>
                    <td><?= e($s['space_code']) ?></td>
                    <td><?= e(formatDate($s['entry_time'])) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($activeSessions)): ?>
                <tr><td colspan="4" class="muted">No hay vehículos estacionados ahora mismo.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
