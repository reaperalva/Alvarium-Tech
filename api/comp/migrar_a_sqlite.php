<?php
// Define la ruta donde se creará el archivo de la base de datos
// La ruta es '../../data/' porque el script estará en la carpeta 'comp/'
$ruta_db = '../data/mi_proyecto.sqlite';

try {
    // Esto crea el archivo de la base de datos si no existe, o lo abre si ya existe
    $db = new SQLite3($ruta_db);

    // SQL para crear las tablas, si no existen
    // Tabla para la info general (reemplaza a info.txt)
    $db->exec("CREATE TABLE IF NOT EXISTS info_general (clave TEXT PRIMARY KEY, valor TEXT)");
    
    // Tabla para las visitas (reemplaza a visitas.txt)
    $db->exec("CREATE TABLE IF NOT EXISTS visitas (id INTEGER PRIMARY KEY, ip TEXT, fecha TEXT)");
    
    // Tabla para los clics (reemplaza a clics.txt)
    $db->exec("CREATE TABLE IF NOT EXISTS clics (id INTEGER PRIMARY KEY, boton_id TEXT, conteo INTEGER)");
    
    // Tabla para los registros del formulario (reemplaza a registros_formulario.txt)
    $db->exec("CREATE TABLE IF NOT EXISTS registros (id INTEGER PRIMARY KEY, nombre TEXT, correo TEXT, whatsapp TEXT, mensaje TEXT, fecha TEXT)");
    
    // Tabla para los servicios (reemplaza a servicios.txt)
    $db->exec("CREATE TABLE IF NOT EXISTS servicios (id INTEGER PRIMARY KEY, titulo_seccion TEXT, titulo TEXT, descripcion TEXT)");
    
    // Tabla para los comentarios (reemplaza a comentarios.txt)
    $db->exec("CREATE TABLE IF NOT EXISTS comentarios (id INTEGER PRIMARY KEY, comentario TEXT, autor TEXT, mensaje_empresa TEXT)");

    echo "¡Base de datos y tablas creadas con éxito! Ahora puedes empezar a migrar tus datos.";
    
    $db->close();

} catch (Exception $e) {
    echo "Hubo un error al inicializar la base de datos: ". $e->getMessage();
}
?>