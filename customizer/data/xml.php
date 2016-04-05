<?
ini_set("display_errors", 0);
require_once("../php/functions.php");

/* Parametros por querystring
 * d (1/0) consulta draft o published
 * c (1/0) muestra custom attrs
 * h = 0 oculta los hotspots
 * t (nombre) muestra un template particular
 * id el id del tour o del template
 * customizer (1/0) me dice si se esta llamando desde el customizer o desde el front
 */



session_start();
$user_id = $_SESSION['usr'];

$custom_attrs = 0;

$draft_subscript = '';
$template_id = '';

//Si recibo un 1 en c, devuelvo custom tags
if(isset($_GET['c']) && $_GET['c'] == 1){
	$custom_attrs = 1;
}

//Si recibo un 1 en d, devuelvo valores de draft
if(isset($_GET['d']) && $_GET['d'] == 1){
	$draft_subscript = '_draft';
}

$customizer = 0;
if(isset($_GET['customizer']) && $_GET['customizer'] == 1){
	$customizer = 1;
}


if($customizer == 1 && $user_id != '' || $customizer == 0){ //Si es llamado desde customizer, chequeo que esté logueado para poder segurizar el usuario en el select del tour

	//Si recibo un objeto específico, devuelvo el template, sino, el xml de un tour (agregar case para nuevos templates dentro de la funcion)
	switch ($_GET['t']){
		case 'skills':
			$template_id = $_GET['id'];
			$prev_tag_ident = 0;
			$print_xml .= get_template($_GET['t'], '', $prev_tag_ident);			
			
			break;
			
		case 'htspts':
			$template_id = $_GET['id'];
			$prev_tag_ident = 0;
			$print_xml .= get_template($_GET['t'], '', $prev_tag_ident);
		
			break;			

		case 'htspts_styles':
			$template_id = $_GET['id'];
			$prev_tag_ident = 0;
			$print_xml .= get_template($_GET['t'], '', $prev_tag_ident);
		
			break;
					
			
		default: //xml de un tour
			
			$id = $_GET['id'];
			
			//XML HEAD
			$prev_tag_ident = 0;
			$segment = 'HEAD';
			$print_xml .= '
			<!-- '.$segment.' -->
			';
			$print_xml .= get_xml($segment, '',$prev_tag_ident);
			
			//Toma de settings generales
			$prev_tag_ident = 0;
			$segment = 'GENERAL';
			$print_xml .= '
			<!-- '.$segment.' -->
			';
			$print_xml .= get_xml($segment, '',$prev_tag_ident);
			

			//DATOS PARTICULARES PARA EL XML EN CUSTOMIZER (Si recibo un 1 en customizer) NO SE INSERTA EN CUSTOMIZER_DRAFT CUANDO SE MANDA EL JSON A UPDATER.PHP POR NO TENER SEGMENT
			if($customizer == 1){
				$print_xml .= '<include url="%CURRENTXML%/customizerGlobals.xml" />';
			}
			
			
			
			//Toma de scenes y hotspots
			$segment = 'SCENES';
			$print_xml .= '
			<!-- '.$segment.' -->
			';
			$print_xml .= get_scenes();
			
			//Toma de skills
			$prev_tag_ident = 0;
			$segment = 'SKILLS';
			$print_xml .= '
			<!-- '.$segment.' -->
			';
			$print_xml .= get_xml($segment, '', $prev_tag_ident);
			
			//Toma de default skills (ELIMINADO, SE CARGAN AL CREARSE EL TOUR Y TIENE POSIBILIDAD DE ELIMINARLO)
			/*
			$prev_tag_ident = 0;
			$segment = 'DEFAULT SKILLS';
			$print_xml .= '
			<!-- '.$segment.' -->
			';
			$print_xml .= get_default_elements('skills', '', $prev_tag_ident);
			*/
			
			//Toma de free hotspots styles
			$prev_tag_ident = 0;
			$segment = 'FREE HOTSPOTS STYLES';
			$print_xml .= '
			<!-- '.$segment.' -->
			';
			$print_xml .= get_free_htpts_styles();
			
			//Toma de hotspots styles
			$prev_tag_ident = 0;
			$segment = 'HOTSPOTS STYLES';
			$print_xml .= '
			<!-- '.$segment.' -->
			';
			$print_xml .= get_xml($segment, '', $prev_tag_ident);
			
			
			$print_xml .= '</krpano>';			
			break;
		
	}
	

	
	//Modificaciones particulares 
	//Lo que tengo que devolver si no se pide custom
	if($custom_attrs != 1){
		$print_xml = str_replace('<skill>', '', $print_xml);
		$print_xml = str_replace('</skill>', '', $print_xml);
	}

	$print_xml = str_replace('#env#', $environment, $print_xml);
	$print_xml = str_replace('#user_id#', $user_id, $print_xml);
	
	
	//Fin modificaciones particulares
	
	
	
	
	if(isset($_GET['format']) && $_GET['format'] == 'txt'){
		echo '<pre>'.htmlentities($print_xml).'</pre>'; //as txt
	}else{
		echo $print_xml; //as xml
	}
	
	
}


