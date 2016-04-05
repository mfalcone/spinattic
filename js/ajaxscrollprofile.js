	$(document).ready(function(){
		
		function lastPostFunc() 
		{ 

			switch($('#mod').val()){
			
			case 'tours':
				id_class = ".post";
				url = 'profile.php?uid=' + $("#uid").val() + '&action=getLastPosts&lastID=' + $(id_class + ":last").attr("id");
				break;
			
			case 'following':
			case 'followers':
				id_class = ".post" + $('#mod').val();
				url = 'ajax_get_follows.php?cant_reg=' +  $('#cant_reg').val() + '&mod=' + $("#mod").val() + '&uid=' + $("#uid").val() + '&action=getLastPosts&lastID=' + $(id_class + ":last").attr("id");
				break;
			}
			
			$.get(url,
					
				function(data){
					if (data != "") {
						$(id_class + ":last").after(data);			
						
						var $live = $('<div>').html(data);
		
						$('img',$live).load(function(){
							if($('#mod').val() == 'tours'){
								setupBlocks();
							}else{
								$('.btn_follow').unbind('click');
								$('.btn_follow').click(function(){
									follow($(this));
								});
							}
						});
						document.getElementById("loading").style.display="none";								
					}
					
				});
 		};  
		
		$(window).scroll(function(){
			if  ($(window).scrollTop() == $(document).height() - $(window).height()){
				if($('#mod').val() == 'tours' && $('.nomore').length == 0){
					document.getElementById("loading").style.display="block";
					lastPostFunc();
				}else{
					if(($('#mod').val() == 'followers' || $('#mod').val() == 'following') && $('.nomorefollows').length == 0){
						document.getElementById("loading").style.display="block";
						lastPostFunc();						
					}
				}
				
			}
		}); 
		
	});