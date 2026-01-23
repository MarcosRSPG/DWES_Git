<?php require RUTA_APP.'/vistas/inc/header.php'; ?>

<section class="card">
  <h2><?= h((string)($datos['titulo'] ?? 'Mensaje')) ?></h2>
  <p><?= h((string)($datos['mensaje'] ?? '')) ?></p>

  <?php if (!empty($datos['linkHref'])): ?>
    <p><a class="btn" href="<?= h(RUTA_URL) ?><?= h((string)$datos['linkHref']) ?>"><?= h((string)($datos['linkTexto'] ?? 'Volver')) ?></a></p>
  <?php endif; ?>
</section>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>
