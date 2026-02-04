<?php
$mascota = $datos['mascota'] ?? null;
if (!$mascota) {
    echo 'Error: Mascota no encontrada';
    exit;
}
$id = $mascota['id'];
require RUTA_APP.'/vistas/inc/header.php';
?>

        <section class="card">
            <h1><?php echo htmlspecialchars($mascota['nombre']); ?></h1>

            <p><strong>ID:</strong> <?php echo htmlspecialchars($mascota['id']); ?></p>
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($mascota['tipo']); ?></p>
            <p><strong>Fecha de nacimiento:</strong> <?php echo $mascota['fecha_nacimiento']; ?></p>
            <p><strong>ID Persona:</strong> <?php echo htmlspecialchars($mascota['id_persona']); ?></p>
            
            <?php if (!empty($mascota['foto_url'])) { ?>
                <p><strong>Foto:</strong></p>
                <img src="/UD1-UD6_Examen-Marcos_Rodriguez/mvcapuntes<?php echo htmlspecialchars($mascota['foto_url']); ?>" 
                     alt="<?php echo htmlspecialchars($mascota['nombre']); ?>" 
                     style="max-width: 300px; border-radius: 4px;">
            <?php } ?>
            
            <p style="margin-top: 2rem;">
                <a href="<?php echo RUTA_URL; ?>/Mascota/editar/<?php echo $id; ?>" class="btn btn-primary">Editar</a>
            </p>
        </section>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>
