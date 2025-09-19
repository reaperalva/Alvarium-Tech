<?php
// Incluye la librería FPDF
require('fpdf/fpdf.php');

// Definir las rutas a los archivos de datos
$ruta_visitas = '../data/visitas.txt';
$ruta_clics = '../data/clics.txt';
$ruta_registros = '../data/registros_formulario.txt';

// --- Extraer y contar el total de visitas ---
$total_visitas = 0;
if (file_exists($ruta_visitas)) {
    $lineas_visitas = file($ruta_visitas, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $total_visitas = count($lineas_visitas);
}

// --- Extraer y sumar el total de clics ---
$total_clics = 0;
if (file_exists($ruta_clics)) {
    $lineas_clics = file($ruta_clics, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lineas_clics as $linea) {
        $partes = explode(':', $linea);
        if (count($partes) == 2) {
            $total_clics += (int)trim($partes[1]);
        }
    }
}

// --- Extraer el total de registros del formulario ---
$total_registros = 0;
$registros_completos = [];
if (file_exists($ruta_registros)) {
    $contenido_registros = file_get_contents($ruta_registros);
    $registros_completos = explode('--- Nuevo Registro ---', $contenido_registros);
    array_shift($registros_completos);
    $total_registros = count($registros_completos);
}

// --- GENERAR EL REPORTE PDF CON FPDF ---
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Título principal
$pdf->Cell(0, 10, utf8_decode('Reporte de la Landing Page'), 0, 1, 'C');
$pdf->Ln(10);

// Sección de Resumen
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Resumen de Datos', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode('Total de Visitas: ') . $total_visitas, 0, 1);
$pdf->Cell(0, 10, utf8_decode('Total de Conversiones (Clics en CTA): ') . $total_clics, 0, 1);
$pdf->Cell(0, 10, utf8_decode('Total de Registros de Formulario: ') . $total_registros, 0, 1);
$pdf->Ln(15);

// Sección de Registros detallados
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Detalle de Registros del Formulario'), 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(230, 230, 230);

// Cabecera de la tabla
$pdf->Cell(30, 8, 'Fecha', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Nombre', 1, 0, 'C', true);
$pdf->Cell(60, 8, utf8_decode('Correo Electrónico'), 1, 0, 'C', true);
$pdf->Cell(30, 8, 'WhatsApp', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Mensaje', 1, 1, 'C', true);

// Contenido de la tabla con MultiCell para el mensaje
$pdf->SetFont('Arial', '', 9);
foreach ($registros_completos as $registro_texto) {
    if (trim($registro_texto) !== '') {
        $lineas = explode("\n", trim($registro_texto));
        $datos = [];
        foreach ($lineas as $linea) {
            $partes = explode(':', $linea, 2);
            if (count($partes) === 2) {
                $clave = trim($partes[0]);
                $valor = trim($partes[1]);
                $datos[$clave] = $valor;
            }
        }
        
        // **Paso 1: Calcular la altura necesaria para esta fila**
        $pdf->SetFont('Arial', '', 9);
        $x_initial = $pdf->GetX();
        $y_initial = $pdf->GetY();
        
        // Mover el cursor a la posición del Mensaje para calcular la altura
        $pdf->SetX($x_initial + 30 + 40 + 60 + 30); // Posición de X para la celda Mensaje
        $pdf->MultiCell(30, 5, utf8_decode($datos['Mensaje']), 0);
        $altura_fila = $pdf->GetY() - $y_initial;
        
        // **Paso 2: Dibujar todas las celdas con la altura calculada**
        $pdf->SetXY($x_initial, $y_initial);
        $pdf->Cell(30, $altura_fila, utf8_decode($datos['Fecha']), 1, 0, 'L');
        $pdf->Cell(40, $altura_fila, utf8_decode($datos['Nombre']), 1, 0, 'L');
        $pdf->Cell(60, $altura_fila, utf8_decode($datos['Correo']), 1, 0, 'L');
        $pdf->Cell(30, $altura_fila, utf8_decode($datos['WhatsApp']), 1, 0, 'L');
        
        // Celda del Mensaje con la altura ya definida
        $x_mensaje = $pdf->GetX();
        $y_mensaje = $pdf->GetY();
        $pdf->Cell(30, $altura_fila, '', 1); // Dibuja una celda vacía para el borde
        $pdf->SetXY($x_mensaje, $y_mensaje);
        $pdf->MultiCell(30, 5, utf8_decode($datos['Mensaje']), 0);

        // Mover el cursor a la siguiente fila
        $pdf->SetY($y_initial + $altura_fila);
    }
}

// Salida del PDF
$pdf->Output('D', 'reporte_landing_page_' . date('Y-m-d') . '.pdf');
?>