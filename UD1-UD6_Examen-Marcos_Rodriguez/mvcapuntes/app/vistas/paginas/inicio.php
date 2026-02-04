<?php
$veterinario = $_SESSION['veterinario_nombre'] ?? 'Usuario';
require RUTA_APP.'/vistas/inc/header.php';
?>

        <section class="card">
            <h1>Menú Principal</h1>
            
            <ul>
                <li><a href="<?php echo RUTA_URL; ?>/Mascota/mascotas">Mascotas</a></li>
                <li><a href="<?php echo RUTA_URL; ?>/Paginas/quienes">Quienes somos</a></li>
            </ul>
        </section>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>