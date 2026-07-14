<?php include __DIR__ . '/header.php'; ?>

<section class="section">
    <div class="section-head">
        <div>
            <span class="badge badge-soft">Panel administrativo</span>
            <h1>Gestiona adopciones, animales y usuarios.</h1>
            <p class="muted">Todo en un solo lugar, con acceso claro y ordenado.</p>
        </div>
    </div>

    <div class="admin-links">
        <a class="card admin-link hover-card" href="<?= url('admin_animales') ?>">
            <span>🐾</span>
            <strong>Animales</strong>
            <small>Registrar, editar y publicar</small>
        </a>

        <a class="card admin-link hover-card" href="<?= url('admin_solicitudes') ?>">
            <span>💌</span>
            <strong>Solicitudes</strong>
            <small>Aprobar o rechazar adopciones</small>
        </a>

        <a class="card admin-link hover-card" href="<?= url('admin_usuarios') ?>">
            <span>👥</span>
            <strong>Usuarios</strong>
            <small>Roles y estado de acceso</small>
        </a>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>