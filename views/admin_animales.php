<?php
$animales = $animales ?? [];
include __DIR__ . '/header.php';
?>

<section class="section">
    <div class="section-head">
        <div>
            <h1>Animales registrados</h1>
            <p class="muted">Gestiona publicación, edición y estado de cada animal.</p>
        </div>
        <a class="btn" href="<?= url('admin_nuevo_animal') ?>">Nuevo animal</a>
    </div>

    <div class="card table-card">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Especie</th>
                    <th>Estado</th>
                    <th>Publicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($animales as $a): ?>
                <tr>
                    <td>
                        <img class="thumb" src="<?= animal_image($a['imagen'] ?? null) ?>" alt="<?= h($a['nombre'] ?? '') ?>">
                    </td>
                    <td><?= h($a['nombre'] ?? '') ?></td>
                    <td><?= h($a['especie_nombre'] ?? '') ?></td>
                    <td><?= h($a['estado'] ?? '') ?></td>
                    <td><?= !empty($a['publicado']) ? 'Publicado' : 'Oculto' ?></td>
                    <td class="actions-inline">
                        <a href="<?= url('admin_editar_animal', ['id' => $a['id'] ?? 0]) ?>">Editar</a>
                        <a href="<?= url('publicar_animal', ['id' => $a['id'] ?? 0, 'publicado' => !empty($a['publicado']) ? 0 : 1]) ?>">
                            <?= !empty($a['publicado']) ? 'Ocultar' : 'Publicar' ?>
                        </a>
                        <a href="<?= url('eliminar_animal', ['id' => $a['id'] ?? 0]) ?>" onclick="return confirm('¿Eliminar animal?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>