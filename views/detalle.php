<?php
$animal = $animal ?? [];
include __DIR__ . '/header.php';
?>

<section class="detail-grid">
    <div class="card hover-card">
        <img class="detail-image" src="<?= animal_image($animal['imagen'] ?? null) ?>" alt="<?= h($animal['nombre'] ?? 'Animal') ?>">
    </div>

    <div class="card detail-info">
        <span class="badge"><?= h($animal['estado'] ?? 'Disponible') ?></span>
        <h1><?= h($animal['nombre'] ?? '') ?></h1>
        <p class="muted"><?= h($animal['especie_nombre'] ?? '') ?><?= !empty($animal['raza_nombre']) ? ' · ' . h($animal['raza_nombre']) : '' ?></p>

        <div class="info-grid">
            <div><strong>Sexo</strong><span><?= h($animal['sexo'] ?? '') ?></span></div>
            <div><strong>Edad</strong><span><?= (int)($animal['edad_meses'] ?? 0) ?> meses</span></div>
            <div><strong>Tamaño</strong><span><?= h($animal['tamano'] ?? '') ?></span></div>
            <div><strong>Vacunado</strong><span><?= !empty($animal['vacunado']) ? 'Sí' : 'No' ?></span></div>
            <div><strong>Esterilizado</strong><span><?= !empty($animal['esterilizado']) ? 'Sí' : 'No' ?></span></div>
            <div><strong>Rescatado</strong><span><?= date_fmt($animal['fecha_rescate'] ?? '') ?></span></div>
        </div>

        <h3>Descripción</h3>
        <p><?= nl2br(h($animal['descripcion'] ?? '')) ?></p>

        <h3>Historia de rescate</h3>
        <p><?= nl2br(h($animal['historia_rescate'] ?? '')) ?></p>

        <?php if (!empty($animal['publicado']) && (($animal['estado'] ?? '') === 'Disponible')): ?>
            <a class="btn" href="index.php?action=adopcion&id=<?= (int)($animal['id'] ?? 0) ?>">Solicitar adopción</a>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>