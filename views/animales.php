<?php
$animales = $animales ?? [];
$especies = $especies ?? [];
include __DIR__ . '/header.php';
?>

<section class="page-hero card hero-anim">
    <div>
        <span class="badge badge-soft">Catálogo de adopción</span>
        <h1>Animales que esperan una familia.</h1>
        <p class="muted">Filtra por especie, sexo, tamaño y edad para encontrar al compañero ideal.</p>
    </div>
    <div class="page-hero-art">
        <img src="<?= APP_BASE ?>/public/images/placeholder.svg" alt="Adopción">
    </div>
</section>

<section class="section">
    <form class="card filter-form" method="get" action="index.php">
        <input type="hidden" name="action" value="animales">

        <input type="text" name="buscar" placeholder="Buscar por nombre o especie" value="<?= h($_GET['buscar'] ?? '') ?>">

        <select name="especie_id">
            <option value="">Todas las especies</option>
            <?php foreach ($especies as $e): ?>
                <option value="<?= (int)$e['id'] ?>" <?= (($_GET['especie_id'] ?? '') == $e['id']) ? 'selected' : '' ?>>
                    <?= h($e['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="sexo">
            <option value="">Sexo</option>
            <option value="Macho" <?= (($_GET['sexo'] ?? '') === 'Macho') ? 'selected' : '' ?>>Macho</option>
            <option value="Hembra" <?= (($_GET['sexo'] ?? '') === 'Hembra') ? 'selected' : '' ?>>Hembra</option>
        </select>

        <select name="tamano">
            <option value="">Tamaño</option>
            <option value="Pequeño" <?= (($_GET['tamano'] ?? '') === 'Pequeño') ? 'selected' : '' ?>>Pequeño</option>
            <option value="Mediano" <?= (($_GET['tamano'] ?? '') === 'Mediano') ? 'selected' : '' ?>>Mediano</option>
            <option value="Grande" <?= (($_GET['tamano'] ?? '') === 'Grande') ? 'selected' : '' ?>>Grande</option>
        </select>

        <select name="estado">
            <option value="">Estado</option>
            <option value="Disponible" <?= (($_GET['estado'] ?? '') === 'Disponible') ? 'selected' : '' ?>>Disponible</option>
            <option value="En evaluación" <?= (($_GET['estado'] ?? '') === 'En evaluación') ? 'selected' : '' ?>>En evaluación</option>
        </select>

        <input type="number" name="edad_min" placeholder="Edad mínima" value="<?= h($_GET['edad_min'] ?? '') ?>">
        <input type="number" name="edad_max" placeholder="Edad máxima" value="<?= h($_GET['edad_max'] ?? '') ?>">

        <button class="btn" type="submit">Filtrar</button>
    </form>
</section>

<section class="section">
    <div class="grid cards">
        <?php foreach ($animales as $a): ?>
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

        <?php if (empty($animales)): ?>
            <div class="card empty-state">
                <h3>No hay resultados</h3>
                <p>Prueba con otros filtros para encontrar más animales disponibles.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>