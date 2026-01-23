<?php require RUTA_APP.'/vistas/inc/header.php'; ?>

<section class="card">
  <h2>Carrito</h2>

  <?php if (!empty($datos['flash'])) { ?>
    <?php $f = $datos['flash']; ?>
    <p class="alert <?php echo ($f['type'] ?? '') === 'ok' ? 'ok' : 'error'; ?>">
      <?php echo (($f['type'] ?? '') === 'ok') ? '✅' : '❌'; ?> <?php echo h((string) ($f['msg'] ?? '')); ?>
    </p>
  <?php } ?>

  <?php if (empty($datos['carrito'])) { ?>
    <p>Carrito vacío.</p>
  <?php } else { ?>
    <table class="table">
      <thead>
        <tr>
          <th>Producto</th>
          <th>PK</th>
          <th>Peso</th>
          <th>Unidades</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php $totalU = 0;
      $totalP = 0.0; ?>
      <?php foreach ($datos['carrito'] as $pk => $linea) { ?>
        <?php
          /** @var MRS\Modelos\LineaCarrito $linea */
          $totalU += $linea->getUnidades();
          $totalP += $linea->totalPeso();
          ?>
        <tr>
          <td><?php echo h($linea->getNombre()); ?></td>
          <td><?php echo h($linea->getPk()); ?></td>
          <td class="num"><?php echo h((string) $linea->getPeso()); ?></td>
          <td class="num">
            <form method="post" action="<?php echo h(RUTA_URL); ?>/Carrito/actualizar" class="inline">
              <input type="hidden" name="pk" value="<?php echo h($linea->getPk()); ?>">
              <input type="number" name="unidades" min="0" value="<?php echo (int) $linea->getUnidades(); ?>">
          </td>
          <td>
              <button type="submit">Actualizar</button>
            </form>
          </td>
        </tr>
      <?php } ?>
      </tbody>
    </table>

    <p><strong>Total unidades:</strong> <?php echo (int) $totalU; ?> · <strong>Peso total:</strong> <?php echo h((string) $totalP); ?></p>

    <p>
      <a class="btn" href="<?php echo h(RUTA_URL); ?>/Pedido/crear">Generar pedido final</a>
    </p>
  <?php } ?>
</section>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>
