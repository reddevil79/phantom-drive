<?php
session_start();
require_once("DBConnection.php");              
include_once('tcpdf/tcpdf.php');

$transaction_id=$_GET['MST_ID'];


$inv_mst_query = "SELECT T1.transaction_id, T1.receipt_no, T1.date_added, T1.total, T1.tendered_amount, T1.change FROM transaction_list T1 WHERE T1.transaction_id='".$transaction_id."' ";
$inv_mst_results = mysqli_query($conn, $inv_mst_query);
$count = mysqli_num_rows($inv_mst_results);
if ($count > 0) {
    $inv_mst_data_row = mysqli_fetch_array($inv_mst_results, MYSQLI_ASSOC);

    //----- Code for generate pdf
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    //$pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->AddPage(); //default A4
    //$pdf->AddPage('P','A5'); //when you require custom page size

    $content = '';

    $content .= '<style type="text/css">
	body{
	font-size:12px;
	line-height:24px;
	font-family:"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
	color:#000;
	}
	</style>    
	<table cellpadding="0" cellspacing="0" style="border:1px solid #ddc;width:100%;">
	<table style="width:100%;" >
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="3" align="center"><b>Bakery Managemet System</b></td></tr>
	<tr><td colspan="3" align="center"><b>CONTACT: 9874561230</b></td></tr>
    <br>
	<tr><td><b>BILL NO.: '.$inv_mst_data_row['receipt_no'].'</b></td></tr>
    <br>
	<tr>
    <td colspan="3" align="center" style="font-size: 18px;">
    <b>RECIEPT</b>
  </td>
</tr>

	<br>
    <tr class="heading" style="background:#eee;border-bottom:1px solid #ddd;font-weight:bold; font-size: 15px;">
    <td align="left">QTY</td>
    <td align="center">Product</td>
    <td align="right">Amount</td>
    </tr>
    <br>';
    
		$total=0;
		$inv_det_query = "SELECT i.*, p.name as pname,p.product_code FROM `transaction_items` i inner join product_list p on i.product_id = p.product_id where `transaction_id` = '{$transaction_id}'";
		$inv_det_results = mysqli_query($conn,$inv_det_query);    
		while($inv_det_data_row = mysqli_fetch_array($inv_det_results, MYSQLI_ASSOC))
		{	
		$content .= '
        <tr class="itemrows">

			  <td align="left">
				  <b>'.$inv_det_data_row['quantity'].'</b>
				  <br>
			  </td>
              <td align="center">
              <b>'.$inv_det_data_row['pname'].'</b>
              <br>
          </td>
			  <td align="right"><b>
				  '.$inv_det_data_row['price'].'
			  </b></td>
		  </tr>';
	
		}
		$content .= '<tr class="total"><td colspan="3" align="right">------------------------</td></tr>
		<tr><td colspan="3" align="right"><b>GRAND&nbsp;TOTAL:&nbsp;'.$inv_mst_data_row['total'].'</b></td></tr>
		<tr><td colspan="3" align="right">------------------------</td></tr>
	
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr><td colspan="4" align="center"><b>THANK YOU ! VISIT AGAIN</b></td></tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	</table>
</table>'; 
$pdf->writeHTML($content);

$file_location = "/home/fbi1glfa0j7p/public_html/examples/generate_pdf/uploads/"; //add your full path of your server
//$file_location = "/opt/lampp/htdocs/examples/generate_pdf/uploads/"; //for local xampp server

$datetime=date('dmY_hms');
$file_name = "INV_".$datetime.".pdf";
ob_end_clean();

$pdf->Output($file_name, 'D'); // D means download



//----- End Code for generate pdf
	

    }
?>