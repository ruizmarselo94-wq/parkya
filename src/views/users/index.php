<?php
// Variables que define el controlador antes de incluir esta vista
/** @var array|null $editUser */
/** @var array $users */
?>
<h1>Usuarios</h1>

<div class="stack">
    <div class="card">
        <h2><?= $editUser ? 'Editar usuario' : 'Nuevo usuario' ?></h2>
        <form method="post" class="fields fields-3">
            <?php if ($editUser): ?>
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= (int) $editUser['id'] ?>">
                <label>Usuario <input type="text" value="<?= e($editUser['username']) ?>" disabled></label>
                <label>Contraseña (dejar en blanco para no cambiar)
                    <input type="password" name="password" minlength="6">
                </label>
            <?php else: ?>
                <input type="hidden" name="action" value="create">
                <label>Usuario <input type="text" name="username" required></label>
                <label>Contraseña <input type="password" name="password" required minlength="6"></label>
            <?php endif; ?>
            <label>Nombre completo
                <input type="text" name="full_name" required value="<?= e($editUser['full_name'] ?? '') ?>">
            </label>
            <label>Email
                <input type="email" name="email" required value="<?= e($editUser['email'] ?? '') ?>">
            </label>
            <label>Teléfono <input type="text" name="phone" value="<?= e($editUser['phone'] ?? '') ?>"></label>
            <label>Rol
                <select name="role">
                    <?php foreach (['operator' => 'Operador', 'cashier' => 'Cajero', 'admin' => 'Administrador'] as $value => $roleLabel): ?>
                        <option value="<?= $value ?>" <?= ($editUser['role'] ?? 'operator') === $value ? 'selected' : '' ?>>
                            <?= $roleLabel ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit"><?= $editUser ? 'Guardar cambios' : 'Crear' ?></button>
        </form>
        <?php if ($editUser): ?>
            <p><a href="users.php">Cancelar edición</a></p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Usuarios registrados</h2>
        <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Usuario</th><th>Nombre</th><th>Rol</th><th>Estado</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= e($u['username']) ?></td>
                        <td><?= e($u['full_name']) ?></td>
                        <td><?= e(label($u['role'])) ?></td>
                        <td>
                            <?php if ($u['is_active']): ?>
                                <span class="badge badge-success">Activo</span>
                            <?php else: ?>
                                <span class="badge badge-muted">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="row-actions">
                                <a class="btn-action btn-edit" href="users.php?edit_id=<?= (int) $u['id'] ?>">Editar</a>
                                <form method="post" onsubmit="return confirm('¿Eliminar este usuario?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                    <button type="submit" class="btn-action btn-delete">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
