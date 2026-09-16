<h1>Usuarios</h1>

<div class="grid">
    <div class="card">
        <h2>Nuevo usuario</h2>
        <form method="post">
            <label>Usuario <input type="text" name="username" required></label>
            <label>Contraseña <input type="password" name="password" required minlength="6"></label>
            <label>Nombre completo <input type="text" name="full_name" required></label>
            <label>Email <input type="email" name="email" required></label>
            <label>Teléfono <input type="text" name="phone"></label>
            <label>Rol
                <select name="role">
                    <option value="operator">Operador</option>
                    <option value="cashier">Cajero</option>
                    <option value="admin">Administrador</option>
                </select>
            </label>
            <button type="submit">Crear</button>
        </form>
    </div>

    <div class="card">
        <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Usuario</th><th>Nombre</th><th>Rol</th><th>Estado</th></tr></thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= e($u['username']) ?></td>
                        <td><?= e($u['full_name']) ?></td>
                        <td><?= e($u['role']) ?></td>
                        <td><?= $u['is_active'] ? 'Activo' : 'Inactivo' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