function get_free_htpts_styles(){
	global $segment;
	global $custom_attrs;
	
	$ssqlp = "SELECT * FROM customizer_free_htspts_styles order by ord";
	$result = mysql_query($ssqlp);
	
	while($row = mysql_fetch_array($result)){
		
		$devolver_final = 1;
		
		//Si hay un nuevo style_id, imprimo apertura
		if($style_id != $row["style_id"]){
		
			//si había uno antes, lo cierro
			if($style_id != ''){
				$final_xml .= '/>';
			}
			
			$style_id = $row["style_id"];
		
			$kind = $row["kind"];
		
			if($row["in_tag_code"] != ''){
				$in_tag_code = '
				'.$row["in_tag_code"].'
				';
			}
			
			
			if($custom_attrs == 1){
				$final_xml .= '
				<style segment="'.$segment.'" kind="'.$kind.'"';
			}else{
				$final_xml .= '
				<style';
			}
		}
		
		//Imprimo attibutes y valor dentro del tag
		$final_xml .= ' '.$row["attr"].'="'.$row["value"].'"';
				
	};
		
	if($devolver_final == 1){
		$final_xml .= '/>
		';
	}
	
	return $final_xml;		

	
}

/* SOLO ERA PARA TRAER LOS SKILLS POR DEFECTO, PERO EL USUARIO PUEDE BORRARLOS ASIQUE SE INSERTAN EN LA CREACIÓN DEL TOUR Y NO LOS FORZAMOS MAS ACA
function get_default_elements ($template, $kind, $prev_tag_ident){
	global $id;
	global $template_id;
	global $draft_subscript;
	global $custom_attrs;
	
	switch ($template){
		case 'skills': //levanto los id de los elementos a devolver (son los que no tenga agregados ya al tour)
			$ssqlp = "SELECT skill_id as template_id FROM customizer_templates_skills where kind not in (select kind from customizer".$draft_subscript." where idtour = '".$id."' and segment = 'SKILLS') and add_by_default = 1 group by skill_id order by skill_id";
			break;

	}
	
	$result = mysql_query($ssqlp);
	
	
	while($row = mysql_fetch_array($result)){
		$template_id = $row["template_id"];
		$final_xml .= get_template($template, $kind, $prev_tag_ident);
	}
	

	return $final_xml;
}
*/

function get_template($template, $kind, $prev_tag_ident){ 
	global $user_id;
	global $template_id;
	global $cdn;
	global $custom_attrs;
	global $id; //el id del tour para los elementos por default (no se usa para devolver solo templates)

	$final_xml = '';
	$tag_ident = '';
	$tag_name = '';

	$devolver_final = 0;
	$in_tag_code = '';

	if ($kind != ''){
		$kind_condition = " kind = '".$kind."' and ";
	}
	
	
	switch ($template){
		case 'skills':
			$ssqlp = "SELECT *, skill_id as template_id FROM customizer_templates_skills where skill_id = ".$template_id." and prev_tag_ident = ".$prev_tag_ident." order by tag_ident";
			break;
		case 'htspts':
			$ssqlp = "SELECT *, htspt_id as template_id FROM customizer_templates_htspts where htspt_id = ".$template_id." and prev_tag_ident = ".$prev_tag_ident." order by tag_ident";
			break;
		case 'htspts_styles':
			$ssqlp = "SELECT *, style_id as template_id FROM customizer_free_htspts_styles where style_id = ".$template_id." and prev_tag_ident = ".$prev_tag_ident." order by ord";
			break;
	}
	
	
	
	$result = mysql_query($ssqlp);

	
	while($row = mysql_fetch_array($result)){
		

		$devolver_final = 1;

		//Si hay un nuevo tag, imprimo apertura (si el tag_name != '', imprimo cierre antes)
		if($tag_ident != $row["tag_ident"] || $tag_name != $row["tag_name"] || $kind != $row["kind"]){

			if($tag_ident != ''){ //si ya había tag, lo cierro
				$final_xml .= '>';
				$final_xml .= $in_tag_code;
				$final_xml .= get_template($template, $kind, $tag_ident);
				$final_xml .= '</'.$tag_name.'>
				';
				$in_tag_code = '';
			}
			
			$template_id = $row["template_id"];
			$tag_ident = $row["tag_ident"];
			$tag_name = $row["tag_name"];
			$segment = $row["segment"];

			$kind = $row["kind"];


			if($custom_attrs == 1){
				$final_xml .= '
				<'.$tag_name.' template_id="'.$template_id.'" tag_ident="'.$tag_ident.'" prev_tag_ident="'.$prev_tag_ident.'" segment="'.$segment.'" kind="'.$kind.'"';
			}else{
				$final_xml .= '
				<'.$tag_name;
			}

		}

		
		//Imprimo attibutes y valor dentro del tag (si es text, no)
		if($row["attr"] == 'text'){ //si es text, es codigo dentro del tag, lo almaceno pero no lo muestro, se inserta en el cierre del tag
			$in_tag_code = '
			'.$row["value"].'
			';
		}else{
			if($row["attr"] != ''){ //Si el atributo no es vacío, imprimo el par attr - value, sino, solo se imprime el tag, por lo que salteo la impresion de values
				$final_xml .= ' '.$row["attr"].'="'.$row["value"].'"';
			}
		}




	};

	if($devolver_final == 1){
		$final_xml .= '>';
		$final_xml .= $in_tag_code;
		$final_xml .= get_template($template, $kind, $tag_ident);
		$final_xml .= '</'.$tag_name.'>
		';
		$in_tag_code = '';
	}

	return $final_xml;

}



