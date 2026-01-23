<?php require RUTA_APP.'/vistas/inc/header.php'; ?>

<section class="card">
  <h2><?= h((string)($datos['titulo'] ?? 'Productos')) ?></h2>

  <p>
    <a href="<?= h(RUTA_URL) ?>/Categoria/categorias">← Volver a categorías</a>
  </p>

  <?php if (empty($datos['productos'])): ?>
    <p>No hay productos en esta categoría.</p>
  <?php else: ?>
    <table class="table">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Peso</th>
          <th>Stock</th>
          <th>Unidades</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($datos['productos'] as $p): ?>
          <?php
            $pk = (string)($p['CodProd'] ?? '');
            $stock = (int)($p['Stock'] ?? 0);
          ?>
          <tr>
            <td><?= h((string)($p['Nombre'] ?? '')) ?></td>
            <td><?= h((string)($p['Descripcion'] ?? '')) ?></td>
            <td class="num"><?= h((string)($p['Peso'] ?? '0')) ?></td>
            <td class="num"><?= $stock ?></td>
            <td>
              <form method="post" action="<?= h(RUTA_URL) ?>/Carrito/agregar" class="inline">
                <input type="hidden" name="pk" value="<?= h($pk) ?>">
                <input type="number" name="unidades" min="1" max="<?= $stock ?>" value="1" required <?= $stock<=0?'disabled':'' ?>>
            </td>
            <td>
                <button type="submit" <?= $stock<=0?'disabled':'' ?>>Añadir</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>
