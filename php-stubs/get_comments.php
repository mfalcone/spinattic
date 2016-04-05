 			<br clear="all">
			<div id="comments" class="comments-wrapper">
			<ul id="comment_list">
<?
	$breaks = array("&lt;br /&gt;", "<br />","<br>","<br/>");

	function get_replies($id){
		global $logged;
		global $breaks;
		$ssqlp_replies = "SELECT comments.comments, comments.id, comments.iduser, DATE_FORMAT(comments.date,'%d/%m/%Y') as fecha, users.avatar, users.friendly_url as friendlyuser, users.username FROM comments, users where comments.orig_comment = ".$id." and comments.iduser = users.id order by comments.date";
		$result_replies = mysql_query($ssqlp_replies);
		while($row_replies = mysql_fetch_array($result_replies)){
			
			$comentario_html = str_ireplace($breaks, "<br>", $row_replies["comments"]);
			$comentario = str_ireplace($breaks, "\r\n", $row_replies["comments"]);
			
			$html_replies .= '<ul class="reply">
								<li id="comment_'.$row_replies["id"].'">
									<div class="comment-avatar">
										<a href="http://'.$_SERVER[HTTP_HOST].'/'.$row_replies["friendlyuser"].'"><img src="http://'.$_SERVER[HTTP_HOST].'/images/users/'.$row_replies["avatar"].'" width="75" height="75"></a>
									</div>
									<div class="comment-text">
										
										
										<div id="comment_text_'.$row_replies["id"].'">
											<h3><a href="http://'.$_SERVER[HTTP_HOST].'/'.$row_replies["friendlyuser"].'">'.$row_replies["username"].'</a></h3>
											<span>'.$row_replies["fecha"].'</span>
											<p id="texto_comentario_'.$row_replies["id"].'">'.$comentario_html.'</p>											
											
											<div class="comment-actions">';
												if($row_replies["iduser"] == $_SESSION["usr"]){ //solo el que hizo el comentario puede editarlo
													$html_replies .= '<a href="'.$row_replies["id"].'" class="btn_edit_comment">Edit</a> - '; 
												}
												
												if($logged == 1 && ($row_replies["iduser"] == $_SESSION["usr"] || $iduser == $_SESSION["usr"] || $_SESSION["admin"] == 1)){
													$html_replies .= '<a href="'.$row_replies["id"].'" class="btn_remove_comment">Remove</a> - '; 
												}
																				
												if($logged == 1){
													$html_replies .= '<a href="'.$id.'"  data-orig-name="'.$row_replies["username"].'" class="btn_reply_comment">Reply</a>';
												}
												
												
										$html_replies .= '	</div>
											
										</div>
										
										<div id="comment_edit_'.$row_replies["id"].'" style="display:none;">
												<div class="comment-textarea">
													<div class="punta"></div>
													<textarea id="thecomment'.$row_replies["id"].'" placeholder="Reply to this comment...">'.$comentario.'</textarea>
												</div>
												<div class="comment-actions" style="text-align:right;">
													<a href="'.$row_replies["id"].'" class="btn_cancel_comment">Cancel</a>
													<button href="'.$row_replies["id"].'" class="blue-button btn_update_comment">Save</button>
												</div>
										</div>	

									</div>

								</li>
							</ul>';
			
		}
		return $html_replies;
	}
		$ssqlp_comments = "SELECT comments.comments, comments.id, comments.iduser, DATE_FORMAT(comments.date,'%d/%m/%Y') as fecha, users.avatar, users.friendly_url as friendlyuser, users.username FROM comments, users where comments.idtour = ".$id." and comments.iduser = users.id and comments.reply_to = 0 order by comments.date";
		$result_comments = mysql_query($ssqlp_comments);
		while($row_comments = mysql_fetch_array($result_comments)){
			
			$comentario = str_ireplace($breaks, "\r\n", $row_comments["comments"]);
			$comentario_html = str_ireplace($breaks, "<br>", $row_comments["comments"]);
?>

				<li class="comment-box" id="comment_<?php echo $row_comments["id"];?>">
					<div class="comment-avatar">
					   <a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?echo $row_comments["friendlyuser"];?>"><img src="http://<?php echo $_SERVER[HTTP_HOST];?>/images/users/<?php echo $row_comments["avatar"];?>"  width="75" height="75"/></a>
					</div>
					<div class="comment-text" >
						<div id="comment_text_<?php echo $row_comments["id"];?>">
							<h3><a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?echo $row_comments["friendlyuser"];?>"><?echo $row_comments["username"];?></a></h3>
							<span><?php echo $row_comments["fecha"];?></span>
							<p id="texto_comentario_<?php echo $row_comments["id"];?>"><?php echo $comentario_html;?></p>
							<div class="comment-actions">
								
								<?php if($row_comments["iduser"] == $_SESSION["usr"]){ //solo el que hizo el comentario puede editarlo?>
									<a href="<?php echo $row_comments["id"];?>" class="btn_edit_comment">Edit</a> -  
								<?php }?>
								
								<?php if($row_comments["iduser"] == $_SESSION["usr"] || $iduser == $_SESSION["usr"] || $_SESSION["admin"] == 1){?>
									<a href="<?php echo $row_comments["id"];?>" class="btn_remove_comment">Remove</a> - 
								<?php }?>
								<?php if($logged == 1){?>
									<a href="<?php echo $row_comments["id"];?>" data-orig-name="<?echo $row_comments["username"];?>" class="btn_reply_comment">Reply</a>
								<?php }?>
							</div>

						</div>
						
						
						<?php 
						//EDIT COMMENT
						if($row_comments["iduser"] == $_SESSION["usr"]){

						?>
						<div id="comment_edit_<?php echo $row_comments["id"];?>" style="display:none;">
							<div class="comment-textarea">
								<div class="punta"></div>
								<textarea id="thecomment<?php echo $row_comments["id"];?>"><?php echo $comentario;?></textarea>
							</div>
							<div class="comment-actions" style="text-align:right;">
								<a href="<?php echo $row_comments["id"];?>" class="btn_cancel_comment">Cancel</a>
								<button href="<?php echo $row_comments["id"];?>" class="blue-button btn_update_comment">Save</button>
							</div>
						</div>
						<?php }?>
					</div>
					
					<?php echo get_replies($row_comments["id"]);?>
					
					<span id="span_replies_<?php echo $row_comments["id"];?>"></span>
					
					<?php //REPLY COMMENT?>
					
					<ul class="reply" id="reply_<?php echo $row_comments["id"];?>" style="display:none;">
						<li>
							<div class="comment-avatar">
							   <a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?echo $_SESSION["friendly_url"];?>"><img src="http://<?php echo $_SERVER[HTTP_HOST];?>/images/users/<?php echo $_SESSION["avatar"];?>"  width="75" height="75"/></a>
							</div>
						
							<div class="comment-text">
									<div class="comment-textarea">
										<div class="punta"></div>
										<textarea id="thereply_<?php echo $row_comments["id"];?>" placeholder="Reply to this comment..."></textarea>
									</div>
									<div class="comment-actions" style="text-align:right;">
										<a href="<?php echo $row_comments["id"];?>" class="btn_cancel_reply">Cancel</a>
										<button class="blue-button btn-new-reply-post" data-replying-id="<?php echo $row_comments["id"];?>" data-orig-post="<?php echo $row_comments["id"];?>">Reply</button>
									</div>
							</div>
						</li>
					</ul>					
					
					
				</li>
				
		
				
				
				
		<?php }?>
		
		
		
		
		</ul>
<?php if($logged == 1){?>            
		
				<div class="comment-box" >
					<div class="comment-avatar">
						<a href="http://<?php echo $_SERVER[HTTP_HOST];?>/<?echo $_SESSION["friendly_url"];?>"><img src="http://<?php echo $_SERVER[HTTP_HOST];?>/images/users/<?echo $_SESSION["avatar"];?>" width="75" height="75" /></a>
					</div>
					<div class="comment-text" >
						<div class="comment-textarea">
							<div class="punta"></div>
							<textarea id="thecomment" placeholder="Share what you think..."></textarea>
						</div>
						<div class="comment-actions" style="text-align:right;">
							<button class="blue-button btn-new-comment-post">Publish</button>
						</div>
					</div>
				</div>

<?}?>       
	   
			
			</div><!-- end comments-wrapper -->
			<input type="hidden" value="tour" id="env" name="env">
