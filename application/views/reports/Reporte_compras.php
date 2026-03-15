<?php

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Compras');

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
    <h3>Reporte de Compras Realizadas</h3>
EOD;
$pdf->writeHTMLCell(0,0,'','',$sheading,0,1,0,true,'C',true);
$pdf->Ln(8);




//			chart properties
//position
$chartX=30;
$chartY=40;

//dimension
$chartWidth=150;
$chartHeight=100;

//padding
$chartTopPadding=10;
$chartLeftPadding=20;
$chartBottomPadding=20;
$chartRightPadding=5;

//chart box
$chartBoxX=$chartX+$chartLeftPadding;
$chartBoxY=$chartY+$chartTopPadding;
$chartBoxWidth=$chartWidth-$chartLeftPadding-$chartRightPadding;
$chartBoxHeight=$chartHeight-$chartBottomPadding-$chartTopPadding;

//bar width


//chart data
$data=array();

foreach($compras as $obtenidos){
    if(in_array($data[$obtenidos['nombre']],$data)){
        $data[$obtenidos['nombre']]['value'] += $obtenidos['total'];
    }else{
        $data[$obtenidos['nombre']] = array();
        $data[$obtenidos['nombre']]['color'] = [rand(10,245),rand(10,245),rand(10,245)];
        $data[$obtenidos['nombre']]['value'] = $obtenidos['total'];
    }
}

//$dataMax
$dataMax=0;
foreach($data as $item){
	if($item['value']>$dataMax)$dataMax=$item['value'];
}

//data step
$barWidth=100/sizeof($data);
$dataStep=intval($dataMax/10);

//set font, line width and color
$pdf->SetLineWidth(0.2);
$pdf->SetDrawColor(0);

//chart boundary
$pdf->Rect($chartX,$chartY,$chartWidth,$chartHeight);

//vertical axis line
$pdf->Line(
	$chartBoxX ,
	$chartBoxY , 
	$chartBoxX , 
	($chartBoxY+$chartBoxHeight)
	);
//horizontal axis line
$pdf->Line(
	$chartBoxX-2 , 
	($chartBoxY+$chartBoxHeight) , 
	$chartBoxX+($chartBoxWidth) , 
	($chartBoxY+$chartBoxHeight)
	);

///vertical axis
//calculate chart's y axis scale unit
$yAxisUnits=$chartBoxHeight/$dataMax;

//draw the vertical (y) axis labels
for($i=0 ; $i<=$dataMax ; $i+=$dataStep){
	//y position
	$yAxisPos=$chartBoxY+($yAxisUnits*$i);
	//draw y axis line
	$pdf->Line(
		$chartBoxX-2 ,
		$yAxisPos ,
		$chartBoxX ,
		$yAxisPos
	);
	//set cell position for y axis labels
	$pdf->SetXY($chartBoxX-$chartLeftPadding , $yAxisPos-2);
	//$pdf->Cell($chartLeftPadding-4 , 5 , $dataMax-$i , 1);---------------
	$pdf->Cell($chartLeftPadding-4 , 5 , $dataMax-$i, 0 , 0 , 'R');
}

///horizontal axis
//set cells position
$pdf->SetXY($chartBoxX , $chartBoxY+$chartBoxHeight);

//cell's width
$xLabelWidth=$chartBoxWidth / count($data);

//$pdf->Cell($xLabelWidth , 5 , $itemName , 1 , 0 , 'C');-------------
//loop horizontal axis and draw the bar
$barXPos=0;
foreach($data as $itemName=>$item){
	//print the label
	//$pdf->Cell($xLabelWidth , 5 , $itemName , 1 , 0 , 'C');--------------
	$pdf->Cell($xLabelWidth , 5 , $itemName , 0 , 0 , 'C');
	
	///drawing the bar
	//bar color
	$pdf->SetFillColor($item['color'][0],$item['color'][1],$item['color'][2]);
	//bar height
	$barHeight=$yAxisUnits*$item['value'];
	//bar x position
	$barX=($xLabelWidth/2)+($xLabelWidth*$barXPos);
	$barX=$barX-($barWidth/2);
	$barX=$barX+$chartBoxX;
	//bar y position
	$barY=$chartBoxHeight-$barHeight;
	$barY=$barY+$chartBoxY;
	//draw the bar
	$pdf->Rect($barX,$barY,$barWidth,$barHeight,'DF');
	//increase x position (next series)
	$barXPos++;
}

//axis labels

$pdf->SetXY($chartX,$chartY);
$pdf->Cell(100,10,"Dinero Invertido",0);
$pdf->SetXY(($chartWidth/2)-50+$chartX,$chartY+$chartHeight-($chartBottomPadding/2));
$pdf->Cell(100,5,"Productos",0,0,'C');



$pdf->Ln(30);
$table = '<table style="border:2px solid gray">';
$table .= '<tr>
            <th style="border:2px solid gray">Producto</th>
            <th style="border:2px solid gray">Descripción</th>
            <th style="border:2px solid gray">Unidades</th>
            <th style="border:2px solid gray">Precio Unitario</th>
            <th style="border:2px solid gray">Costo Envio</th>
            <th style="border:2px solid gray">Total</th>
            <th style="border:2px solid gray">Titular Tarjeta</th>
            <th style="border:2px solid gray">Número Tarjeta</th>
            </tr>';
foreach($compras as $obtenidos){
    $table .= '<tr>
                <td style="border:2px solid gray">'.$obtenidos['nombre'].'</td>
                <td style="border:2px solid gray">'.$obtenidos['descripcion'].'</td>
                <td style="border:2px solid gray">'.$obtenidos['cantidad'].'</td>
                <td style="border:2px solid gray">$'.$obtenidos['precio'].'</td>
                <td style="border:2px solid gray">$'.$obtenidos['costo_envio'].'</td>
                <td style="border:2px solid gray">$'.$obtenidos['total'].'</td>
                <td style="border:2px solid gray">'.$obtenidos['titular_tarjeta'].'</td>
                <td style="border:2px solid gray">'.$obtenidos['numero_tarjeta'].'</td>
                </tr>';

}
$table .= '</table>';
$pdf->writeHTMLCell(0,0,'','',$table,0,1,0,true,'C',true);

// ---------------------------------------------------------

//Close and output PDF document
ob_clean();
$pdf->Output('Compras.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
