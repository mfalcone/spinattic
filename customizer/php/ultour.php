<?php 
$restrict = 1;

session_start();

//require("inc/auth.inc");
require("functions.php");
        
function exit_status($return)
{
    echo json_encode($return);
    exit;
}

//print_r($_POST); die();
//Solo para insercion de nuevos tours

	
        /* (SOLO POR AUTOCREATE) */

        if (isset($_POST['autocreate'])) 
        { 


            $ssqlp1 = "insert into tours_draft (iduser,user, date, date_updated, state, version_xml) values ('".$_SESSION["usr"]."', '".mysql_real_escape_string($_SESSION["username"])."', now(), now(),'draft', 0)";
			mysql_query($ssqlp1);
			$ssqlp = "SELECT max(id) as elid FROM tours_draft";
			$result = mysql_query($ssqlp);
			$row = mysql_fetch_array($result);
			$elid = $row["elid"];
			
			mysql_query("update tours_draft set friendly_url = '".$elid."', title = '360º Virtual tour created with Spinattic.com' where id = ".$elid);
							
			//Inserto valores generales en customizer_draft
			mysql_query("insert into customizer_draft select ".$elid.", segment, kind, 0, tag_name, tag_ident, prev_tag_ident, attr, value, '".$_SESSION["usr"]."' from customizer_templates_general");
			
			//Inserto Skills por defecto en customizer_draft
			mysql_query("insert into customizer_draft select ".$elid.", segment, kind, skill_id, tag_name, tag_ident, prev_tag_ident, attr, value, '".$_SESSION["usr"]."' from customizer_templates_skills where add_by_default = 1");
			
			//Seteo la sesion con el id del tour para recuperarla en upload-files (siguiente paso)
			$_SESSION['tour_id'] = $elid;
			
			$mensaje = 'Tour was created successfuly!';

			exit_status(array(
			    'result' => 'SUCCESS', 
			    'msg' => $mensaje, 
			    'params' => array(
                                'tour_id' => $elid,
                                'xml_version' => 0
                                )
			));			

       }

            



//header("Location:edit_virtual_tour.php?id=".$elid);


?>
