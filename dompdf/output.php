<?php
GLOBAL $CFG_GLPI;
require_once 'autoload.inc.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();

# Cargamos el contenido HTML.
$dompdf ->load_html($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($nombre_pdf,null);

