			<br clear="all">
            <div id="comments" class="comments-wrapper">
			<span id="comment_list">
<?
		$breaks = array("&lt;br /&gt;", "<br />","<br>","<br/>");
		
		$ssqlp_comments = "SELECT blog_comments.comment, blog_comments.id, blog_comments.guest_name, blog_comments.iduser, DATE_FORMAT(blog_comments.date,'%d/%m/%Y') as fecha, users.avatar, users.username FROM blog_comments LEFT JOIN users on blog_comments.iduser = users.id where blog_comments.idpost = ".$id." order by blog_comments.date";
		//echo $ssqlp_comments;
		$result_comments = mysql_query($ssqlp_comments);
		while($row_comments = mysql_fetch_array($result_comments)){
			if($row_comments["iduser"] != '' && $row_comments["iduser"] != 0){
				$avatar_markup = '<a href="profile.php?uid='.$row_comments["iduser"].'"><img src="images/users/'.$row_comments["avatar"].'"  width="75" height="75"/></a>';
				$name_markup = '<a href="profile.php?uid='.$row_comments["iduser"].'">'.$row_comments["username"].'</a>';
			}else{
				$avatar_markup = '<a href=""><img src="images/users/avatar.jpg"  width="75" height="75"/></a>';
				$name_markup = '<a href="">'.$row_comments["guest_name"].'</a>';
			}
			$comentario_html = str_ireplace($breaks, "<br>", $row_comments["comment"]);
?>

                <div class="comment-box" id="comment_<?php echo $row_comments["id"];?>">
                    <div class="comment-avatar">
                       <?php echo $avatar_markup;?>
                    </div>
                    <div class="comment-text" >
                    	<span id="comment_text_<?php echo $row_comments["id"];?>">
	                        <h3><?php echo $name_markup;?></h3>
	                        <span><?php echo $row_comments["fecha"];?></span>
	                        <p id="texto_comentario_<?php echo $row_comments["id"];?>"><?php echo $comentario_html;?></p>
	                    <?php if($row_comments["iduser"] == $_SESSION["usr"] || $_SESSION["admin"] == 1){?>    
	                        <div class="comment-actions">
	                        <?php if($row_comments["iduser"] == $_SESSION["usr"]){ //solo el que hizo el comentario puede editarlo?>
	                        	<a href="<?php echo $row_comments["id"];?>" class="btn_edit_comment">Edit</a> - 
	                        	<?php }
			                        	//El que hizo el comentario y el admin pueden eliminarlo?>
	                        	<a href="<?php echo $row_comments["id"];?>" class="btn_remove_comment">Remove</a>
	                        </div>
	                    <?php }?>
	                    </span>
	                    <?php if($row_comments["iduser"] == $_SESSION["usr"]){
  
    					$comentario = str_ireplace($breaks, "\r\n", $row_comments["comment"]);  
    					?>
	                    <span id="comment_edit_<?php echo $row_comments["id"];?>" style="display:none;">
	                        <div class="comment-textarea">
	                            <div class="punta"></div>
	                            <textarea id="thecomment<?php echo $row_comments["id"];?>"><?php echo $comentario;?></textarea>
	                        </div>
	                        <div class="comment-actions" style="text-align:right;">
	                            <a href="<?php echo $row_comments["id"];?>" class="btn_cancel_comment">Cancel</a>
	                            <button href="<?php echo $row_comments["id"];?>" class="blue-button btn_update_comment">Save</button>
	                        </div>
	                    </span>
	                    <?php }?>
                    </div>
                    
                    
                </div>
       <?php }?>
</span>


<?php if($logged == 1){
	$avatar_markup = '<a href="profile.php?uid='.$_SESSION["usr"].'"><img src="images/users/'.$_SESSION['avatar'].'" width="75" height="75"></a>';
}else{
	$avatar_markup = '<a href=""><img src="images/users/avatar.jpg" width="75" height="75"></a>';
	$user_data = '<div class="user-data">
					<input id="guest_name" type="text" placeholder="Your Name" class="border form-user">
					<input id="guest_email" type="email" placeholder="Your e-mail" class="border form-user">
				  </div>';	
}?>

	                <div class="comment-box">
	                    <div class="comment-avatar">
	                        <?php echo $avatar_markup;?>
	                    </div>
	                    <div class="comment-text">
	                        <div class="comment-textarea">
	                            <div class="punta"></div>
	                            <textarea id="thecomment" placeholder="Share what you think..."></textarea>
	                        </div>
								<?php echo $user_data;?>
	                        <div class="comment-actions" style="text-align:right;">
	                            <button class="blue-button btn-new-comment-post">Publish</button>
	                        </div>
	                    </div>
	                </div>

       
			
            </div><!-- end comments-wrapper -->
			<input type="hidden" value="blog" id="env" name="env">