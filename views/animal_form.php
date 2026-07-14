<?php
$animal = $animal ?? [];
$especies = $especies ?? [];
$razas = $razas ?? [];
include __DIR__ . '/header.php';
?>

<section class="section">
    <div class="card">
        <div class="section-head" style="margin-bottom:18px;">
            <div>
                <span class="badge badge-soft">Gestión de animales</span>
                <h1 style="margin:10px 0 6px;">
                    <?= !empty($animal['id']) ? 'Editar animal' : 'Registrar animal' ?>
                </h1>
                <p class="muted" style="margin:0;">
                    Desde aquí puedes modificar todos los datos del animal rescatado.
                </p>
            </div>
        </div>

        <form method="post" action="index.php?action=guardar_animal" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= h($animal['id'] ?? '') ?>">

            <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:14px;">
                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Nombre</label>
                    <input type="text" name="nombre" value="<?= h($animal['nombre'] ?? '') ?>" required>
                </div>

                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Especie</label>
                    <select name="especie_id" required>
                        <option value="">Seleccione especie</option>
                        <?php foreach ($especies as $e): ?>
                            <option value="<?= (int)$e['id'] ?>" <?= ((int)($animal['especie_id'] ?? 0) === (int)$e['id']) ? 'selected' : '' ?>>
                                <?= h($e['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Raza</label>
                    <select name="raza_id">
                        <option value="">Seleccione raza</option>
                        <?php foreach ($razas as $r): ?>
                            <option value="<?= (int)$r['id'] ?>" <?= ((int)($animal['raza_id'] ?? 0) === (int)$r['id']) ? 'selected' : '' ?>>
                                <?= h($r['nombre']) ?> · <?= h($r['especie_nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Sexo</label>
                    <select name="sexo" required>
                        <option value="Macho" <?= (($animal['sexo'] ?? '') === 'Macho') ? 'selected' : '' ?>>Macho</option>
                        <option value="Hembra" <?= (($animal['sexo'] ?? '') === 'Hembra') ? 'selected' : '' ?>>Hembra</option>
                    </select>
                </div>

                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Edad en meses</label>
                    <input type="number" name="edad_meses" value="<?= h($animal['edad_meses'] ?? '') ?>" required>
                </div>

                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Tamaño</label>
                    <select name="tamano" required>
                        <option value="Pequeño" <?= (($animal['tamano'] ?? '') === 'Pequeño') ? 'selected' : '' ?>>Pequeño</option>
                        <option value="Mediano" <?= (($animal['tamano'] ?? '') === 'Mediano') ? 'selected' : '' ?>>Mediano</option>
                        <option value="Grande" <?= (($animal['tamano'] ?? '') === 'Grande') ? 'selected' : '' ?>>Grande</option>
                    </select>
                </div>

                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Color</label>
                    <input type="text" name="color" value="<?= h($animal['color'] ?? '') ?>">
                </div>

                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Fecha de rescate</label>
                    <input type="date" name="fecha_rescate" value="<?= h(!empty($animal['fecha_rescate']) ? date('Y-m-d', strtotime((string)$animal['fecha_rescate'])) : '') ?>">
                </div>

                <div style="grid-column:1 / -1;">
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Estado de salud</label>
                    <input type="text" name="estado_salud" value="<?= h($animal['estado_salud'] ?? '') ?>">
                </div>
            </div>

            <div style="margin-top:18px; padding:16px; border:1px solid var(--border); border-radius:18px; background:#fff;">
                <h3 style="margin-top:0;">Imagen del animal</h3>

                <?php if (!empty($animal['imagen'])): ?>
                    <div style="margin-bottom:14px;">
                        <p class="muted" style="margin:0 0 10px;">Imagen actual:</p>
                        <img
                            src="<?= animal_image($animal['imagen']) ?>"
                            alt="<?= h($animal['nombre'] ?? 'Animal') ?>"
                            style="max-width:260px; width:100%; border-radius:18px; border:1px solid var(--border); object-fit:cover;"
                        >
                    </div>
                <?php else: ?>
                    <p class="muted">Este animal todavía no tiene imagen.</p>
                <?php endif; ?>

                <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:14px;">
                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:700;">URL directa de imagen</label>
                        <input type="text" name="imagen_url" placeholder="https://.../foto.jpg" value="<?= h($animal['imagen'] ?? '') ?>">
                    </div>

                    <div>
                        <label style="display:block; margin-bottom:8px; font-weight:700;">Subir imagen desde el ordenador</label>
                        <input type="file" name="imagen_file" accept="image/*">
                    </div>
                </div>
            </div>

            <div style="margin-top:18px; display:grid; grid-template-columns:repeat(2,1fr); gap:14px;">
                <label class="checkline" style="margin:0;">
                    <input type="checkbox" name="esterilizado" <?= !empty($animal['esterilizado']) ? 'checked' : '' ?>>
                    Esterilizado
                </label>

                <label class="checkline" style="margin:0;">
                    <input type="checkbox" name="vacunado" <?= !empty($animal['vacunado']) ? 'checked' : '' ?>>
                    Vacunado
                </label>

                <label class="checkline" style="margin:0;">
                    <input type="checkbox" name="publicado" <?= !empty($animal['publicado']) ? 'checked' : '' ?>>
                    Publicado
                </label>

                <div>
                    <label style="display:block; margin-bottom:8px; font-weight:700;">Estado</label>
                    <select name="estado" required>
                        <option value="Disponible" <?= (($animal['estado'] ?? '') === 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                        <option value="En evaluación" <?= (($animal['estado'] ?? '') === 'En evaluación') ? 'selected' : '' ?>>En evaluación</option>
                        <option value="Adoptado" <?= (($animal['estado'] ?? '') === 'Adoptado') ? 'selected' : '' ?>>Adoptado</option>
                    </select>
                </div>
            </div>

            <div style="margin-top:18px;">
                <label style="display:block; margin-bottom:8px; font-weight:700;">Descripción</label>
                <textarea name="descripcion" required style="min-height:130px;"><?= h($animal['descripcion'] ?? '') ?></textarea>
            </div>

            <div style="margin-top:18px;">
                <label style="display:block; margin-bottom:8px; font-weight:700;">Historia de rescate</label>
                <textarea name="historia_rescate" style="min-height:130px;"><?= h($animal['historia_rescate'] ?? '') ?></textarea>
            </div>

            <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:20px;">
                <button class="btn" type="submit">Guardar cambios</button>
                <a class="btn btn-outline" href="index.php?action=admin_animales">Volver</a>
            </div>
        </form>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>