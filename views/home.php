<?php
$featured = $featured ?? [];
$especies = $especies ?? [];
include __DIR__ . '/header.php';
?>

<section class="hero card hero-anim">
    <div class="hero-copy">
        <span class="badge badge-soft">Cada adopción cambia una vida</span>
        <h1>Encuentra un amigo para toda la vida.</h1>
        <p>
            Una plataforma cálida y clara para conocer animales rescatados, revisar su historia
            y dar el paso hacia una adopción responsable.
        </p>

        <div class="actions">
            <a class="btn" href="index.php?action=animales">Ver animales</a>
            <?php if (!is_logged_in()): ?>
                <a class="btn btn-outline" href="index.php?action=registro">Quiero adoptar</a>
            <?php endif; ?>
        </div>

        <div class="mini-stats">
            <div><strong><?= count($featured) ?></strong><span>destacados</span></div>
            <div><strong>Amor</strong><span>en cada hogar</span></div>
            <div><strong>Buscan</strong><span>Una familia</span></div>
        </div>
    </div>

    <div class="hero-visual">
        <div class="bubble bubble-a"></div>
        <div class="bubble bubble-b"></div>
        <div class="bubble bubble-c"></div>
        <img src="<?= APP_BASE ?>/public/images/57d77e153d21aa700324afcda6f98352.jpg" alt="Adopción" class="hero-art">
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <h2>Explora por especie</h2>
            <p class="muted">Un vistazo rápido a los animales que esperan hogar.</p>
        </div>
        <a href="index.php?action=animales">Ver catálogo completo</a>
    </div>

    <div class="chip-grid">
        <?php foreach ($especies as $e): ?>
            <a class="chip" href="index.php?action=animales&especie_id=<?= (int)$e['id'] ?>">
                <span class="chip-icon"><?= $e['nombre'] === 'Perro' ? '🐶' : ($e['nombre'] === 'Gato' ? '🐱' : '🐰') ?></span>
                <span><?= h($e['nombre']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <h2>Animales destacados</h2>
            <p class="muted">Rescatados, cuidados y listos para ser amados.</p>
        </div>
    </div>

    <div class="grid cards">
        <?php foreach ($featured as $a): ?>
            <article class="card animal-card hover-card">
                <div class="animal-image-wrap">
                    <img src="<?= animal_image($a['imagen'] ?? null) ?>" alt="<?= h($a['nombre'] ?? 'Animal') ?>">
                </div>

                <div class="card-body">
                    <div class="card-top">
                        <span class="badge"><?= h($a['estado'] ?? 'Disponible') ?></span>
                        <span class="muted tiny"><?= h($a['sexo'] ?? '') ?></span>
                    </div>

                    <h3><?= h($a['nombre'] ?? '') ?></h3>
                    <p><?= h($a['especie_nombre'] ?? '') ?><?= !empty($a['raza_nombre']) ? ' · ' . h($a['raza_nombre']) : '' ?></p>
                    <p class="muted"><?= h($a['tamano'] ?? '') ?> · <?= (int)($a['edad_meses'] ?? 0) ?> meses</p>
                    <a class="btn btn-sm" href="index.php?action=detalle&id=<?= (int)($a['id'] ?? 0) ?>">Ver detalle</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="info-banner card">
        <div>
            <h2>Adoptar es dar una segunda oportunidad.</h2>
            <p class="muted">
                Tu apoyo ayuda a que más animales rescatados encuentren una familia responsable y amorosa.
            </p>
        </div>
        <div class="info-banner-list">
            <div>🐾 Revisa su historia</div>
            <div>💛 Envía tu solicitud</div>
            <div>🏡 Cambia su vida</div>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <h2>Cómo funciona</h2>
    </div>

    <div class="steps-grid">
        <article class="card step-card hover-card">
            <span class="step-number">1</span>
            <h3>Explora</h3>
            <p>Revisa los animales disponibles y conoce sus características.</p>
        </article>

        <article class="card step-card hover-card">
            <span class="step-number">2</span>
            <h3>Solicita</h3>
            <p>Completa el formulario de adopción con información responsable.</p>
        </article>

        <article class="card step-card hover-card">
            <span class="step-number">3</span>
            <h3>Recibe respuesta</h3>
            <p>El administrador revisa tu solicitud y aprueba o rechaza.</p>
        </article>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>