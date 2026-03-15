<?php

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Factura');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font


// add a page
$pdf->AddPage();

$sheading = <<<EOD
    <h1>Reporte de Factura</h1>
EOD;
$pdf->writeHTMLCell(0,0,'','',$sheading,0,1,0,true,'C',true);
$pdf->Ln(8);



// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$html = '<h3>Especificación</h3>
        <br>
        <label>Factura número: </label>
        <label>'.$venta['idVentas'].'</label>
        <br>
        <label>Fecha de facturación: </label>
        <label>'.$venta['fecha'].'</label>
        <br>
        <label>Nombre cliente: </label>
        <label>'.$venta['usuario'].'</label>
        <br>
        <label>DNI cliente: </label>
        <label>'.$venta['cedula'].'</label>

        <h3>Dirección</h3>
        <br>
        <label>Pais:</label>
        <label>'.$venta['pais'].',     </label>
        <label>Provincia:</label>
        <label>'.$venta['provincia'].',     </label>
        <label>Casillero:</label>
        <label>'.$venta['casillero'].',     </label>
        <label>Codigo Postal:</label>
        <label>'.$venta['postal'].'</label>
        <br><br>
        <label>Observaciones: </label>
        <label>'.$venta['observaciones'].'</label>

        <h3>Metodo de Pago</h3>
        <br>
        <label>Titular: '.$venta['titular_tarjeta'].'</label>
        <br>
        <label>Numero de tarjeta: '.$venta['numero_tarjeta'].'</label><br><br>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

$table = '<table style="border:2px solid gray">';
$table .= '<tr>
            <th width="12%" style="border:2px solid gray">Producto</th>
            <th width="40%" style="border:2px solid gray">Descripción</th>
            <th width="12%" style="border:2px solid gray">Unidades</th>
            <th width="12%" style="border:2px solid gray">Precio Unitario</th>
            <th width="12%" style="border:2px solid gray">Costo Envio</th>
            <th width="12%" style="border:2px solid gray">Total</th>
            
            </tr>';
foreach($producto as $obtenidos){
    $table .= '<tr>
                <td style="border:2px solid gray">'.$obtenidos['nombre'].'</td>
                <td style="border:2px solid gray">'.$obtenidos['descripcion'].'</td>
                <td style="border:2px solid gray">'.$obtenidos['cantidad'].'</td>
                <td style="border:2px solid gray">$'.$obtenidos['precio'].'</td>
                <td style="border:2px solid gray">$'.$obtenidos['costo_envio'].'</td>
                <td style="border:2px solid gray">$'.$obtenidos['total'].'</td>
                </tr>';

}
$table .= '</table>';
$pdf->writeHTMLCell(0,0,'','',$table,0,1,0,true,'C',true);

$html = '<br><br><br>
        <label style="font-size:20px; font-weight:bold">TOTAL: $'.$venta['venta_total'].'<label>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// ---------------------------------------------------------

//Close and output PDF document
ob_clean();
$pdf->Output('Factura.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
