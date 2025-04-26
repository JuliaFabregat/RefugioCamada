<?php
// DATABASE FUNCTION
function pdo(PDO $pdo, string $sql, array $arguments = null)
{
    if (!$arguments) {
        return $pdo->query($sql);
    }
    $statement = $pdo->prepare($sql);
    $statement->execute($arguments);
    return $statement;
}

// FORMATTING FUNCTIONS
function html_escape($text): string
{
    $text = $text ?? '';

    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8', false);
}

function format_date(string $string): string
{
    $date = date_create_from_format('Y-m-d H:i:s', $string);
    return $date->format('F d, Y');
}

// UTILITY FUNCTIONS
function redirect(string $location, array $parameters = [], $response_code = 302)
{
    $qs = $parameters ? '?' . http_build_query($parameters) : '';
    $location = $location . $qs;
    header('Location: ' . $location, true, $response_code);

    exit;
}

function create_filename(string $filename, string $uploads): string
{
    $basename  = pathinfo($filename, PATHINFO_FILENAME);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $cleanname = preg_replace("/[^A-z0-9]/", "-", $basename);
    $filename  = $cleanname . '.' . $extension;
    $i         = 0;
    while (file_exists($uploads . $filename)) {
        $i        = $i + 1;
        $filename = $basename . $i . '.' . $extension;
    }
    return $filename;
}

// ERROR AND EXCEPTION HANDLING FUNCTIONS
// Convert errors to exceptions
set_error_handler('handle_error');
function handle_error($error_type, $error_message, $error_file, $error_line)
{
    throw new ErrorException($error_message, 0, $error_type, $error_file, $error_line);
}

// Handle exceptions - log exception and show error message (if server does not send error page listed in .htaccess)
set_exception_handler('handle_exception');
// function handle_exception($e)
// {
//     error_log($e);                        // Log the error
//     http_response_code(500);              // Set the http response code
//     echo "<h1>Sorry, a problem occurred</h1>   
//           The site's owners have been informed. Please try again later.";
// }

// Errores más específicos para depurar
function handle_exception($e)
{
    error_log($e); // Mantiene el log
    http_response_code(500);
    // Muestra el error completo (solo para desarrollo)
    echo "<pre>";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "</pre>";
    exit;
}


// Handle fatal errors
register_shutdown_function('handle_shutdown');
function handle_shutdown()
{
    $error = error_get_last();
    if ($error !== null) {
        $e = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
        handle_exception($e);
    }
}