<?php 

//echo $_POST['scenes_changes'];

$restrict = 1;

require("inc/auth.inc");
require("inc/conex.inc");
require("inc/functions.inc");
include("php-stubs/make_tour_files.php");	
        
function exit_status($return)
{
    echo json_encode($return);
    exit;
}

//print_r($_POST); die();
//Decido si edita o inserta:

$ssqlp = "SELECT * FROM tours_draft where id = '".$_POST["tour_id"]."'";
$result = mysql_query($ssqlp);	

if($row = mysql_fetch_array($result)){

	/* EDITA */   
	
        $elid = $_POST["tour_id"];
        
        //Elimine el update de user por la perdida de session, de todas formas no hace falta actualizar mas ese campo
        
        $ssqlp1 = "update tours_draft set title = '".mysql_real_escape_string($_POST["title"])."', 
        location = '".mysql_real_escape_string($_POST["location"])."', 
        description = '".mysql_real_escape_string($_POST["description"])."',
        category = '".$_POST["category"]."',
        tags = '".mysql_real_escape_string($_POST["tags_loaded"])."', 
        lat = '".$_POST["lat"]."', 
        lon = '".$_POST["lon"]."', 
        allow_comments = '".$_POST["allow_comments"]."', 
        allow_social = '".$_POST["allow_social"]."', 
        allow_embed = '".$_POST["allow_embed"]."', 
        allow_votes = '".$_POST["allow_votes"]."',
        enable_avatar = '".$_POST["enable_avatar"]."', 
        enable_title = '".$_POST["enable_title"]."',
        skin_id = '".$_POST["skin_id"]."',  
        thumb_width = '".$_POST["thumb_width"]."',
        thumb_height = '".$_POST["thumb_height"]."',
        thumb_margin = '".$_POST["thumb_margin"]."',
        privacy = '_".$_POST["privacy"]."', 
        date_updated = now()";
		// En draft siempre van a estar en ese estado    if(!($_GET["autosave"]==true) || $_GET["autosave"]==undefined){$ssqlp1 .= ",state = '".$_POST["saving-type"]."'";}        
        if(!($_GET["autosave"]==true) || $_GET["autosave"]==undefined){$ssqlp1 .= ",version_xml = version_xml + 1";}
        $ssqlp1 .= " where id = ".$elid;
        
        //echo $ssqlp1;
        //$devolver = $ssqlp1;
		
		mysql_query($ssqlp1);

		/* AGREGO TAGS */
			
		$pieces = explode(",", $_POST["tags_loaded"]);
		foreach($pieces as $piece => $value){
				
			$ssqlp = "SELECT tag FROM tags where tag = '".trim($value)."'";
			$result = mysql_query($ssqlp);	
			if(!($row = mysql_fetch_array($result))){
				mysql_query("insert into tags (tag) values ('".trim(mysql_real_escape_string($value))."')");

			}
		}


		/* APLICO EL ORDEN DE ESCENAS */
		if($_POST["scene-id"]){
			foreach($_POST["scene-id"] as $orderscene => $idscene){
				$ssqlp2 = "update panosxtour_draft set ord = ".$orderscene." where id = ".$idscene;
				mysql_query($ssqlp2);
			}
		}	

		//Update de thumb_path, si no esta customizado
		//Check if Customized:
		$ssqlthumb = "SELECT * FROM tours_draft where id = ".$_POST["tour_id"];
		$resultthumb = mysql_query($ssqlthumb);
		$rowthumb = mysql_fetch_array($resultthumb);
		if($rowthumb["tour_thumb_custom"] == 0){
			//If not customized, replace with 1st pano thumb
			$ssqlthumb = "SELECT * FROM panosxtour_draft where idtour = ".$_POST["tour_id"]." ORDER BY ord LIMIT 1";
			$resultthumb = mysql_query($ssqlthumb);
			$rowthumb = mysql_fetch_array($resultthumb);
		
			mysql_query("update tours_draft set tour_thumb_path = '".$cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/'."' where id = ".$_POST["tour_id"]);
		
		}		
		

		/* HOTSPOTS (Si no estoy pasando a published) */ 
	//if(!($_POST["saving-type"] == 'publish' && (!($_GET["autosave"]==true) || $_GET["autosave"]==undefined))){		
		
//	}


		$elarray = json_decode($_POST['scenes_changes']);
		
                if ($elarray)
                {    
                    foreach ($elarray as $elemento => $contenido) 
                    { 

                            $htsp_scene_id = '';

    //			$ssqlp3 = "insert into texto (id,texto) values (now(), 'elemento: ".$elemento."')";
    //			mysql_query($ssqlp3);

                            foreach ($contenido as $key_elemento => $elemento_dentro_de_scene) 
                            {

                                    if ($key_elemento == 'id'){$htsp_scene_id = $elemento_dentro_de_scene;}
                                    if ($key_elemento == 'hotspots'){

                                            //SI HAY HOTSPOTS BORRO LOS QUE TENGA
                                            $ssqlp3 = "delete from hotspots_draft where scene_id = '".$htsp_scene_id."'";
                                            mysql_query($ssqlp3);					

                                            foreach ($elemento_dentro_de_scene as $id_hotspot => $hotspot) 
                                            {
                                                    $htsp_style = '';
                                                    $htsp_name = '';
                                                    $htsp_type = '';
                                                    $htsp_ath = '';
                                                    $htsp_atv = '';
                                                    $htsp_extra_linkedscene = '';
                                                    $htsp_extra_infotitle = '';
                                                    $htsp_extra_infotext = '';
                                                    $htsp_extra_photourl = '';
                                                    $htsp_extra_tooltip = '';					

    //						$ssqlp3 = "insert into texto (id,texto) values (now(), '".$elemento.": id_hotspot: ".$id_hotspot."')";
    //						mysql_query($ssqlp3);

                                                    foreach ($hotspot as $caracteristica_hotspot => $valor_caracteristica_hotspot) 
                                                    {
                                                            if($caracteristica_hotspot == 'ath'){$htsp_ath = $valor_caracteristica_hotspot;}

                                                            if($caracteristica_hotspot == 'atv'){$htsp_atv = $valor_caracteristica_hotspot;}

                                                            if($caracteristica_hotspot == 'name'){$htsp_name = $valor_caracteristica_hotspot;}

                                                            if($caracteristica_hotspot == 'type'){

                                                                    $htsp_type = $valor_caracteristica_hotspot;

                                                                    switch ($htsp_type) {
                                                                        case "info":
                                                                            $htsp_style = 'info';
                                                                            break;
                                                                        case "media":
                                                                            $htsp_style = 'photo';
                                                                            break;
                                                                        case "link":
                                                                            $htsp_style = 'arrow';
                                                                            break;
                                                                    }								

                                                                    $htsp_style .= '_hotspot';

    //								$ssqlp3 = "insert into texto (id,texto) values (now(), '".$elemento.": hspt: ".$id_hotspot.": ".$caracteristica_hotspot.": ".$valor_caracteristica_hotspot."')";
    //								mysql_query($ssqlp3);

                                                            }

                                                            if($caracteristica_hotspot == 'extra'){

                                                                    foreach ($valor_caracteristica_hotspot as $index_extra => $valor_extra) 
                                                                    {
                                                                            switch ($index_extra) {
                                                                                case "linkedscene":
                                                                                    $htsp_extra_linkedscene = $valor_extra;
                                                                                    break;
                                                                                case "infotitle":
                                                                                    $htsp_extra_infotitle = $valor_extra;
                                                                                    break;
                                                                                case "infotext":
                                                                                    $htsp_extra_infotext = $valor_extra;
                                                                                    break;
                                                                                case "pic":
                                                                                    $htsp_extra_photourl = $valor_extra;
                                                                                    break;
                                                                                case "tooltip":
                                                                                    $htsp_extra_tooltip = $valor_extra;
                                                                                    break;									        									        
                                                                            }									

    //									$ssqlp3 = "insert into texto (id,texto) values (now(), '".$elemento.": hspt: ".$id_hotspot.": Extra: ".$index_extra.": ".$valor_extra."')";
    //									mysql_query($ssqlp3);
                                                                    }
                                                            }
                                                    }

                                                    $ssqlp3 = "insert into hotspots_draft (scene_id, name, style, type, ath, atv, extra_linkedscene, extra_infotitle, extra_infotext, extra_photourl, extra_tooltip) values ('".$htsp_scene_id."', '".mysql_real_escape_string($htsp_name)."', '".$htsp_style."', '".$htsp_type."', '".$htsp_ath."', '".$htsp_atv."', '".$htsp_extra_linkedscene."', '".mysql_real_escape_string($htsp_extra_infotitle)."', '".mysql_real_escape_string($htsp_extra_infotext)."', '".$htsp_extra_photourl."', '".mysql_real_escape_string($htsp_extra_tooltip)."')";
                                                    mysql_query($ssqlp3);	
                                                    //echo $ssqlp3;

                                            }
                                    }
                                    if ($key_elemento == 'view'){
                                            foreach ($elemento_dentro_de_scene as $id_view => $view) 
                                            {
                                                    if($id_view == 'hlookat'){$hspt_view_hlookat = $view;}
                                                    if($id_view == 'vlookat'){$hspt_view_vlookat = $view;}

    //						$ssqlp3 = "insert into texto (id,texto) values (now(), '".$elemento.": view: ".$id_view.": ".$view."')";
    //						mysql_query($ssqlp3);

                                            }
                                    }
                                    $ssqlp3 = "update panosxtour_draft set hlookat = '".$hspt_view_hlookat."', vlookat = '".$hspt_view_vlookat."' where id = '".$htsp_scene_id."'";
                                    mysql_query($ssqlp3);				
                            }
                    }
                }

		/* VUELCO O BORRO EN TABLAS PRINCIPALES / CREO ARCHIVOS DEL TOUR O LO ELIMINO DEPENDIENDO DE DRAFT O PUBLISH */
                
                if($_POST["saving-type"] == 'publish' && (!($_GET["autosave"]==true) || $_GET["autosave"]==undefined)){
                	
                	//Tomo si el tour es brand new (es la 1era vez que se publica) para el mixpanel
                	$ssqlpbn = "SELECT brand_new FROM tours_draft where id = ".$elid;
                	$resultbn = mysql_query($ssqlpbn);
                	$rowbn = mysql_fetch_array($resultbn);
                	$brand_new = $rowbn["brand_new"];                	
                	
                	
                	//Borro lo que haya en publish y subo lo nuevo
                	mysql_query("delete from hotspots where scene_id in (select id from panosxtour where idtour = ".$elid.")");
                	mysql_query("delete from panosxtour where idtour = ".$elid);
                	mysql_query("delete from tours where id = ".$elid);
                	                	
                	mysql_query("insert into tours select id, title, friendly_url, location, description, category, tags, lat, lon, allow_comments, allow_social, allow_embed, allow_votes, privacy, likes, views, iduser, user, comments, date, date_updated, 'publish' as state, version_xml, brand_new, enable_avatar, skin_id, enable_title, thumb_width, thumb_height, thumb_margin, priority, tour_thumb_path, tour_thumb_custom, show_lat_lng from tours_draft where id = ".$elid);
                	mysql_query("insert into panosxtour select * from panosxtour_draft where idtour = ".$elid);
                	mysql_query("insert into hotspots select * from hotspots_draft where scene_id in (select id from panosxtour_draft where idtour = ".$elid.")");
                	
                	
                	//paso Brand_new a 0
                	mysql_query("update tours_draft set brand_new = 0  where id = ".$elid);
                	
                	make_tour_files($elid);
                	
                	if ($brand_new == 1 ){
                		setMaxPriority($elid);
                	}
                	
                }else{

                	if(!($_GET["autosave"]==true) || $_GET["autosave"]==undefined){

                		//Si despublico, traigo las escenas que tenga desde publish para que no tome algun draft viejo (hotspots y los datos del tour los actualiza arriba) 
                		mysql_query("delete from panosxtour_draft where idtour = ".$elid);
                		mysql_query("insert into panosxtour_draft select * from panosxtour where idtour = ".$elid);
                		
	                	mysql_query("delete from hotspots where scene_id in (select id from panosxtour where idtour = ".$elid.")");
	                	mysql_query("delete from panosxtour where idtour = ".$elid);
	                	mysql_query("delete from tours where id = ".$elid);
	                	
	                	//borro local
	                	$tourfolder = 'tours/'.$elid.'/';
	                	if(is_dir($tourfolder)){
		                	$gestor = opendir($tourfolder);
		                	$count = 0;
		                	$retval = array();
		                	while ($elemento = readdir($gestor))
		                	{
		                		if($elemento != "." && $elemento != ".."){
		                			unlink ($tourfolder.$elemento);
		                		}
		                	}
	                	}
                	}               	
                }
                		
                
                $ssqlp = "SELECT version_xml FROM tours_draft WHERE id = ".$elid;
                $result = mysql_query($ssqlp);	
                
                if($row = mysql_fetch_array($result))   $xml_version = $row["version_xml"];
                
                
                exit_status(array(
			    'result' => 'SUCCESS', 
			    'msg' => 'Changes has been saved', 
//			    'state' => $devolver,
			    'params' => array(
                                'xml_version' => $xml_version,
			    				'brand_new' => $brand_new
                            )
			));	
     
}else{
	
	/* INSERTA */        
	
        /* (SOLO POR AUTOCREATE) */

        if (isset($_POST['autocreate'])) 
        { 
        	/* (PASO A SER AUTOINCREMENTAL)
            $ssqlp = "SELECT max(id) as elid FROM tours";
            $result = mysql_query($ssqlp);	
            if($row = mysql_fetch_array($result)){
                    $elid = $row["elid"] + 1;
            }else{
                    $elid = 1;
            }
            */

            $ssqlp1 = "insert into tours_draft (iduser,user, date, state, version_xml) values ('".$_SESSION["usr"]."', '".mysql_real_escape_string($_SESSION["username"])."', now(),'draft', 0)";
			mysql_query($ssqlp1);
			$ssqlp = "SELECT max(id) as elid FROM tours_draft";
			$result = mysql_query($ssqlp);
			$row = mysql_fetch_array($result);
			$elid = $row["elid"];
			
			mysql_query("update tours_draft set friendly_url = '".$elid."', title = 'Untitled ".$elid."' where id = ".$elid);
							
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

            
}


//header("Location:edit_virtual_tour.php?id=".$elid);


?>
