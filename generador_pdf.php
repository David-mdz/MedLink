<?php

// Crear instancia de FPDF
require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();

// Encabezado del PDF
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Recetas y Clones', 0, 1); // Título

// Supongamos que tienes un array con las recetas y sus clones
$recetas = array(
    array(
        'nombre' => 'Receta 1',
        'clones' => array('Clon 1A', 'Clon 1B', 'Clon 1C')
    ),
    array(
        'nombre' => 'Receta 2',
        'clones' => array('Clon 2A', 'Clon 2B')
    )
    // Puedes añadir más recetas y sus clones aquí
);

// Agregar las recetas y sus clones al PDF
$pdf->SetFont('Arial', '', 12);

foreach ($recetas as $receta) {
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Receta: ' . $receta['nombre'], 0, 1); // Mostrar el nombre de la receta

    $pdf->SetFont('Arial', '', 12);
    foreach ($receta['clones'] as $clone) {
        $pdf->Cell(10);
        $pdf->Cell(0, 10, 'Clon: ' . $clone, 0, 1); // Mostrar el nombre del clon
    }
    $pdf->Ln(); // Espacio entre recetas
}

// Salida del PDF
$pdf->Output();
?>
