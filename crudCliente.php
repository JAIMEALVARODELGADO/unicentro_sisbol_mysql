<?php
/**
 * consultas.php
 * Endpoint AJAX para consultas de listas/catálogos del sistema.
 *
 * Uso:  GET consultas.php?opcion=<nombre>
 * Resp: JSON  { success: bool, data: [...] | message: string }
 * ─────────────────────────────────────────────────────────────
 * Ubicar en la misma carpeta que formulario_clientes_unicentro.html
 * Ajustar las constantes de conexión a la base de datos.
 */

// ── Cabeceras ────────────────────────────────────────────────────
header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');

// Solo GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

// ── Configuración de base de datos ──────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'unicentro');   // <-- Nombre de su base de datos
define('DB_USER',    'root');        // <-- Usuario
define('DB_PASS',    '');            // <-- Contraseña
define('DB_CHARSET', 'utf8mb4');

// ── Leer parámetro ───────────────────────────────────────────────
$opcion = trim($_GET['opcion'] ?? '');

if ($opcion === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Parámetro "opcion" requerido.']);
    exit;
}

// ── Conexión PDO ─────────────────────────────────────────────────
try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    error_log('[consultas.php] Conexión fallida: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error de conexión con la base de datos.']);
    exit;
}

// ── Router de opciones ───────────────────────────────────────────
switch ($opcion) {

    /* ── Tipos de documento de identidad ── */
    case 'tipoIdentificacion':
        /*
         * Tabla: tipo
         * Columnas relevantes:
         *   codi_tip  varchar  – código único del tipo  (ej. 01001)
         *   codi_gru  varchar  – grupo al que pertenece (ej. 01)
         *   desc_tip  varchar  – descripción larga      (ej. Cedula de Ciudadanía)
         *   valo_tip  varchar  – valor corto / sigla    (ej. CC)
         *   fijo_tip  char     – indicador fijo         (S / N)
         *
         * Filtro: codi_gru = '01'
         * Orden:  codi_tip ASC
         */
        try {
            $stmt = $pdo->prepare(
                "SELECT codi_tip, codi_gru, desc_tip, valo_tip, fijo_tip
                   FROM tipo
                  WHERE codi_gru = :codi_gru
                  ORDER BY codi_tip ASC"
            );
            $stmt->execute([':codi_gru' => '01']);
            $rows = $stmt->fetchAll();

            echo json_encode([
                'success' => true,
                'data'    => $rows,
            ]);

        } catch (PDOException $e) {
            http_response_code(500);
            error_log('[consultas.php] tipoIdentificacion: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al consultar tipos de documento.']);
        }
        break;

    /* ── Opción no reconocida ── */
    default:
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => "Opción \"$opcion\" no reconocida.",
        ]);
        break;
}