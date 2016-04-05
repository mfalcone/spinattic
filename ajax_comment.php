<?
require("inc/conex.inc");
require("inc/functions.inc");
session_start();
$id = $_GET["id"];
$idc = $_GET["idc"];
$action = $_GET["a"];
$myid = $_SESSION['usr'];
$comment = htmlspecialchars($_GET["comment"]);
$env = $_GET["env"];
$guest_name = $_GET["guest_name"];
$guest_email = $_GET["guest_email"];
$orig_post =  $_GET["orig_post"];
$replying_id =  $_GET["replying_id"];


$breaks = array("&lt;br /&gt;", "<br />","<br>","<br/>");


//BLOG COMMENTS

if($env == 'blog'){

	if (id != ''){
		if($action == 'ul'){
			
			if($myid != ''){ //si no estoy logueado debo recibir name e email
				$ssqlp = "INSERT INTO blog_comments (iduser, idpost, comment, date) values (".$myid.", ".$id.", '".mysql_real_escape_string($comment)."', now())";
			}else{
				$ssqlp = "INSERT INTO blog_comments (idpost, comment, date, guest_name, guest_email) values (".$id.", '".mysql_real_escape_string($comment)."', now(), '".mysql_real_escape_string($guest_name)."', '".mysql_real_escape_string($guest_email)."')";
			}
			

			mysql_query($ssqlp);

			$ssqlp_comments = "SELECT blog_comments.comment, blog_comments.id, blog_comments.iduser, blog_comments.guest_name, DATE_FORMAT(blog_comments.date,'%d/%m/%Y') as fecha, users.avatar, users.friendly_url as friendlyuser, users.username FROM blog_comments LEFT JOIN users on blog_comments.iduser = users.id where blog_comments.idpost = ".$id." order by blog_comments.date desc";
			$result_comments = mysql_query($ssqlp_comments);
			$row_comments = mysql_fetch_array($result_comments);
			
			
			if($row_comments["iduser"] != '' && $row_comments["iduser"] != 0){
				$avatar_markup = '<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row_comments["friendlyuser"].'"><img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row_comments["avatar"].'"  width="75" height="75"/></a>';
				$name_markup = '<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row_comments["friendlyuser"].'">'.$row_comments["username"].'</a>';
			}else{
				$avatar_markup = '<a href=""><img src="http://'.$_SERVER[HTTP_HOST].'/images/users/avatar.jpg"  width="75" height="75"/></a>';
				$name_markup = '<a href="">'.$row_comments["guest_name"].'</a>';
			}

			
			$comentario = str_ireplace($breaks, "\r\n", $row_comments["comment"]);
			$comentario_html = str_ireplace($breaks, "<br>", $row_comments["comment"]);

			$new_html = '<div class="comment-box" id="comment_'.$row_comments["id"].'">
			<div class="comment-avatar">'.$avatar_markup.'			
			</div>
			<div class="comment-text">
			<span id="comment_text_'.$row_comments["id"].'">
			<h3>'.$name_markup.'</h3>
			<span>'.$row_comments["fecha"].'</span>
			<p id="texto_comentario_'.$row_comments["id"].'">'.$comentario_html.'</p>
			<div class="comment-actions">
			<a href="'.$row_comments["id"].'" class="btn_edit_comment">Edit</a> -
			<a href="'.$row_comments["id"].'" class="btn_remove_comment">Remove</a>
			</div>
			</span>
			<span id="comment_edit_'.$row_comments["id"].'" style="display:none;">
			<div class="comment-textarea">
			<div class="punta"></div>
			<textarea id="thecomment'.$row_comments["id"].'">'.$comentario.'</textarea>
			</div>
			<div class="comment-actions" style="text-align:right;">
			<a href="'.$row_comments["id"].'" class="btn_cancel_comment">Cancel</a>
			<button class="blue-button btn_update_comment" href="'.$row_comments["id"].'">Save</button>
			</div>
			</span>
			</div>
			</div>';

			echo json_encode(array(

					'new_html' => $new_html,

					'idtour' => $id

			));
		}
		if($action == 'd'){
			$ssqlp = "DELETE FROM blog_comments where id = ".$idc;
			mysql_query($ssqlp);
		}
		if($action == 'ud'){
			$ssqlp = "UPDATE blog_comments set comment = '".mysql_real_escape_string($comment)."', date = now() where id = ".$idc;

			mysql_query($ssqlp);
		}
	}
}

