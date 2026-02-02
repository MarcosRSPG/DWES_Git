<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>

<h1><?php echo $datos['titulo'] ?? 'Alta de articulos'; ?></h1>

<p>
    Esta página es un <strong>cliente web</strong> que envía un JSON a la <strong>API REST</strong> mediante
    <code>fetch()</code>. La API devuelve el resultado y se muestra debajo.
</p>

<?php
// Endpoint REST apuntando al servidor API (mvcapi)
$API_USUARIOS_ENDPOINT = rtrim(API_BASE_URL, '/') . '/api/usuarios';
?>

<style>
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start; }
    .card { border: 1px solid #ddd; border-radius: 10px; padding: 16px; }
    label { display:block; margin-top: 10px; font-weight: 600; }
    input { width: 100%; padding: 8px; margin-top: 6px; box-sizing: border-box; }
    button { margin-top: 14px; padding: 10px 14px; cursor: pointer; }
    pre { background:#f4f4f4; padding:12px; border-radius:8px; overflow:auto; }
    code { background:#f4f4f4; padding:2px 6px; border-radius:6px; }
    .muted { color:#555; font-size: 0.95em; }
</style>

<div class="grid">
    <div class="card">
        <h3>Formulario (cliente web)</h3>

        <form id="usuarioForm" autocomplete="off">
            <label>nombre (nombre)
                <input name="nombre" required maxlength="50" placeholder="nombre">
            </label>

            <label>clave (clave)
                <input name="clave" required maxlength="50" placeholder="clave">
            </label>

            <button type="submit">Crear usuario (POST JSON a la API)</button>
        </form>

        <p class="muted">
            Endpoint: <code><?php echo htmlspecialchars($API_USUARIOS_ENDPOINT); ?></code>
        </p>
    </div>

    <div class="card">
        <h3>Respuesta de la API</h3>
        <pre id="out">Pulsa “Crear usuario para ver la respuesta JSON.</pre>
    </div>
</div>

<script>
    const endpoint = <?php echo json_encode($API_USUARIOS_ENDPOINT); ?>;
    const basicAuth = <?php echo json_encode('Basic ' . base64_encode(API_BASIC_USER . ':' . API_BASIC_PASS)); ?>;

    document.getElementById('usuarioForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const out = document.getElementById('out');
        out.textContent = 'Enviando petición a la API...';

        const fd = new FormData(e.target);
        const payload = Object.fromEntries(fd.entries());

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
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

            if (res.ok) e.target.reset();

        } catch (err) {
            out.textContent = 'Error de red:\n' + String(err);
        }
    });
</script>

<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
