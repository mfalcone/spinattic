<?php
/**
 * HTML2PDF Library - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @package   Html2pdf
 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
 * @copyright 2016 Laurent MINGUET
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

	require("../inc/conex.inc");
	session_start();
	
	//Check if invoice is mine
	$ssqlp = "select payments.* from payments where user_id = '".$_SESSION['usr']."' and id = '".$_GET["id"]."'";
	$result = mysql_query($ssqlp);
	if($row = mysql_fetch_array($result)){
		

	    // get the HTML
	    ob_start();
	    
	    $invoice = $row["id"];
	    
	    include(dirname(__FILE__).'/template/template.php');
	    $content = ob_get_clean();
	
	    // convert in PDF
	    require_once(dirname(__FILE__).'/vendor/autoload.php');
	    try
	    {
			
	        $html2pdf = new HTML2PDF('P', 'A4', 'es');
	//      $html2pdf->setModeDebug();
	        $html2pdf->setDefaultFont('Arial');
	        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
	        $html2pdf->Output('invoice.pdf');
	    }
	    catch(HTML2PDF_exception $e) {
	        echo $e;
	        exit;
	    }
	}else{
		header("Location: ../manager_account.php");
	}