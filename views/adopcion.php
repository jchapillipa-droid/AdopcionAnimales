<?php
$animal = $animal ?? [];
include __DIR__ . '/header.php';
?>

<section class="section">
    <div class="card">
        <div class="section-head" style="margin-bottom:0;">
            <div>
                <span class="badge badge-soft">Formulario de adopción</span>
                <h1>Quiero adoptar a <?= h($animal['nombre'] ?? '') ?></h1>
                <p class="muted">Completa la información con honestidad y compromiso.</p>
            </div>
        </div>

        <div class="detail-grid" style="margin-top:18px;">
            <div class="card adopt-preview hover-card" style="box-shadow:none;">
                <img class="detail-image" src="<?= animal_image($animal['imagen'] ?? null) ?>" alt="<?= h($animal['nombre'] ?? '') ?>">
                <div style="margin-top:14px;">
                    <h2><?= h($animal['nombre'] ?? '') ?></h2>
                    <p><?= h($animal['especie_nombre'] ?? '') ?><?= !empty($animal['raza_nombre']) ? ' · ' . h($animal['raza_nombre']) : '' ?></p>
                </div>
            </div>

            <form class="card adopt-form" method="post" action="index.php?action=solicitar">
                <input type="hidden" name="animal_id" value="<?= (int)($animal['id'] ?? 0) ?>">

                <label>Motivo de adopción</label>
                <textarea name="motivo" required></textarea>

                <label>Experiencia con mascotas</label>
                <textarea name="experiencia"></textarea>

                <label>Tipo de vivienda</label>
                <input type="text" name="tipo_vivienda" required>

                <label>Miembros del hogar</label>
                <input type="number" name="miembros_hogar" min="1" required>

                <label class="checkline">
                    <input type="checkbox" name="acepta_compromiso" required>
                    Acepto cuidar responsablemente al animal.
                </label>

                <button class="btn" type="submit">Enviar solicitud</button>
                <a class="btn btn-outline" href="index.php?action=detalle&id=<?= (int)($animal['id'] ?? 0) ?>">Volver</a>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>