function get_xml($segment, $kind, $prev_tag_ident){
	global $user_id;
	global $id;
	global $draft_subscript;
	global $segment;
	global $cdn;
	global $custom_attrs;
	global $customizer;
	
	$final_xml = '';
	$tag_ident = '';
	$tag_name = '';

	$devolver_final = 0;
	$in_tag_code = '';
	
	$kind_condition = '';
	
	if ($kind != ''){$kind_condition = " kind = '".$kind."' and ";}
	
	if($customizer == 1){ //Si es llamado desde el front, no segurizo el usuario
		$ssqlp = "SELECT * FROM customizer".$draft_subscript." where ".$kind_condition." segment = '".$segment."' and idtour = '".$id."' and user_id = ".$user_id." and prev_tag_ident = ".$prev_tag_ident." order by kind, tag_ident";
	}else{
		$ssqlp = "SELECT * FROM customizer".$draft_subscript." where ".$kind_condition." segment = '".$segment."' and idtour = '".$id."' and prev_tag_ident = ".$prev_tag_ident." order by kind, tag_ident";
	}	

	$result = mysql_query($ssqlp);

	
	while($row = mysql_fetch_array($result)){
		
		$devolver_final = 1;

		//Si hay un nuevo tag, imprimo apertura (si el tag_name != '', imprimo cierre antes)
		if($tag_ident != $row["tag_ident"] || $tag_name != $row["tag_name"] || $kind != $row["kind"]){

			if($tag_ident != ''){ //si ya había tag, lo cierro
				$final_xml .= '>';
				$final_xml .= $in_tag_code;
				$final_xml .= get_xml($segment, $kind, $tag_ident);
				if($tag_name!='krpano'){$final_xml .= '</'.$tag_name.'>
				';}
				$in_tag_code = '';
			}
			
			$tag_ident = $row["tag_ident"];
			$tag_name = $row["tag_name"];
			$segment = $row["segment"];
			$template_id = $row["template_id"];
			
			$kind = $row["kind"];
			
			
			if($custom_attrs == 1){
				$final_xml .= '
				<'.$tag_name.' template_id="'.$template_id.'" tag_ident="'.$tag_ident.'" prev_tag_ident="'.$prev_tag_ident.'" segment="'.$segment.'" kind="'.$kind.'"';
			}else{
				$final_xml .= '
				<'.$tag_name;
			}
			
		}

		//Imprimo attibutes y valor dentro del tag (si es text, no)
		if($row["attr"] == 'text'){ //si es text, es codigo dentro del tag, lo almaceno pero no lo muestro, se inserta en el cierre del tag
			$in_tag_code = '
			'.$row["value"].'
			';
		}else{
			if($row["attr"] != ''){ //Si el atributo no es vacío, imprimo el par attr - value, sino, solo se imprime el tag, por lo que salteo la impresion de values
				$final_xml .= ' '.$row["attr"].'="'.$row["value"].'"';
			}
		}
		
		


	};

	if($devolver_final == 1){
		$final_xml .= '>';
		$final_xml .= $in_tag_code;
		$final_xml .= get_xml($segment, $kind, $tag_ident);
		if($tag_name!='krpano'){$final_xml .= '</'.$tag_name.'>
		';}
		$in_tag_code = '';
	}
	
	return $final_xml;

}


function get_scenes(){
	global $user_id;
	global $id;
	global $draft_subscript;
	global $cdn;
	global $custom_attrs;

	$final_data = '';

	//SCENES

	$ssqlp = "SELECT panosxtour".$draft_subscript.".*, panos.name as filename FROM panos, panosxtour".$draft_subscript." where panosxtour".$draft_subscript.".idpano = panos.id and panosxtour".$draft_subscript.".idtour = ".$id." order by ord";

	//echo $ssqlp;
	
	$result = mysql_query($ssqlp);
	while($row = mysql_fetch_array($result)){
		
		if($custom_attrs == 1){
			$segment_html = ' segment="SCENES"';
			$id_html = ' scene_id="'.$row["id"].'"';
			$scene_filename = ' scene_filename="'.$row["filename"].'"';
		}else{
			$segment_html = '';
			$id_html = '';
			$scene_filename = '';
		}
		
		
		$final_data.= '
		<scene'.$segment_html.$id_html.$scene_filename.' name="scene_'.$row["id"].'" urlname="'.htmlspecialchars($row["urlname"]).'" title="'.htmlspecialchars($row["name"]).'" onstart=""  thumburl="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/thumb200x100.jpg" thumbBigUrl="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/thumb500x250.jpg" lat="'.htmlspecialchars($row["lat"]).'" lng="'.htmlspecialchars($row["lng"]).'" description="'.htmlspecialchars($row["description"]).'" heading="'.htmlspecialchars($row["heading"]).'">
			<view'.$segment_html.' hlookat="'.$row["hlookat"].'" vlookat="'.$row["vlookat"].'" fovtype="'.$row["fovtype"].'" fov="'.$row["fov"].'" fovmin="'.$row["fovmin"].'" fovmax="'.$row["fovmax"].'" limitview="'.$row["limitview"].'"  />

			<preview'.$segment_html.' url="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/preview.jpg" />

			<image'.$segment_html.'>

				<cube'.$segment_html.' url="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/pano_%s.jpg" />

				<mobile'.$segment_html.'>
					<cube'.$segment_html.' url="'.$cdn.'/panos/'.$row["idpano"].'/pano.tiles/mobile_%s.jpg" />
				</mobile>

			</image>';


		//HOTSPOTS (si $_GET['h'] != 0 tomo hotspots, sino, no)
		if(!isset($_GET['h']) || $_GET['h'] != 0){
			
			$ssqlp_htsp = "SELECT * FROM hotspots".$draft_subscript." where scene_id = ".$row["id"];
			$result_htsp = mysql_query($ssqlp_htsp);
			while($row_htsp = mysql_fetch_array($result_htsp)){
	
	
	
				$final_data.= '
				<hotspot'.$segment_html.' template_id="'.$row_htsp["template_id"].'" style="'.$row_htsp["style"].'" kind="'.htmlspecialchars($row_htsp["type"]).'" name="'.htmlspecialchars($row_htsp["name"]).'" ath="'.$row_htsp["ath"].'" atv="'.$row_htsp["atv"].'"';
	
				switch ($row_htsp["type"]) {
					case "arrow":
						$final_data.= ' linkedscene="'.$row_htsp["extra_linkedscene"].'" rotate="'.$row_htsp["extra_rotate"].'" tooltip="'.htmlspecialchars($row_htsp["extra_tooltip"], ENT_QUOTES).'"/>';
						break;
					case "info":
						$final_data.= ' infotitle="'.str_replace("'", "´", htmlspecialchars($row_htsp["extra_infotitle"])).'" infotext="'.str_replace("'", "´", htmlspecialchars($row_htsp["extra_infotext"])).'" />';
						break;
					case "photo":
						$final_data.= ' pic="'.htmlspecialchars($row_htsp["extra_photourl"], ENT_QUOTES).'" tooltip="'.htmlspecialchars($row_htsp["extra_tooltip"], ENT_QUOTES).'" />';
						break;
					case "media":
						$final_data.= ' video="'.htmlspecialchars($row_htsp["extra_photourl"], ENT_QUOTES).'" tooltip="'.htmlspecialchars($row_htsp["extra_tooltip"], ENT_QUOTES).'" />';
						break;					
					case "link":
						$final_data.= ' linkurl="'.$row_htsp["extra_linkurl"].'" target="'.$row_htsp["extra_target"].'" tooltip="'.htmlspecialchars($row_htsp["extra_tooltip"], ENT_QUOTES).'" />';
						break;
				}
	
			}
		}

		$final_data .= 	'
		</scene>';
	}

	return $final_data;

}

?>