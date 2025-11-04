<?php

$textos = [
    'es' => [
        'titulo' => 'Selección de idioma',
        'bienvenido' => 'BIENVENIDO',
        'visita' => 'VISITA',
        'cookie_reset' => 'Cookie eliminada. Reseteo el contador de visitas',
        'idioma_actual' => 'Idioma actual guardado en cookie:',
        'cambiar_idioma' => 'Cambiar idioma:',
        'guardar_idioma' => 'Guardar idioma',
        'recordatorio' => 'Recuerda: al volver a cargar / volver a entrar en la página, el título aparecerá automáticamente en el idioma que hayas elegido.',
        'espanol' => 'Español',
        'ingles' => 'Inglés',
        'frances' => 'Francés',
        'aleman' => 'Alemán',
        'italiano' => 'Italiano',
    ],
    'en' => [
        'titulo' => 'Language selection',
        'bienvenido' => 'WELCOME',
        'visita' => 'VISIT',
        'cookie_reset' => 'Cookie deleted. Resetting visit counter',
        'idioma_actual' => 'Current language stored in cookie:',
        'cambiar_idioma' => 'Change language:',
        'guardar_idioma' => 'Save language',
        'recordatorio' => 'Remember: when you reload / come back to the page, the title will automatically appear in the language you chose.',
        'espanol' => 'Spanish',
        'ingles' => 'English',
        'frances' => 'French',
        'aleman' => 'German',
        'italiano' => 'Italian',
    ],
    'fr' => [
        'titulo' => 'Sélection de la langue',
        'bienvenido' => 'BIENVENUE',
        'visita' => 'VISITE',
        'cookie_reset' => 'Cookie supprimé. Réinitialisation du compteur de visites',
        'idioma_actual' => 'Langue actuelle enregistrée dans le cookie :',
        'cambiar_idioma' => 'Changer de langue :',
        'guardar_idioma' => 'Enregistrer la langue',
        'recordatorio' => 'Rappelle-toi : quand tu recharges / reviens sur la page, le titre apparaîtra automatiquement dans la langue choisie.',
        'espanol' => 'Espagnol',
        'ingles' => 'Anglais',
        'frances' => 'Français',
        'aleman' => 'Allemand',
        'italiano' => 'Italien',
    ],
    'de' => [
        'titulo' => 'Sprachauswahl',
        'bienvenido' => 'WILLKOMMEN',
        'visita' => 'BESUCH',
        'cookie_reset' => 'Cookie gelöscht. Besuchszähler wird zurückgesetzt',
        'idioma_actual' => 'Aktuelle in Cookie gespeicherte Sprache:',
        'cambiar_idioma' => 'Sprache ändern:',
        'guardar_idioma' => 'Sprache speichern',
        'recordatorio' => 'Denke daran: Wenn du die Seite neu lädst oder erneut besuchst, erscheint der Titel automatisch in der gewählten Sprache.',
        'espanol' => 'Spanisch',
        'ingles' => 'Englisch',
        'frances' => 'Französisch',
        'aleman' => 'Deutsch',
        'italiano' => 'Italienisch',
    ],
    'it' => [
        'titulo' => 'Selezione della lingua',
        'bienvenido' => 'BENVENUTO',
        'visita' => 'VISITA',
        'cookie_reset' => 'Cookie eliminato. Reset del contatore visite',
        'idioma_actual' => 'Lingua corrente salvata nel cookie:',
        'cambiar_idioma' => 'Cambia lingua:',
        'guardar_idioma' => 'Salva lingua',
        'recordatorio' => 'Ricorda: quando ricarichi o torni sulla pagina, il titolo apparirà automaticamente nella lingua che hai scelto.',
        'espanol' => 'Spagnolo',
        'ingles' => 'Inglese',
        'frances' => 'Francese',
        'aleman' => 'Tedesco',
        'italiano' => 'Italiano',
    ],
];

if (isset($_POST['lang'])) {
    $langActual = $_POST['lang'];
    setcookie('lang', $langActual, time() + 3600);
} else {
    if (isset($_COOKIE['lang'])) {
        $langActual = $_COOKIE['lang'];
    } else {
        $langActual = 'es';
        setcookie('lang', $langActual, time() + 3600);
    }
}

$mensajeVisitas = '';

if (!isset($_COOKIE['visitas'])) {
    setcookie('visitas', 1, time() + 3600);
    $mensajeVisitas = '<h1>'.$textos[$langActual]['bienvenido'].'</h1>';
} else {
    $visitas = (int) $_COOKIE['visitas'];
    if ($visitas < 10) {
        ++$visitas;
        setcookie('visitas', $visitas, time() + 3600);
        $mensajeVisitas = '<h1>'.$textos[$langActual]['visita'].' '.$visitas.'</h1>';
    } else {
        setcookie('visitas', '', time() - 3600);
        echo '<h2>'.$textos[$langActual]['cookie_reset'].'</h2>';
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="<?php echo $langActual; ?>">
<head>
    <meta charset="UTF-8" />
    <title><?php echo $textos[$langActual]['titulo']; ?></title>
</head>
<body>

    <?php echo $mensajeVisitas; ?>
    <h2><?php echo $textos[$langActual]['titulo']; ?></h2>

    <div class="bloque">
        <p><strong><?php echo $textos[$langActual]['idioma_actual']; ?></strong>
            <?php echo $langActual; ?>
        </p>

        <form method="post">
            <label for="lang"><?php echo $textos[$langActual]['cambiar_idioma']; ?></label>
            <select id="lang" name="lang">
                <option value="es" <?php if ($langActual === 'es') {
                    echo 'selected';
                } ?>>
                    <?php echo $textos[$langActual]['espanol']; ?>
                </option>
                <option value="en" <?php if ($langActual === 'en') {
                    echo 'selected';
                } ?>>
                    <?php echo $textos[$langActual]['ingles']; ?>
                </option>
                <option value="fr" <?php if ($langActual === 'fr') {
                    echo 'selected';
                } ?>>
                    <?php echo $textos[$langActual]['frances']; ?>
                </option>
                <option value="de" <?php if ($langActual === 'de') {
                    echo 'selected';
                } ?>>
                    <?php echo $textos[$langActual]['aleman']; ?>
                </option>
                <option value="it" <?php if ($langActual === 'it') {
                    echo 'selected';
                } ?>>
                    <?php echo $textos[$langActual]['italiano']; ?>
                </option>
            </select>
            <button type="submit"><?php echo $textos[$langActual]['guardar_idioma']; ?></button>
        </form>

        <p><?php echo $textos[$langActual]['recordatorio']; ?></p>
    </div>

</body>
</html>