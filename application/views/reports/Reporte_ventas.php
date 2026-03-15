<?php

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Ventas');

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
    <h3>Reporte de Ventas</h3>
EOD;
$pdf->writeHTMLCell(0,0,'','',$sheading,0,1,0,true,'C',true);




//chart data
$data=array();

#$array = array();
foreach($ventas as $obtenidos){
    if(in_array($data[$obtenidos['nombre']],$data)){
        $data[$obtenidos['nombre']]['value'] += $obtenidos['cantidad'];
    }else{
        $data[$obtenidos['nombre']] = array();
        $data[$obtenidos['nombre']]['color'] = [rand(10,245),rand(10,245),rand(10,245)];
        $data[$obtenidos['nombre']]['value'] = $obtenidos['cantidad'];
    }
}



#array_push($data,$array['perra']);
//pie and legend properties
$pieX=105;
$pieY=80;
$r=40;//radius
$legendX=150;
$legendY=70;

//get total data summary
$dataSum=0;
foreach($data as $item){
	$dataSum+=$item['value'];
}

//get scale unit for each degree
$degUnit=360/$dataSum;

//variable to store current angle
$currentAngle=0;
//store current legend Y position
$currentLegendY=$legendY;



//simplify the code by drawing both pie and legend in one loop
foreach($data as $index=>$item){
	//draw the pie
	//slice size
	$deg=$degUnit*$item['value'];
	//set color
    $pdf->setTextColor('gray');
	$pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
	//remove border
	
	//draw the slice
	$pdf->PieSector($pieX,$pieY,$r,$currentAngle,$currentAngle+$deg);
	//add slice angle to currentAngle var
	$currentAngle+=$deg;
	
	//draw the legend	
	$pdf->Rect($legendX,$currentLegendY,5,5,'DF');
	$pdf->SetXY($legendX + 6,$currentLegendY);
	$pdf->Cell(50,5,$index . " - " . $item['value'] . " uds",0,0);
	$currentLegendY+=5;
}


$pdf->Ln(50);
$sumaTotal = 0;
$table = '<table style="border:2px solid gray">';
$table .= '<tr>
            <th style="border:2px solid gray">Producto</th>
            <th style="border:2px solid gray">Descripción</th>
            <th style="border:2px solid gray">Unidades</th>
            <th style="border:2px solid gray">Precio Unitario</th>
            <th style="border:2px solid gray">Costo Envio</th>
            <th style="border:2px solid gray">Total</th>
            </tr>';
foreach($ventas as $obtenidos){
    $table .= '<tr>
                <td style="border:2px solid gray">'.$obtenidos['nombre'].'</td>
                <td style="border:2px solid gray">'.$obtenidos['descripcion'].'</td>
                <td style="border:2px solid gray">'.$obtenidos['cantidad'].'</td>
                <td style="border:2px solid gray">$'.$obtenidos['precio'].'</td>
                <td style="border:2px solid gray">$'.$obtenidos['costo_envio'].'</td>
                <td style="border:2px solid gray">$'.$obtenidos['total'].'</td>
                </tr>';
    $sumaTotal += $obtenidos['total'];
}
$table .= '<tr>
            <td style="border:2px solid gray">TOTAL:</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>$'.$sumaTotal.'</td>    
            </tr>';
$table .= '</table>';

$pdf->writeHTMLCell(0,0,'','',$table,0,1,0,true,'C',true);

// ---------------------------------------------------------

//Close and output PDF document
ob_clean();
$pdf->Output('Ventas.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
