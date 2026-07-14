<?php
$usuario = $usuario ?? [];
$misSolicitudes = $misSolicitudes ?? [];
include __DIR__ . '/header.php';
?>

<section class="section">
    <div class="section-head">
        <div>
            <h1>Mi perfil</h1>
            <p class="muted">Actualiza tus datos y revisa tus solicitudes.</p>
        </div>
    </div>

    <div class="profile-grid">
        <form class="card glass-card" method="post" action="<?= url('actualizar_perfil') ?>">
            <h2>Datos personales</h2>

            <div class="form-grid two">
                <input type="text" name="nombres" value="<?= h($usuario['nombres'] ?? '') ?>" required>
                <input type="text" name="apellidos" value="<?= h($usuario['apellidos'] ?? '') ?>" required>
                <input type="text" name="dni" value="<?= h($usuario['dni'] ?? '') ?>" required>
                <input type="text" name="telefono" value="<?= h($usuario['telefono'] ?? '') ?>">
                <input type="email" name="email" value="<?= h($usuario['email'] ?? '') ?>" required>
                <input type="text" name="direccion" value="<?= h($usuario['direccion'] ?? '') ?>">
            </div>

            <button class="btn" type="submit">Guardar cambios</button>
        </form>

        <div class="card glass-card">
            <h2>Mis solicitudes</h2>

            <?php if (!empty($misSolicitudes)): ?>
                <div class="stack">
                    <?php foreach ($misSolicitudes as $s): ?>
                        <article class="mini-card hover-card">
                            <strong><?= h($s['animal_nombre'] ?? '') ?></strong>
                            <p><?= h($s['especie_nombre'] ?? '') ?><?= !empty($s['raza_nombre']) ? ' · ' . h($s['raza_nombre']) : '' ?></p>
                            <p class="muted">Estado: <?= h($s['estado_solicitud'] ?? '') ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Aún no has enviado solicitudes.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>