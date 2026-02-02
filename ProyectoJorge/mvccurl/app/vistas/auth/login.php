<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>

<h1><?php echo $datos['titulo'] ?? 'Login'; ?></h1>

<?php
// Endpoint REST apuntando al servidor API (mvcapi)
$API_USUARIOS_ENDPOINT = rtrim(API_BASE_URL, '/') . '/api/login';
?>

<?php if (!empty($datos['error'])): ?>
    <pre>
Error HTTP <?php echo (int)$datos['http']; ?>:
<?php echo htmlspecialchars(json_encode($datos['error'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?>
  </pre>

    <p><a href="<?php echo rtrim(RUTA_URL,'/'); ?>/paginas/usuarios_form">Crear usuario</a></p>

    <?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
    <?php return; ?>
<?php endif; ?>

<form id="loginForm" autocomplete="off">
    <label for="usuario">Usuario</label><br>
    <input type="text" name="nombre" id="nombre" required>
    <br><br>
    <label for="clave">Contraseña</label><br>
    <input type="password" name="clave" id="clave" required>
    <br><br>
    <button type="submit">Entrar</button>
</form>

<pre id="out"></pre>

<script>
    const endpoint = <?php echo json_encode($API_USUARIOS_ENDPOINT); ?>;
    const basicAuth = <?php echo json_encode('Basic ' . base64_encode(API_BASIC_USER . ':' . API_BASIC_PASS)); ?>;
    const sessionEndpoint = <?php echo json_encode(rtrim(RUTA_URL,'/') . '/Auth/setSession'); ?>;

    document.getElementById('loginForm').addEventListener('submit', async (e) => {
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

            if (res.ok) {
                const usuario = payload.nombre || '';
                const ses = await fetch(sessionEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ usuario })
                });

                if (!ses.ok) {
                    const sesText = await ses.text();
                    out.textContent = 'Error al crear sesión:\n' + sesText;
                    return;
                }

                window.location.href = '<?php echo rtrim(RUTA_URL,'/'); ?>/articulos/index';
            }

        } catch (err) {
            out.textContent = 'Error de red:\n' + String(err);
        }
    });
</script>

<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
