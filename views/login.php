<?php include __DIR__ . '/header.php'; ?>

<section class="auth-wrap">
    <form class="card auth-card glass-card" method="post" action="<?= url('login') ?>">
        <span class="badge badge-soft">Bienvenido de vuelta</span>
        <h1>Iniciar sesión</h1>
        <p class="muted">Entra para seguir ayudando a encontrar hogares.</p>

        <label>Usuario o correo</label>
        <input type="text" name="usuario" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button class="btn" type="submit">Ingresar</button>
        <p class="muted">¿No tienes cuenta? <a href="<?= url('registro') ?>">Regístrate aquí</a></p>
    </form>
</section>

<?php include __DIR__ . '/footer.php'; ?>