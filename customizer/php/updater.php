<?php
ini_set("display_errors", 0);

require_once("functions.php");
include("make_tour_files.php");

$iduser =  $_SESSION['usr'];
$action = $_POST["action"];
$id = $_POST["id"];

switch($action){
	case "reset_tour_thumb":
		
		//chequeo que el tour sea mio
		$ssqlp1 = "SELECT id from tours_draft WHERE iduser = '".$iduser."' and id = ".$id;
		
		//echo $ssqlp1;
		$result = mysql_query($ssqlp1);
		
		if($row = mysql_fetch_array($result)){
			
			//Replace with 1st pano thumb
			$ssqlthumb = "SELECT * FROM panosxtour_draft where idtour = ".$id." ORDER BY ord LIMIT 1";
			$resultthumb = mysql_query($ssqlthumb);
			$rowthumb = mysql_fetch_array($resultthumb);
			
			mysql_query("update tours_draft set tour_thumb_custom = 0, tour_thumb_path = '".$cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/'."' where id = ".$id." and iduser = '".$iduser."'");
			
			$state = 'saved';
		
		}else{
			$state = "ERROR";
		}
		
		echo json_encode(array(
				'state' => $state,
				'date' => date ('m/d/o g:i a'),
				'path' => $cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/thumb200x100.jpg'				
		));		
		
	break;
		
	
	case "del_scene": //Eliminacion de SCENE ------------------------------------------------------------------------------------------

		$draft = $_POST["d"];
		
		if(strpos ($id, '|')){ //Verifico si hay varios para laburo en bach
			$ids = explode('|',$id);
			foreach ($ids as $elid) {
				if($elid != ''){
					del_scene($elid);
				}
			}
		}else{
			del_scene($id);
		}		
		
		echo json_encode(array(
				'state' => 'saved',
				'date' => date ('m/d/o g:i a')
		));
		

		
	break;
	
	case "del_pano": //Eliminacion de PANO
		
		//Checkeo que la pano sea mia
		$ssqlp1 = "SELECT id FROM panos WHERE user = '".$iduser."' and id = ".$id;
		$result = mysql_query($ssqlp1);
		if($row = mysql_fetch_array($result)){
			$ssqlp1 = "delete from panosxtour_draft where idpano = ".$id;
			mysql_query($ssqlp1);
			
			$ssqlp1 = "delete from panosxtour where idpano = ".$id;
			mysql_query($ssqlp1);
			
			$ssqlp1 = "delete from panos where id = ".$id;
			mysql_query($ssqlp1);
			
			//Borro cloud
			$salida = shell_exec('s3cmd -c /var/www/'.$bucket_config_file.' del s3://'.$cdn_string.'/panos/'.$id.' --recursive 2>&1');

			
			
			$state = 'saved';			
		}else{
			$state = "error";
		}
	
		echo json_encode(array(
				'state' => $state,
				'date' => date ('m/d/o g:i a')
		));		
		
		
	break;
	default: //Update del customizer general ------------------------------------------------------------------------------------------
		
		$json = $_POST["json"];
	
		$scene_order = 0;
		
		$json_array = json_decode($json, true);
		$tour = $json_array["krpano"]["datatour"];
		$settings = $json_array["krpano"]["settings"];
		
		$draft_subscript = '';
		//Si el tour esta en draft, meto el subscript
		if($tour["state"] == 'draft'){
			$draft_subscript = '_draft';
		}
		
		//Update de datos del tour
		//Desde Settings
		$title = mysql_escape_string($settings["_title"]);
		$description = mysql_escape_string($settings["_description"]);
		$lat = mysql_escape_string($settings["_lat"]);
		$lon = mysql_escape_string($settings["_long"]);
		$location = mysql_escape_string($settings["_location_heading"]);
		
		//Desde datatour
		$category = mysql_escape_string($tour["category"]);
		$tags = mysql_escape_string($tour["tags"]);
		$allow_comments = mysql_escape_string($tour["allow_comments"]);
		$allow_social = mysql_escape_string($tour["allow_social"]);
		$allow_embed = mysql_escape_string($tour["allow_embed"]);
		$allow_votes = mysql_escape_string($tour["allow_votes"]);
		$privacy = mysql_escape_string($tour["privacy"]);
		$friendlyURL = mysql_escape_string($tour["friendlyURL"]);
		$skin_id = mysql_escape_string($tour["skin_id"]);
		$enable_title = mysql_escape_string($tour["enable_title"]);
		$enable_avatar = mysql_escape_string($tour["enable_avatar"]);
		$thumb_width = mysql_escape_string($tour["thumb_width"]);
		$thumb_height = mysql_escape_string($tour["thumb_height"]);
		$thumb_margin = mysql_escape_string($tour["thumb_margin"]);
		$show_lat_lng = mysql_escape_string($tour["show_lat_lng"]);
		
		$ssqlp = "update tours".$draft_subscript." set location = '".$location."', description = '".$description."', date_updated = now(), title = '".$title."', category='".$category."', tags='".$tags."', allow_comments='".$allow_comments."', allow_social='".$allow_social."', allow_embed='".$allow_embed."', allow_votes='".$allow_votes."', privacy='".$privacy."', friendly_url='".$friendlyURL."', lat='".$lat."', lon='".$lon."', skin_id='".$skin_id."', enable_title='".$enable_title."', enable_avatar='".$enable_avatar."', thumb_width='".$thumb_width."', thumb_height='".$thumb_height."', thumb_margin='".$thumb_margin."', show_lat_lng='".$show_lat_lng."' where id = '".$id."' and iduser = '".$iduser."'";
		
		//echo $ssqlp;
		//echo 'ACA ';
		
		mysql_query($ssqlp);
		
		
		/* AGREGO TAGS */
		
		$pieces = explode(",", $tags);
		foreach($pieces as $piece => $value){
		
			$ssqlp = "SELECT tag FROM tags where tag = '".trim($value)."'";
			$result = mysql_query($ssqlp);
			if(!($row = mysql_fetch_array($result))){
				mysql_query("insert into tags (tag) values ('".trim($value)."')");
		
			}
		}		
		
		
		//Elimino la data del customizer
		mysql_query("delete from customizer".$draft_subscript." where idtour = '".$id."' and user_id = '".$iduser."'");
		
		//Update de customizer y scenes - IMPORTANTE: LOS TAGS SIN SEGMENT NO SON INSERTADOS (CON ESTO EVITO QUE SE INSERTEN TAGS EXPLUSIVOS PARA LA VERSION CUSTOMIZER DEL TOUR)
		recorro ($json_array, '');
		
		
		/* VUELCO O BORRO EN TABLAS PRINCIPALES / CREO ARCHIVOS DEL TOUR O LO ELIMINO DEPENDIENDO DE DRAFT O PUBLISH */
		if(isset($_POST["tolive"])){
			
			if($_POST["tolive"] == '1'){ //PUBLICACION
				//echo "OK";
				//Tomo si el tour es brand new (es la 1era vez que se publica) para el mixpanel
				$ssqlpbn = "SELECT brand_new FROM tours_draft where id = ".$id;
				$resultbn = mysql_query($ssqlpbn);
				$rowbn = mysql_fetch_array($resultbn);
				$brand_new = $rowbn["brand_new"];
			
			
				//Borro lo que haya en publish y subo lo nuevo
				mysql_query("delete from hotspots where scene_id in (select id from panosxtour where idtour = ".$id.")");
				mysql_query("delete from panosxtour where idtour = ".$id);
				mysql_query("delete from tours where id = ".$id);
				mysql_query("delete from customizer where idtour = ".$id);
			
				mysql_query("insert into tours select id, title, friendly_url, location, description, category, tags, lat, lon, allow_comments, allow_social, allow_embed, allow_votes, privacy, likes, views, iduser, user, comments, date, date_updated, 'publish' as state, version_xml, brand_new, enable_avatar, skin_id, enable_title, thumb_width, thumb_height, thumb_margin, priority, tour_thumb_path, tour_thumb_custom, show_lat_lng from tours_draft where id = ".$id);
				mysql_query("insert into panosxtour select * from panosxtour_draft where idtour = ".$id);
				mysql_query("insert into hotspots select * from hotspots_draft where scene_id in (select id from panosxtour_draft where idtour = ".$id.")");
				mysql_query("insert into customizer select * from customizer_draft where idtour = ".$id);
			
			
				//paso Brand_new a 0
				mysql_query("update tours_draft set brand_new = 0  where id = ".$id);
			
				make_tour_files($id);
			
				if ($brand_new == 1 ){
					setMaxPriority($id);
				}
			}
			
			if($_POST["tolive"] == '-1'){  //DESPUBLICACION
			
				//Si despublico, traigo las escenas que tenga desde publish para que no tome algun draft viejo (hotspots y los datos del tour los actualiza arriba)
				mysql_query("delete from panosxtour_draft where idtour = ".$id);
				mysql_query("insert into panosxtour_draft select * from panosxtour where idtour = ".$id);
		
				mysql_query("delete from hotspots where scene_id in (select id from panosxtour where idtour = ".$id.")");
				mysql_query("delete from panosxtour where idtour = ".$id);
				mysql_query("delete from tours where id = ".$id);
				mysql_query("delete from customizer where idtour = ".$id);
		
				//borro local
				$tourfolder = '../../tours/'.$id.'/';
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
	
		
		
		
		echo json_encode(array(
			'state' => 'saved',
			'date' => date ('m/d/o g:i a')
		));
		
		

	break;
}

function del_scene($id){
	
	global $iduser;
	//chequeo si la scene es mia y tomo id del tour
	$ssqlp1 = "SELECT panosxtour_draft.idtour FROM panosxtour_draft, panos WHERE panosxtour_draft.idpano = panos.id and panos.user = '".$iduser."' and panosxtour_draft.id = ".$id;
	
	//echo $ssqlp1;
	
	$result = mysql_query($ssqlp1);
	
	if($row = mysql_fetch_array($result)){
	
		$id_tour = $row["idtour"];
	
		//borrar escena
		$ssqlp1 = "delete from panosxtour_draft where id = ".$id;
		mysql_query($ssqlp1);
	
		//borrar hotspots de esta scene
		$ssqlp1 = "delete from hotspots_draft where scene_id = ".$id;
		mysql_query($ssqlp1);
	
		//borrar hotspots arrow que apunten a esta scene
		$ssqlp1 = "delete from hotspots_draft where (type = 'link' or type = 'arrow') and extra_linkedscene = 'scene_".$id."'";
		//echo $ssqlp1;
		mysql_query($ssqlp1);
	
		//si no está en draft (viene de published) borro en published
		if ($draft != 1){
			$ssqlp1 = "delete from panosxtour where id = ".$id;
			mysql_query($ssqlp1);
	
			//borrar hotspots de esta scene
			$ssqlp1 = "delete from hotspots where scene_id = ".$id;
			mysql_query($ssqlp1);
	
			//borrar hotspots arrow que apunten a esta scene
			$ssqlp1 = "delete from hotspots where (type = 'link' or type = 'arrow') and extra_linkedscene = 'scene_".$id."'";
			mysql_query($ssqlp1);
	
			//verifico si queda sin escenas en published para borrarlo de tours (quedarìa en draft)
			$ssqlp1 = "SELECT * FROM panosxtour WHERE idtour = ".$id_tour;
			$result = mysql_query($ssqlp1);
			if(!($row = mysql_fetch_array($result))){
				$ssqlp1 = "delete from tours where id = ".$id_tour;
				mysql_query($ssqlp1);
				//borro local
				$tourfolder = '../../tours/'.$id_tour.'/';
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
	
	}
	
}

function recorro($matriz, $tag_name){

	global $id;
	global $iduser;
	global $draft_subscript;


	$in_tag_text = '';
	$tag_ident = '';
	$prev_tag_ident = '';
	$segment = '';
	$kind = '';
	$template_id = '';

	foreach($matriz as $key=>$value){

		if (is_array($value)){
			//si es un array, lo recorro

			//echo 'key:'. $key;
			//echo '<br>';

			if(is_int($key)){ //Si el key es numerico, conservo el key del parent
				$key = $tag_name;
			}

			if($key == 'scene'){ //Si es una scene, lo mando al update scene
				update_scene($value);
			}else{
				if($key != 'datatour'){ //Si es el array de datatour no lo insertamos en customizer_draft
					recorro($value, $key);
				}
			}



		}else{

			//echo 'key:'. $key;
			//echo '<br>';

			//si es un elemento lo tomo
			//Saco los "_" que tenga al principio
			while (substr($key, 0, 1) == '_') {
				$key = substr($key, 1);
			}




			switch ($key){
				case 'tag_ident':
					$tag_ident = $value;
					break;
				case 'prev_tag_ident':
					$prev_tag_ident = $value;
					break;
				case 'segment':
					$segment = $value;
					break;
				case 'kind':
					$kind = $value;
					break;
				case 'template_id':
					$template_id = $value;
					break;
				default:
					//si es otro tag, lo inserto, EXCEPTO QUE SEA FREE HOTSPOTS STYLES, SCENES o DATATOUR
					if($segment != '' && $segment != 'FREE HOTSPOTS STYLES' && $segment != 'SCENES'){
					$ssqlp = "insert into customizer".$draft_subscript." (idtour, segment, kind, tag_name, tag_ident, prev_tag_ident, attr, value, user_id, template_id) values ('".$id."', '".$segment."', '".$kind."', '".$tag_name."', '".$tag_ident."', '".$prev_tag_ident."', '".mysql_escape_string($key)."', '".mysql_escape_string($value)."', '".$iduser."', '".$template_id."')";
					mysql_query($ssqlp);
					//echo $ssqlp."<br>";

					if($segment == 'SKILLS'){//Si es skill, inserto el tag principal (si es que no existe)
						$ssqlp = "SELECT * FROM customizer".$draft_subscript." where segment = 'SKILLS' and kind = '".$kind."' and template_id = '".$template_id."' and tag_name = 'skill' and idtour = '".$id."'";
						$result = mysql_query($ssqlp);
						if(!($row = mysql_fetch_array($result))){
							$ssqlp = "insert into customizer".$draft_subscript." (idtour, segment, kind, tag_name, tag_ident, prev_tag_ident, attr, value, user_id, template_id) values ('".$id."', 'SKILLS', '".$kind."', 'skill', '1', '0', '', '', '".$iduser."', '".$template_id."')";
							mysql_query($ssqlp);
						}
					}
				}

				break;
			}

		}

	}

}



function update_scene($matriz){

	global $id;
	global $iduser;
	global $draft_subscript;
	global $scene_order;
	global $cdn;

	foreach ($matriz as $scene){

		//echo "ARRAY:".is_array($scene);
		//echo "_scene_id:".$scene["_scene_id"];

		$hotspots = $scene["hotspot"];

		$scene_id = $scene["_scene_id"];

		$urlname = mysql_escape_string($scene["_urlname"]);
		$title = mysql_escape_string($scene["_title"]);
		$lat = mysql_escape_string($scene["_lat"]);
		$lng = mysql_escape_string($scene["_lng"]);
		$description = mysql_escape_string($scene["_description"]);
		$heading = mysql_escape_string($scene["_heading"]);

		$hlookat = mysql_escape_string($scene["view"]["_hlookat"]);
		$vlookat = mysql_escape_string($scene["view"]["_vlookat"]);
		$fovtype = mysql_escape_string($scene["view"]["_fovtype"]);
		$fov = mysql_escape_string($scene["view"]["_fov"]);
		//$maxpixelzoom = mysql_escape_string($scene["view"]["_maxpixelzoom"]);
		$fovmin = mysql_escape_string($scene["view"]["_fovmin"]);
		$fovmax = mysql_escape_string($scene["view"]["_fovmax"]);
		$limitview = mysql_escape_string($scene["view"]["_limitview"]);

		//con maxpixalzoom
		//$ssqlp = "update panosxtour".$draft_subscript." set ord = '".$scene_order."', urlname='".$urlname."', name='".$title."', lat='".$lat."', lng='".$lng."', description='".$description."', heading='".$heading."', hlookat='".$hlookat."', vlookat='".$vlookat."', fovtype='".$fovtype."', fov='".$fov."', maxpixelzoom='".$maxpixelzoom."', fovmin='".$fovmin."', fovmax='".$fovmax."', limitview='".$limitview."' where id = ".$scene_id;
		
		$ssqlp = "update panosxtour".$draft_subscript." set ord = '".$scene_order."', urlname='".$urlname."', name='".$title."', lat='".$lat."', lng='".$lng."', description='".$description."', heading='".$heading."', hlookat='".$hlookat."', vlookat='".$vlookat."', fovtype='".$fovtype."', fov='".$fov."', fovmin='".$fovmin."', fovmax='".$fovmax."', limitview='".$limitview."' where id = ".$scene_id;

		mysql_query($ssqlp);

		
		//Update de thumb_path, si no esta customizado
		//Check if Customized:
		$ssqlthumb = "SELECT * FROM tours".$draft_subscript." where id = ".$id;
		$resultthumb = mysql_query($ssqlthumb);
		$rowthumb = mysql_fetch_array($resultthumb);
		if($rowthumb["tour_thumb_custom"] == 0){
			//If not customized, replace with 1st pano thumb
			$ssqlthumb = "SELECT * FROM panosxtour".$draft_subscript." where idtour = ".$id." ORDER BY ord LIMIT 1";
			$resultthumb = mysql_query($ssqlthumb);
			$rowthumb = mysql_fetch_array($resultthumb);
		
			mysql_query("update tours".$draft_subscript." set tour_thumb_path = '".$cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/'."' where id = ".$id);
		
		}		
		
		
		//Si hay hotspots, los inserto (borro los actuales antes)
		mysql_query("delete from hotspots".$draft_subscript." where scene_id = ".$scene_id);
		if(sizeof($hotspots) > 0){
			update_hotspots($hotspots, $scene_id);
		}

		$scene_order++;
	}

}



function update_hotspots($hotspots, $scene_id){

	global $id;
	global $iduser;
	global $draft_subscript;
	global $ssqlp_htspts;

	foreach($hotspots as $key=>$value){

		if (is_int($key)){ //si el key es un nro, estoy en un array de hotspots, sino, estoy dentro de uno (si hay mas de 1 hotspot, no recibo array de hotspots, sino los atributos directamente)

			$ssqlp_htspts = '';

			update_hotspots($value, $scene_id);  //Si es un array, es un hotspot (hay mas de 1) lo recorro

		}else{

			if($ssqlp_htspts == ''){ //Si $ssqlp_htspts no es vacío, estoy recorriendo elementos del mismo hotspot, por lo que no hago nada, solo lo hago al ingresar al hotspot por 1era vez

				$htsp_type = $hotspots["_kind"];

				switch ($htsp_type) {
					case "arrow":
						$ssqlp_htspts = "insert into hotspots".$draft_subscript." (scene_id, name, style, type, template_id, ath, atv, extra_linkedscene, extra_rotate, extra_tooltip) values ('".$scene_id."', '".$hotspots["_name"]."', '".$hotspots["_style"]."', '".$hotspots["_kind"]."', '".$hotspots["_template_id"]."', '".$hotspots["_ath"]."', '".$hotspots["_atv"]."', '".$hotspots["_linkedscene"]."', '".$hotspots["_rotate"]."', '".$hotspots["_tooltip"]."')";
						break;
					case "info":
						$ssqlp_htspts = "insert into hotspots".$draft_subscript." (scene_id, name, style, type, template_id, ath, atv, extra_infotitle, extra_infotext) values ('".$scene_id."', '".$hotspots["_name"]."', '".$hotspots["_style"]."', '".$hotspots["_kind"]."', '".$hotspots["_template_id"]."', '".$hotspots["_ath"]."', '".$hotspots["_atv"]."', '".$hotspots["_infotitle"]."', '".$hotspots["_infotext"]."')";
						break;
					case "photo":
						$ssqlp_htspts = "insert into hotspots".$draft_subscript." (scene_id, name, style, type, template_id, ath, atv, extra_photourl, extra_tooltip) values ('".$scene_id."', '".$hotspots["_name"]."', '".$hotspots["_style"]."', '".$hotspots["_kind"]."', '".$hotspots["_template_id"]."', '".$hotspots["_ath"]."', '".$hotspots["_atv"]."', '".$hotspots["_pic"]."', '".$hotspots["_tooltip"]."')";
						break;
					case "media":
						$ssqlp_htspts = "insert into hotspots".$draft_subscript." (scene_id, name, style, type, template_id, ath, atv, extra_photourl, extra_tooltip) values ('".$scene_id."', '".$hotspots["_name"]."', '".$hotspots["_style"]."', '".$hotspots["_kind"]."', '".$hotspots["_template_id"]."', '".$hotspots["_ath"]."', '".$hotspots["_atv"]."', '".$hotspots["_video"]."', '".$hotspots["_tooltip"]."')";
						break;
					case "link":
						$ssqlp_htspts = "insert into hotspots".$draft_subscript." (scene_id, name, style, type, template_id, ath, atv, extra_linkurl, extra_tooltip, extra_target) values ('".$scene_id."', '".$hotspots["_name"]."', '".$hotspots["_style"]."', '".$hotspots["_kind"]."', '".$hotspots["_template_id"]."', '".$hotspots["_ath"]."', '".$hotspots["_atv"]."', '".$hotspots["_linkurl"]."', '".$hotspots["_tooltip"]."', '".$hotspots["_target"]."')";
						break;
				}



				//echo $ssqlp_htspts."<br>";
				mysql_query($ssqlp_htspts);
			}
		}
	}
}
?>