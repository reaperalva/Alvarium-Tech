<?php
// Define la ruta del archivo de registro
$archivo_registro = '../data/registros_formulario.txt';

// Verificar que los datos del formulario fueron enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge y sanitiza los datos del formulario
    $nombre = htmlspecialchars($_POST['name'] ?? '');
    $correo = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $whatsapp = htmlspecialchars($_POST['whatsapp'] ?? '');
    $mensaje = htmlspecialchars($_POST['comments'] ?? '');
    $fecha = date('Y-m-d H:i:s');

    // Valida que los campos requeridos no estén vacíos
    if (!empty($nombre) && filter_var($correo, FILTER_VALIDATE_EMAIL) && !empty($whatsapp) && !empty($mensaje)) {
        // Formato para el registro
        $registro = "--- Nuevo Registro ---\n";
        $registro .= "Fecha: " . $fecha . "\n";
        $registro .= "Nombre: " . $nombre . "\n";
        $registro .= "Correo: " . $correo . "\n";
        $registro .= "WhatsApp: " . $whatsapp . "\n";
        $registro .= "Mensaje: " . $mensaje . "\n\n";

        // Escribe el registro en el archivo
        // El flag FILE_APPEND asegura que se añada al final del archivo sin sobrescribirlo
        if (file_put_contents($archivo_registro, $registro, FILE_APPEND | LOCK_EX) !== false) {
            echo "¡Formulario enviado con éxito! Nos pondremos en contacto contigo pronto.";
        } else {
            echo "Hubo un error al guardar los datos. Por favor, inténtalo de nuevo.";
        }
    } else {
        echo "Error: Por favor, completa todos los campos correctamente.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>