<?php
// Variables que define el controlador antes de incluir esta vista
/** @var array $spaces */
?>
<h1>Registrar entrada</h1>

<form method="post" class="card">
    <fieldset class="fields">
        <legend>Vehículo</legend>
        <label>Placa
            <input type="text" name="plate" required maxlength="10" style="text-transform: uppercase">
        </label>
        <label>Tipo
            <select name="type">
                <option value="car">Auto</option>
                <option value="motorcycle">Moto</option>
                <option value="van">Camioneta</option>
            </select>
        </label>
        <label>Marca <input type="text" name="brand"></label>
        <label>Modelo <input type="text" name="model"></label>
        <label>Color <input type="text" name="color"></label>
    </fieldset>

    <fieldset class="fields">
        <legend>Cliente (si la placa es nueva)</legend>
        <label>Nombre completo <input type="text" name="customer_full_name"></label>
        <label>Teléfono <input type="text" name="customer_phone"></label>
        <label>Email <input type="email" name="customer_email"></label>
        <label>Documento <input type="text" name="customer_document"></label>
    </fieldset>

    <fieldset class="fields">
        <legend>Lugar disponible</legend>
        <?php if (empty($spaces)): ?>
            <p class="muted">No hay lugares disponibles en ningún estacionamiento.</p>
        <?php endif; ?>
        <?php foreach ($spaces as $space): ?>
            <label class="radio">
                <input type="radio" name="space_id" value="<?= (int) $space['id'] ?>" required>
                <?= e($space['lot_name']) ?> — Lugar <?= e($space['code']) ?> (<?= e($space['type']) ?>)
            </label>
        <?php endforeach; ?>
    </fieldset>

    <button type="submit">Registrar entrada</button>
</form>
