<?php
$solicitudes = $solicitudes ?? [];
include __DIR__ . '/header.php';
?>

<section class="section">
    <div class="section-head">
        <div>
            <h1>Solicitudes de adopción</h1>
            <p class="muted">Revisa, aprueba o rechaza cada solicitud.</p>
        </div>
    </div>

    <div class="stack">
        <?php foreach ($solicitudes as $s): ?>
            <article class="card request-card hover-card">
                <div class="request-left">
                    <div class="request-title">
                        <h3><?= h($s['animal_nombre'] ?? '') ?></h3>
                        <span class="badge"><?= h($s['estado_solicitud'] ?? '') ?></span>
                    </div>
                    <p><?= h($s['solicitante_nombre'] ?? '') ?> · <?= h($s['solicitante_email'] ?? '') ?></p>
                    <p class="muted"><?= nl2br(h($s['motivo'] ?? '')) ?></p>
                </div>

                <div class="actions-inline request-actions">
                    <?php if (($s['estado_solicitud'] ?? '') === 'Pendiente'): ?>
                        <form method="post" action="<?= url('aprobar_solicitud', ['id' => $s['id'] ?? 0]) ?>">
                            <input type="hidden" name="respuesta" value="Solicitud aprobada por el administrador.">
                            <button class="btn btn-sm" type="submit">Aprobar</button>
                        </form>

                        <form method="post" action="<?= url('rechazar_solicitud', ['id' => $s['id'] ?? 0]) ?>">
                            <input type="hidden" name="respuesta" value="Solicitud rechazada por evaluación administrativa.">
                            <button class="btn btn-outline btn-sm" type="submit">Rechazar</button>
                        </form>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>

        <?php if (empty($solicitudes)): ?>
            <div class="card empty-state">
                <h3>No hay solicitudes</h3>
                <p>Cuando un usuario envíe una solicitud, aparecerá aquí para revisión.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>