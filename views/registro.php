<?php include __DIR__ . '/header.php'; ?>

<section class="auth-wrap">
    <form class="card auth-card wide glass-card" method="post" action="<?= url('registro') ?>">
        <span class="badge badge-soft">Nueva familia</span>
        <h1>Crear cuenta</h1>
        <p class="muted">Un paso para empezar a adoptar con responsabilidad.</p>

        <div class="form-grid two">
            <input type="text" name="nombres" placeholder="Nombres" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="text" name="dni" placeholder="DNI" required>
            <input type="text" name="telefono" placeholder="Teléfono">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="email" name="email" placeholder="Correo" required>
            <input type="text" name="direccion" placeholder="Dirección">
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
        </div>

        <button class="btn" type="submit">Registrar</button>
        <p class="muted">¿Ya tienes cuenta? <a href="<?= url('login') ?>">Inicia sesión</a></p>
    </form>
</section>

<?php include __DIR__ . '/footer.php'; ?>