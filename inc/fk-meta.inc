<?
	switch ($meta_type){
		case "tour": //Páginas de tours:
			if($description == ''){
				$meta_tour_description = "Create, customize and share virtual tours with 360 panoramas from your 360 camera";
			}else{
				$meta_tour_description = $description.' | Spinattic.com 360 virtual tours.';
			}

			//Get thumb path
			//$meta_ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$id." ORDER BY ord LIMIT 1";
			$meta_ssqlthumb = "SELECT * FROM tours where id = ".$id;
			$meta_resultthumb = mysql_query($meta_ssqlthumb);
			$meta_rowthumb = mysql_fetch_array($meta_resultthumb);
			//$meta_thumb_path = $cdn.'/panos/'.$meta_rowthumb["idpano"].'/pano.tiles/thumb900x450.jpg';			
			$meta_thumb_path = $meta_rowthumb["tour_thumb_path"].'thumb900x450.jpg';
			
			echo '
			<meta property="og:title" content="'.$title.' by '.$user.' | Spinattic.com"/>
			<meta property="og:site_name" content="Spinattic"/>
			<meta property="og:url" content="'.$http.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'"/>
			<meta property="og:description" content="'.$meta_tour_description.'"/>
			<meta property="og:image" content="'.$meta_thumb_path.'"/>
			<meta property="og:image:type" content="image/jpeg">
			<meta property="og:image:width" content="900">
			<meta property="og:image:height" content="450">		
			<link rel="image_src" href="'.$meta_thumb_path.'" >';
			break;
			
		case "user": //Páginas de profile de usuario:
			
			$meta_user_description = "A proud photographer and Spinattic member";
			$meta_avatar_path = $http.$_SERVER["HTTP_HOST"].'/images/users/'.$avatar;
			
			echo '
			<meta property="og:title" content="'.$username.' | Spinattic.com"/>
			<meta property="og:site_name" content="Spinattic"/>
			<meta property="og:url" content="'.$http.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'"/>
			<meta property="og:description" content="'.$meta_user_description.'"/>
			<meta property="og:image" content="'.$meta_avatar_path.'"/>
			<meta property="og:image:type" content="image/jpeg">
			<meta property="og:image:width" content="107">
			<meta property="og:image:height" content="107">				
			<link rel="image_src" href="'.$meta_avatar_path.'" >';
			break;
			
		case "blog": //Páginas de post :
			$meta_post_description = $post_title;
			
			if($post_image != ''){
				$meta_logo_path = $http.$_SERVER["HTTP_HOST"].'/images/blog/'.$post_image;
			}else{
				$meta_logo_path = $http.$_SERVER["HTTP_HOST"].'/images/fb-logo.jpg';
			}
		
			echo '
			<meta property="og:title" content="'.$meta_post_description.' | Spinattic.com"/>
			<meta property="og:site_name" content="Spinattic"/>
			<meta property="og:url" content="'.$http.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'"/>
			<meta property="og:description" content="A journal about creating virtual tours and the professionals leading the innovation behind them."/>
			<meta property="og:image" content="'.$meta_logo_path.'"/>
			<link rel="image_src" href="'.$meta_logo_path.'" >';
			break;	
					
		default:
			
			$meta_logo_path = $http.$_SERVER["HTTP_HOST"].'/images/fb-logo.jpg';
			
			
		
			echo '
			<meta property="og:title" content="Spinattic | 360 camera panoramic photography"/>
			<meta property="og:site_name" content="Spinattic"/>
			<meta property="og:url" content="'.$http.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'"/>
			<meta property="og:description" content="Create, customize and share virtual tours with 360 panoramas from your 360 camera"/>
			<meta property="og:image" content="'.$meta_logo_path.'"/>
			<link rel="image_src" href="'.$meta_logo_path.'" >';	
	}	
?>