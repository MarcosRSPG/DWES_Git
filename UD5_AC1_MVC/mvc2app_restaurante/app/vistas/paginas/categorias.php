<?php require RUTA_APP.'/vistas/inc/header.php'; ?>

<section class="card">
  <h2>Categorías</h2>

  <?php if (empty($datos['categorias'])): ?>
    <p>No hay categorías.</p>
  <?php else: ?>
    <ul class="list">
      <?php foreach ($datos['categorias'] as $c): ?>
        <?php $id = (string)($c['CodCat'] ?? ''); $nombre = (string)($c['Nombre'] ?? $id); ?>
        <li>
          <a href="<?= h(RUTA_URL) ?>/Categoria/listar/<?= h(urlencode($id)) ?>">
            <?= h($nombre) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</section>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>
