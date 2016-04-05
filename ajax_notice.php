<?
/* Types
* 1 - Follow
* 2 - Likes
* 3 - Comments
* 4 - Blog Post
* 5 - Reply Comment
* 6 - Brand new tour from following
*/
ini_set("display_errors", 0);

	if($type == ''){//para no volver a llamarlo si vengo de la api de mobile
		session_start();
		require_once("inc/conex.inc");
		require_once("inc/functions.inc");
		$type = $_GET["type"];
		$id = $_GET["t"];
		$text = $_GET["txt"];
		$myid = $_SESSION['usr'];
		$myname = $_SESSION['username'];
		$myavatar = $_SESSION['avatar'];		
		$from_API = 0;
	}	else{
		$from_API = 1;
	}
	

	

	if ($id !='' && $myid != ''){
		//si existe una notificacion de este tipo sin leer, con los mismos source y target, no inserto una nueva notificacion:
		$ssqlp = "SELECT * FROM notifications where 'read' = 0 and target_id = ".$id." and source_id = ".$myid." and type <> 4 and type = ".$type;
		$result = mysql_query($ssqlp);
		if(!($row = mysql_fetch_array($result))){
			
			switch ($type){
			case 1:
				
				$ssqlp_user = "SELECT * FROM users where id = ".$id;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$to = $row_user["email"];				
				$toName = $row_user["username"];
				
				$send_mail =  $row_user["follow_me"];
				
				$text = 'is now following you';
				
				$ssqlp = "INSERT INTO notifications (source_id, target_id, text, date, type) values (".$myid.", ".$id.", '".mysql_escape_string($text)."', now(), ".$type.")";
				
				if($myid != $id){
					mysql_query($ssqlp);
					
					//Mail data
					$notif_info = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile.php?uid='.$myid.'" style="color:#51ace5; text-decoration:none;">'.$myname.'</a> is now following you';
					$avatar = $myavatar;
					$subject = $myname.' is following you in Spinattic';
					
					if($send_mail == 1){
						send_mail($to, $toName, $subject, $avatar, $notif_info, '','','','', 1);
					}					
				}
				
				

				
				break;

	//Si es un comentario o un like, en id recibo el id del tour, no del usuario, por lo que tengo que deducir el usuario para el target	
		
			case 2:
				$ssqlp_user = "SELECT iduser, title FROM tours where id = ".$id;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$titulo = $row_user["title"];				
				$text = 'likes your tour: <strong><a href="http://'.$_SERVER[HTTP_HOST].'/tour.php?id='.$id.'">'.$titulo.'</a></strong>';
				$id = $row_user["iduser"];
				
				$ssqlp = "INSERT INTO notifications (source_id, target_id, text, date, type) values (".$myid.", ".$id.", '".mysql_escape_string($text)."', now(), ".$type.")";
				
				if($myid != $id){
					mysql_query($ssqlp);
				}
				
				
				break;
				
			case 3: 
				$ssqlp_user = "SELECT iduser, title FROM tours where id = ".$id;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$tour_id = $id;
				$titulo = $row_user["title"];
				$text = 'commented your tour: <strong><a href="http://'.$_SERVER[HTTP_HOST].'/tour.php?id='.$id.'#comments">'.$titulo.'</a></strong>';
				$id = $row_user["iduser"];
				
				
				$ssqlp_user = "SELECT * FROM users where id = ".$id;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$to = $row_user["email"];
				$toName = $row_user["username"];				
				
				$send_mail =  $row_user["comment_tour"];
				
				
				$ssqlp = "INSERT INTO notifications (source_id, target_id, text, date, type) values (".$myid.", ".$id.", '".mysql_escape_string($text)."', now(), ".$type.")";				
				
				if($myid != $id){
					mysql_query($ssqlp);
					
					//Mail data
					$notif_info = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile.php?uid='.$myid.'" style="color:#51ace5; text-decoration:none;">'.$myname.'</a> commented on your tour: <b><a href="http://'.$_SERVER['HTTP_HOST'].'/tour.php?id='.$tour_id.'" style="color:#51ace5; text-decoration:none;">'.$titulo.'</a></b>';
					$avatar = $myavatar;
					$subject = $myname.' commented your tour on Spinattic';
					
					if($send_mail == 1){
						send_mail($to, $toName, $subject, $avatar, $notif_info, '','','','', 1);
					}
				}
				
				

				
				break;

	//Si es un post, recibo el id del post, pero el usuario no se tiene en cuenta ya que siempre va a ser spinattic 
	//Genero una notificacion por cada usuario activo
	
			case 4:
				//Solo los administradores pueden postear blogs
				if($_SESSION['admin'] == 1){
					$ssqlp_post = "SELECT blog.title, blog.text, categories_blog.mailing_group,  blog.send_mail FROM blog, categories_blog where blog.category = categories_blog.id and blog.id = ".$id;
					$result_post = mysql_query($ssqlp_post);
					$row_post = mysql_fetch_array($result_post);
					$titulo = $row_post["title"];
					$blog_text = $row_post["text"];
					$text = 'has news for you: <strong><a href="http://'.$_SERVER['HTTP_HOST'].'/blog-single-post.php?id='.$id.'">'.$titulo.'</a></strong>';
					$send_mail = $row_post["send_mail"];
					$mailing_group = $row_post["mailing_group"];
					
					$ssqlp = "INSERT INTO notifications (source_id, target_id, text, date, type) select  '".$myid."', id, '".mysql_escape_string($text)."', now(), ".$type." from users where status = 1";
					mysql_query($ssqlp);
					
					//echo $ssqlp;
					
					if($send_mail == 1){
						//Mail proccess
						$ssqlp_user = "SELECT * FROM users where status = 1";
						$result_user = mysql_query($ssqlp_user);
					
						$subject = $titulo.' - News from Spinattic.com';
						$toName = 'spinattic_mail_group'; //uso esta bandera para meter los destinatarios ($to) en CCO
					
						while($row_user = mysql_fetch_array($result_user)){
					
							if($mailing_group == 1){
								$send_mail =  $row_user["important_improvements"];
							}
							if($mailing_group == 2){
								$send_mail =  $row_user["spinattic_blog"];
							}
					
							if($send_mail == 1){
								$to = $row_user["email"];
					
								//$to = substr($to, 0,strlen($to)-1);
								send_mail($to, $toName, $subject, '', '', $titulo, 'http://'.$_SERVER['HTTP_HOST'].'/blog-single-post.php?id='.$id, substr($blog_text, 0, 700).' ... <br><a href="http://'.$_SERVER['HTTP_HOST'].'/blog-single-post.php?id='.$id.'" style="color:#51ace5; text-decoration:none;">Read More ></a>', '', 1);
					
							}
					
					
					
						}
					
					}					
					
				}

	

				break;		

			case 5:
				//En $id recibo el id del reply (comment) 
				$ssqlp_comment = "SELECT idtour, iduser, orig_comment, reply_to FROM comments where id = ".$id;
				$result_comment = mysql_query($ssqlp_comment);
				$row_comment = mysql_fetch_array($result_comment);		
				$id_tour = $row_comment["idtour"];
				$id_user = $row_comment["iduser"];
				$orig_comment = $row_comment["orig_comment"];
				$reply_to = $row_comment["reply_to"];
				
				
				//Para el dueño del tour
				$ssqlp_user = "SELECT iduser, title FROM tours where id = ".$id_tour;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$titulo = $row_user["title"];
				$text = 'commented your tour: <strong><a href="http://'.$_SERVER[HTTP_HOST].'/tour.php?id='.$id_tour.'#comments">'.$titulo.'</a></strong>';
				$id = $row_user["iduser"];
			
			
				$ssqlp_user = "SELECT * FROM users where id = ".$id;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$to = $row_user["email"];
				$toName = $row_user["username"];
			
				$send_mail =  $row_user["comment_tour"];
			
				$ssqlp = "INSERT INTO notifications (source_id, target_id, text, date, type) values (".$myid.", ".$id.", '".mysql_escape_string($text)."', now(), ".$type.")";
			
				if($myid != $id){
					mysql_query($ssqlp);
			
					//Mail data
					$notif_info = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile.php?uid='.$myid.'" style="color:#51ace5; text-decoration:none;">'.$myname.'</a> commented on your tour: <b><a href="http://'.$_SERVER['HTTP_HOST'].'/tour.php?id='.$id_tour.'" style="color:#51ace5; text-decoration:none;">'.$titulo.'</a></b>';
					$avatar = $myavatar;
					$subject = $myname.' commented your tour on Spinattic';
			
					if($send_mail == 1){
						send_mail($to, $toName, $subject, $avatar, $notif_info, '','','','', 1);
					}
				}
				
				$tour_owner_id = $id;
				
				//Para el dueño del post original

				$ssqlp_user = "SELECT iduser FROM comments where id = ".$orig_comment;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$text = 'replied your comment in the tour <strong><a href="http://'.$_SERVER[HTTP_HOST].'/tour.php?id='.$id_tour.'#comments">'.$titulo.'</a></strong>';
				$id = $row_user["iduser"];
				
				
				$ssqlp_user = "SELECT * FROM users where id = ".$id;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$to = $row_user["email"];
				$toName = $row_user["username"];
				
				$send_mail =  $row_user["comment_tour"];
				
				$ssqlp = "INSERT INTO notifications (source_id, target_id, text, date, type) values (".$myid.", ".$id.", '".mysql_escape_string($text)."', now(), ".$type.")";
				
				if($myid != $id && $tour_owner_id != $id){ //si el usuario target no es el dueño del tour
					mysql_query($ssqlp);
				
					//Mail data
					$notif_info = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile.php?uid='.$myid.'" style="color:#51ace5; text-decoration:none;">'.$myname.'</a> replied your comment in the tour <b><a href="http://'.$_SERVER['HTTP_HOST'].'/tour.php?id='.$id_tour.'" style="color:#51ace5; text-decoration:none;">'.$titulo.'</a></b>';
					$avatar = $myavatar;
					$subject = $myname.' replied your comment on Spinattic';
				
					if($send_mail == 1){
						send_mail($to, $toName, $subject, $avatar, $notif_info, '','','','', 1);
					}
				}
				
				$post_owner_id = $id;
			
				//Para el dueño del reply contestado
				
				$ssqlp_user = "SELECT iduser FROM comments where id = ".$reply_to;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$text = 'replied your comment in the tour <strong><a href="http://'.$_SERVER[HTTP_HOST].'/tour.php?id='.$id_tour.'#comments">'.$titulo.'</a></strong>';
				$id = $row_user["iduser"];
				
				
				$ssqlp_user = "SELECT * FROM users where id = ".$id;
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$to = $row_user["email"];
				$toName = $row_user["username"];
				
				$send_mail =  $row_user["comment_tour"];
				
				$ssqlp = "INSERT INTO notifications (source_id, target_id, text, date, type) values (".$myid.", ".$id.", '".mysql_escape_string($text)."', now(), ".$type.")";
				
				if($myid != $id && $tour_owner_id != $id && $post_owner_id != $id){ //si el usuario target no es el dueño del tour ni del post original
					mysql_query($ssqlp);
				
					//Mail data
					$notif_info = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile.php?uid='.$myid.'" style="color:#51ace5; text-decoration:none;">'.$myname.'</a> replied your comment in the tour <b><a href="http://'.$_SERVER['HTTP_HOST'].'/tour.php?id='.$id_tour.'" style="color:#51ace5; text-decoration:none;">'.$titulo.'</a></b>';
					$avatar = $myavatar;
					$subject = $myname.' replied your comment on Spinattic';
				
					if($send_mail == 1){
						send_mail($to, $toName, $subject, $avatar, $notif_info, '','','','', 1);
					}
				}				
				
			
				break;			

			case 6:
				//En id recibo el id del tour, no del usuario, por lo que tengo que deducir el usuario para el target
				//Tomo los datos del tour y el id del dueño
				$ssqlp_user = "SELECT tours.iduser, tours.title, users.avatar, users.username FROM tours, users where tours.id = ".$id." and users.id = tours.iduser";
				$result_user = mysql_query($ssqlp_user);
				$row_user = mysql_fetch_array($result_user);
				$tour_id = $id;
				$titulo = $row_user["title"];
				$user_id = $row_user["iduser"];
				$user_name = $row_user["username"];
				$user_avatar = $row_user["avatar"];
				
				//Tomo el Thumb del tour
				$ssqlthumb = "SELECT * FROM panosxtour where idtour = ".$tour_id." ORDER BY ord LIMIT 1";
				$resultthumb = mysql_query($ssqlthumb);
				$rowthumb = mysql_fetch_array($resultthumb);				
				$thumb_path = $cdn.'/panos/'.$rowthumb["idpano"].'/pano.tiles/thumb500x250.jpg';
				
				
				$text = 'published a new virtual tour: <strong><a href="http://'.$_SERVER[HTTP_HOST].'/tour.php?id='.$tour_id.'">'.$titulo.'</a></strong>';
				

				$ssqlp = "INSERT INTO notifications (source_id, target_id, text, date, type) select  '".$myid."', users.id, '".mysql_escape_string($text)."', now(), ".$type." from follows, users where follows.id_following = ".$myid." and follows.id_follower = users.id and users.status = 1";
				mysql_query($ssqlp);				
				
				
				//Recorro los usuarios que lo siguen
				$ssqlp_user = "SELECT follows.id_follower, users.email, users.new_tour FROM follows, users where follows.id_following = ".$user_id." and follows.id_follower = users.id and users.status = 1";
				$result_user = mysql_query($ssqlp_user);
				
				$subject = $myname.' published a new virtual tour in Spinattic.com';
				$toName = 'spinattic_mail_group'; //uso esta bandera para meter los destinatarios ($to) en CCO
				
				while($row_user = mysql_fetch_array($result_user)){
					
					$send_mail =  $row_user["new_tour"];
					
					if($send_mail == 1){
						$to = $row_user["email"].",";
						
						//$to = substr($to, 0,strlen($to)-1);
						
						$notif_info = '<a href="http://'.$_SERVER['HTTP_HOST'].'/profile.php?uid='.$myid.'" style="color:#51ace5; text-decoration:none;">'.$myname.'</a> published a new virtual tour. <br>Now you know.';
						
						send_mail($to, $toName, $subject, $user_avatar, $notif_info, $titulo, 'http://'.$_SERVER[HTTP_HOST].'/tour.php?id='.$tour_id, '<a href="http://'.$_SERVER['HTTP_HOST'].'/tour.php?id='.$tour_id.'"><img src="'.$thumb_path.'"></a>', '',1);
				
					}
					
				}
				

				
				
			break;
				
				
			}
			

		}
		if($from_API != 1) {echo 'success';}
		//echo $ssqlp;

	}
?>