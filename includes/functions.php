<?php
// Funciones de conexión a la base de datos en caso de consultas directas
function pdo(PDO $pdo, string $sql, array $arguments = null)
{
    if (!$arguments) {
        return $pdo->query($sql);
    }
    $statement = $pdo->prepare($sql);
    $statement->execute($arguments);
    return $statement;
}

// Función para escapar texto HTML      
function html_escape($text): string
{
    $text = $text ?? '';
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8', false);
}

// Función para formatear la fecha
function format_date(string $string): string
{
    $date = date_create_from_format('Y-m-d H:i:s', $string);
    return $date->format('F d, Y');
}

// Función para redireccionar a otra página
function redirect(string $location, array $parameters = [], $response_code = 302)
{
    $qs = $parameters ? '?' . http_build_query($parameters) : '';
    $location = $location . $qs;
    header('Location: ' . $location, true, $response_code);

    exit;
}

// ERRORES Y EXCEPCIONES - Desarrollo
// Convierte errores en excepciones
set_error_handler('handle_error');
function handle_error($error_type, $error_message, $error_file, $error_line)
{
    throw new ErrorException($error_message, 0, $error_type, $error_file, $error_line);
}

// Manejo de excepciones
set_exception_handler('handle_exception');
function handle_exception($e)
{
    error_log($e);
    http_response_code(500);
    // Muestra el error completo (desarrollo)
    echo "<pre>";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "</pre>";
    exit;
}

// Errores fatales
register_shutdown_function('handle_shutdown');
function handle_shutdown()
{
    $error = error_get_last();
    if ($error !== null) {
        $e = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
        handle_exception($e);
    }
}