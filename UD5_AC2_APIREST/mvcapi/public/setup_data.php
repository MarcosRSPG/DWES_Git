<?php
/**
 * Script para insertar datos de prueba en la BD de mvcapi
 * Accede a este archivo en el navegador: http://mywww/UD5_AC2_APIREST/mvcapi/public/setup_data.php
 */

// Suprimir esto después de usarlo (por seguridad)
// define('ALLOW_SETUP', true);

try {
    // Cargar configuración y Db
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../app/config/config.php';
    
    use Cls\Mvc2app\Db;
    
    $db = new Db();
    
    echo "<h1>Setup de Datos</h1>\n";
    echo "<p>Insertando datos de prueba...</p>\n";
    
    // Crear tabla cars si no existe
    echo "<h3>1. Creando tabla 'cars'...</h3>\n";
    $db->query("CREATE TABLE IF NOT EXISTS cars (
        id INT AUTO_INCREMENT PRIMARY KEY,
        brand VARCHAR(50) NOT NULL,
        model VARCHAR(50) NOT NULL,
        color VARCHAR(30) NOT NULL,
        owner VARCHAR(80) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $db->execute();
    echo "<p style='color:green'>✓ Tabla 'cars' lista</p>\n";
    
    // Crear tabla articulos si no existe
    echo "<h3>2. Creando tabla 'articulos'...</h3>\n";
    $db->query("CREATE TABLE IF NOT EXISTS articulos (
        id_articulo INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(100) NOT NULL,
        contenido TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $db->execute();
    echo "<p style='color:green'>✓ Tabla 'articulos' lista</p>\n";
    
    // Limpiar datos existentes
    echo "<h3>3. Limpiando datos anteriores...</h3>\n";
    $db->query("TRUNCATE TABLE cars");
    $db->execute();
    $db->query("TRUNCATE TABLE articulos");
    $db->execute();
    echo "<p style='color:green'>✓ Tablas vaciadas</p>\n";
    
    // Insertar coches
    echo "<h3>4. Insertando coches...</h3>\n";
    $cars = [
        ['Volkswagen', 'Polo', 'Negro', 'Rebeca'],
        ['Toyota', 'Corolla', 'Blanco', 'Juan'],
        ['Ford', 'Fiesta', 'Rojo', 'María'],
        ['Honda', 'Civic', 'Azul', 'Pedro'],
        ['BMW', 'Serie 3', 'Gris', 'Ana'],
    ];
    
    foreach ($cars as $car) {
        $db->query("INSERT INTO cars (brand, model, color, owner) VALUES (:brand, :model, :color, :owner)");
        $db->bind(':brand', $car[0]);
        $db->bind(':model', $car[1]);
        $db->bind(':color', $car[2]);
        $db->bind(':owner', $car[3]);
        $db->execute();
    }
    echo "<p style='color:green'>✓ " . count($cars) . " coches insertados</p>\n";
    
    // Insertar artículos
    echo "<h3>5. Insertando artículos...</h3>\n";
    $articulos = [
        ['Primer Artículo', 'Contenido del primer artículo'],
        ['Segundo Artículo', 'Contenido del segundo artículo'],
        ['Tercer Artículo', 'Contenido del tercer artículo'],
    ];
    
    foreach ($articulos as $articulo) {
        $db->query("INSERT INTO articulos (titulo, contenido) VALUES (:titulo, :contenido)");
        $db->bind(':titulo', $articulo[0]);
        $db->bind(':contenido', $articulo[1]);
        $db->execute();
    }
    echo "<p style='color:green'>✓ " . count($articulos) . " artículos insertados</p>\n";
    
    // Verificar datos
    echo "<h3>6. Verificando datos...</h3>\n";
    $db->query("SELECT COUNT(*) as total FROM cars");
    $result = $db->registro();
    echo "<p style='color:green'>✓ Total de coches: " . $result->total . "</p>\n";
    
    $db->query("SELECT COUNT(*) as total FROM articulos");
    $result = $db->registro();
    echo "<p style='color:green'>✓ Total de artículos: " . $result->total . "</p>\n";
    
    echo "<hr>\n";
    echo "<p><strong>Setup completado exitosamente!</strong></p>\n";
    echo "<p><a href='" . RUTA_URL . "'>Volver a inicio</a></p>\n";
    
} catch (Exception $e) {
    echo "<h1>Error</h1>\n";
    echo "<p style='color:red'>" . $e->getMessage() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
}
?>
