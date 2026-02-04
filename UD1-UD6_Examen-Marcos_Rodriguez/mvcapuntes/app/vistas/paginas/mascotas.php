<?php require RUTA_APP.'/vistas/inc/header.php'; ?>

        <section class="card">
            <h1>Listado de Mascotas</h1>

            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Fecha nacimiento</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos['mascotas'] as $m) { ?>
                        <?php
                        $id = $m['id'] ?? '';
                        $nombre = $m['nombre'] ?? '';
                        $tipo = $m['tipo'] ?? '';
                        $fechaNac = $m['fecha_nacimiento'] ?? '';
                        $fotoUrl = $m['foto_url'] ?? '';
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nombre); ?></td>
                            <td><?php echo htmlspecialchars($tipo); ?></td>
                            <td><?php echo $fechaNac; ?></td>
                            <td><img alt="<?php echo htmlspecialchars($nombre); ?>" src="/UD1-UD6_Examen-Marcos_Rodriguez/mvcapuntes<?php echo $fotoUrl; ?>"/></td>
                            <td>
                                <a href="<?php echo RUTA_URL; ?>/Mascota/ver/<?php echo $id; ?>" class="btn btn-secondary">Ver</a>
                                <a href="<?php echo RUTA_URL; ?>/Mascota/editar/<?php echo $id; ?>" class="btn btn-secondary">Editar</a>
                                <a href="<?php echo RUTA_URL; ?>/Mascota/eliminar/<?php echo $id; ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            
            <a href="<?php echo RUTA_URL; ?>/Mascota/crear" class="btn btn-primary">+ Nueva Mascota</a>
        </section>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>