//TOURS COMMENTS


if($env == 'tour'){	

	if (id != '' && $myid != ''){
		if($action == 'ul'){
			
			//Le cambio el valor de priority para recategorizar el tour si el comentario lo estoy cargando dentro de las 12 horas del último
			$ssqlp = "select idtour from comments where idtour = ".$id." and TIMESTAMPDIFF(HOUR, date, now()) < 12";
			$result = mysql_query($ssqlp);
			
			if (mysql_num_rows($result) == 0){
				setMaxPriority($id);
			}
			
			
			$ssqlp = "INSERT INTO comments (iduser, idtour, comments, date, reply_to, orig_comment) values (".$myid.", ".$id.", '".mysql_real_escape_string($comment)."', now(), '".$replying_id."', '".$orig_post."')";
			mysql_query($ssqlp);
			
			$ssqlp_comments = "SELECT comments.comments, comments.id, comments.iduser, DATE_FORMAT(comments.date,'%d/%m/%Y') as fecha, users.avatar, users.friendly_url as friendlyuser, users.username FROM comments, users where comments.idtour = ".$id." and comments.iduser = users.id order by comments.date desc";
			$result_comments = mysql_query($ssqlp_comments);
			$row_comments = mysql_fetch_array($result_comments);		
			
			$comentario_html = str_ireplace($breaks, "<br>", $row_comments["comments"]);
			$comentario = str_ireplace($breaks, "\r\n", $row_comments["comments"]);
			
			
			if($replying_id == '' || $replying_id == 'undefined'){

				//es un nuevo post
			
				$new_html = '<li class="comment-box" id="comment_'.$row_comments["id"].'">
								<div class="comment-avatar">
									<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row_comments["friendlyuser"].'"><img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row_comments["avatar"].'"  width="75" height="75"/></a>
			                    </div>
			                    <div class="comment-text">
			                    	<span id="comment_text_'.$row_comments["id"].'">
				                        <h3><a href="http://'.$_SERVER[HTTP_HOST].'/'.$row_comments["friendlyuser"].'">'.$row_comments["username"].'</a></h3>
				                        <span>'.$row_comments["fecha"].'</span>
				                        <p id="texto_comentario_'.$row_comments["id"].'">'.$comentario_html.'</p>
				                        <div class="comment-actions">
				                        	<a href="'.$row_comments["id"].'" class="btn_edit_comment">Edit</a> - 
				                        	<a href="'.$row_comments["id"].'" class="btn_remove_comment">Remove</a> - 
				                        	<a href="'.$row_comments["id"].'" data-orig-name="'.$row_comments["username"].'" class="btn_reply_comment">Reply</a>
				                        </div>
				                    </span>
				                    <span id="comment_edit_'.$row_comments["id"].'" style="display:none;">
				                        <div class="comment-textarea">
				                            <div class="punta"></div>
				                            <textarea id="thecomment'.$row_comments["id"].'">'.$comentario.'</textarea>
				                        </div>
				                        <div class="comment-actions" style="text-align:right;">
				                            <a href="'.$row_comments["id"].'" class="btn_cancel_comment">Cancel</a>
				                            <button class="blue-button btn_update_comment" href="'.$row_comments["id"].'">Save</button>
				                        </div>
				                    </span>
			                    </div>
			                    
			                    <span id="span_replies_'.$row_comments["id"].'"></span>
			                    
								<ul class="reply" id="reply_'.$row_comments["id"].'" style="display:none;">
									<li>
										<div class="comment-avatar">
										   <a href="http://'.$_SERVER[HTTP_HOST].'/'.$row_comments["friendlyuser"].'"><img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$_SESSION["avatar"].'"  width="75" height="75"/></a>
										</div>
									
										<div class="comment-text">
												<div class="comment-textarea">
													<div class="punta"></div>
													<textarea id="thereply_'.$row_comments["id"].'" placeholder="Reply to this comment..."></textarea>
												</div>
												<div class="comment-actions" style="text-align:right;">
													<a href="'.$row_comments["id"].'" class="btn_cancel_reply">Cancel</a>
													<button class="blue-button btn-new-reply-post" data-replying-id="'.$row_comments["id"].'" data-orig-post="'.$row_comments["id"].'">Reply</button>
												</div>
										</div>
									</li>
								</ul>			                    
			                    
			                </li>';
				
			}else{ 
				
				//es reply
			
				$new_html = '<ul class="reply">
								<li id="comment_'.$row_comments["id"].'">
									<div class="comment-avatar">
										<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row_comments["friendlyuser"].'"><img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row_comments["avatar"].'" width="75" height="75"></a>
									</div>
									<div class="comment-text">
										
										
										<div id="comment_text_'.$row_comments["id"].'">
											<h3><a href="http://'.$_SERVER[HTTP_HOST].'/'.$row_comments["friendlyuser"].'">'.$row_comments["username"].'</a></h3>
											<span>'.$row_comments["fecha"].'</span>
											<p id="texto_comentario_'.$row_comments["id"].'">'.$comentario_html.'</p>											
											
											<div class="comment-actions">
					                        	<a href="'.$row_comments["id"].'" class="btn_edit_comment">Edit</a> - 
					                        	<a href="'.$row_comments["id"].'" class="btn_remove_comment">Remove</a> - 
					                        	<a href="'.$orig_post.'" data-orig-name="'.$row_comments["username"].'" class="btn_reply_comment">Reply</a>
											</div>
											
										</div>
										
										<div id="comment_edit_'.$row_comments["id"].'" style="display:none;">
												<div class="comment-textarea">
													<div class="punta"></div>
													<textarea id="thecomment'.$row_comments["id"].'" placeholder="Reply to this comment...">'.$comentario.'</textarea>
												</div>
												<div class="comment-actions" style="text-align:right;">
													<a href="'.$row_comments["id"].'" class="btn_cancel_comment">Cancel</a>
													<button href="'.$row_comments["id"].'" class="blue-button btn_update_comment">Save</button>
												</div>
										</div>	

									</div>

								</li>
							</ul>';
			}
			
			if($comment != ''){
			
				$ssqlp_cant = "SELECT count(*) as cantidad from comments where idtour = ".$id;
				$result_cant = mysql_query($ssqlp_cant);
				$row_cant = mysql_fetch_array($result_cant);		
				$cantidad = $row_cant["cantidad"]; 	
				
				mysql_query("update tours set comments = ".$cantidad." where id = ".$id);
				mysql_query("update tours_draft set comments = ".$cantidad." where id = ".$id);
				
				echo json_encode(array(
						'result' => 'SUCCESS',
						
						'new_html' => $new_html,
				
						'idtour' => $id,
						
						'id_comment' => $row_comments["id"]
				
				));			
				
			}else{
				echo json_encode(array(
						'result' => 'ERROR'
				));
			}
		}
		if($action == 'd'){
			$ssqlp = "DELETE FROM comments where id = ".$idc." or orig_comment = ".$idc;
			mysql_query($ssqlp);
			
			$ssqlp_cant = "SELECT count(*) as cantidad from comments where idtour = ".$id;
			$result_cant = mysql_query($ssqlp_cant);
			$row_cant = mysql_fetch_array($result_cant);		
			$cantidad = $row_cant["cantidad"]; 	
			
			mysql_query("update tours set comments = ".$cantidad." where id = ".$id);
			mysql_query("update tours_draft set comments = ".$cantidad." where id = ".$id);
			
		}		
		if($action == 'ud'){
			$ssqlp = "UPDATE comments set comments = '".mysql_real_escape_string($comment)."', date = now() where id = ".$idc;
		
			mysql_query($ssqlp);
		}		
	}else{
		echo json_encode(array(
				'result' => 'ERROR'
		));		
	}
}
?>