<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>

<?php
// Endpoint REST apuntando al servidor API (mvcapi)
$API_ARTICULOS_ENDPOINT = rtrim(API_BASE_URL, '/') . '/api/articulo';
?>

<h1><?php echo $datos['titulo'] ?? 'Ficha'; ?></h1>

<?php if (!empty($datos['error'])): ?>
    <pre>
Error HTTP <?php echo (int)$datos['http']; ?>:
<?php echo htmlspecialchars(json_encode($datos['error'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?>
  </pre>

    <p><a href="<?php echo rtrim(RUTA_URL,'/'); ?>/articulos/index">Volver al listado</a></p>

    <?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
    <?php return; ?>
<?php endif; ?>

<?php $articulo = $datos['articulo'] ?? []; ?>

<ul>
    <li><strong>ID:</strong> <?php echo htmlspecialchars($articulo['id_articulo'] ?? ''); ?></li>
    <li><strong>Título:</strong> <?php echo htmlspecialchars($articulo['titulo'] ?? ''); ?></li>
    <li><strong>Descripción:</strong> <?php echo htmlspecialchars($articulo['descripcion'] ?? ''); ?></li>
    <li><strong>Foto:</strong> <?php echo htmlspecialchars($articulo['foto'] ?? ''); ?></li>
    <li><strong>Cosa:</strong> <?php echo htmlspecialchars($articulo['cosa'] ?? ''); ?></li>
</ul>

<h3>JSON recibido</h3>
<pre><?php echo htmlspecialchars(json_encode($articulo, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?></pre>

<button id="btnBorrar">Borrar Articulo</button>

<p id="out"></p>

<p><a href="<?php echo rtrim(RUTA_URL,'/'); ?>/articulos/index">Volver al listado</a></p>

<script>
    const endpoint = <?php echo json_encode($API_ARTICULOS_ENDPOINT); ?>;
    const basicAuth = <?php echo json_encode('Basic ' . base64_encode(API_BASIC_USER . ':' . API_BASIC_PASS)); ?>;

    document.getElementById('btnBorrar').addEventListener('click', async (e) => {
        e.preventDefault();

        const out = document.getElementById('out');
        out.textContent = 'Enviando petición a la API...';

        const fd = new FormData();
        const payload = Object.fromEntries(fd.entries());

        try {
            const res = await fetch(endpoint + "/<?php echo (int)($articulo['id_articulo'] ?? 0); ?>", {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8',
                    'Accept': 'application/json',
                    'Authorization': basicAuth
                },
                body: JSON.stringify(payload)
            });

            const text = await res.text();
            let data;
            try { data = JSON.parse(text); }
            catch { data = { raw: text }; }

            out.textContent =
                `HTTP ${res.status} ${res.statusText}\n\n` +
                JSON.stringify(data, null, 2);

            if (res.ok) window.location.href = '<?php echo rtrim(RUTA_URL,'/'); ?>/articulos/index';

        } catch (err) {
            out.textContent = 'Error de red:\n' + String(err);
        }
    });
</script>

<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
