<?php
$usuarios = $usuarios ?? [];
$roles = $roles ?? [];
include __DIR__ . '/header.php';
?>

<section class="section">
    <div class="section-head">
        <div>
            <h1>Gestión de usuarios</h1>
            <p class="muted">Administra permisos y estado de acceso.</p>
        </div>
    </div>

    <div class="card table-card">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= h(($u['nombres'] ?? '') . ' ' . ($u['apellidos'] ?? '')) ?></td>
                    <td><?= h($u['usuario'] ?? '') ?></td>
                    <td><?= h($u['email'] ?? '') ?></td>
                    <td><?= h($u['rol_nombre'] ?? '') ?></td>
                    <td><?= h($u['estado'] ?? '') ?></td>
                    <td>
                        <form method="post" action="<?= url('guardar_usuario') ?>" class="inline-form">
                            <input type="hidden" name="id" value="<?= h($u['id'] ?? 0) ?>">

                            <select name="rol_id">
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>" <?= ((int)($u['rol_id'] ?? 0) === (int)$r['id']) ? 'selected' : '' ?>>
                                        <?= h($r['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select name="estado">
                                <option value="Activo" <?= (($u['estado'] ?? '') === 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= (($u['estado'] ?? '') === 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>

                            <button class="btn btn-sm" type="submit">Guardar</button>

                            <?php if ((int)($u['id'] ?? 0) !== (int)current_user()['id']): ?>
                                <a class="btn btn-outline btn-sm" href="<?= url('desactivar_usuario', ['id' => $u['id'] ?? 0]) ?>" onclick="return confirm('¿Desactivar usuario?')">
                                    Desactivar
                                </a>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>