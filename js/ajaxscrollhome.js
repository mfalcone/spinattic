	$(document).ready(function(){
		
		$('form#mainForm').bind('submit', function(e){
			e.preventDefault();
			checkForm();
		});
		
		$('input#hostName').focus();
	
		function lastPostFunc() 
		{ 
			current_url = document.location.href.split('/').slice(-1)[0];
			current_page = current_url.split('?').slice(0,1);
			current_vars = current_url.split('?').slice(1)+"&";
			
			//alert(current_page+ "?" + current_vars + "action=getLastPosts&lastID="+$(".post:last").attr("id"));
			
			$.ajax({
				  type: 'POST',
				  url: current_page+ "?" + current_vars + "action=getLastPosts&lastID="+$(".item:last").attr("id"),
				  success: function(data){
						if (data != "") {
							$(".item:last").after(data);			
							
							setupBlocks();
							jQuery('.btn_follow').unbind('click');
							jQuery('.btn_follow').click(function()	{
								follow($(this));
							});				
							}
							document.getElementById("loading").style.display="none";
						},
				  async:false
				});			
			
			/*
			$.post(current_page+ "?" + current_vars + "action=getLastPosts&lastID="+$(".item:last").attr("id"),
	
			function(data){
				if (data != "") {
				$(".item:last").after(data);			
				
				setupBlocks();
				jQuery('.btn_follow').click(function()	{
					follow($(this));
				});				
				}
				document.getElementById("loading").style.display="none";
			}
			);
			*/
 		};  
		
		$(window).scroll(function(){
			if  ($(window).scrollTop() == $(document).height() - $(window).height()){
				if($('.nomore').length == 0){
					document.getElementById("loading").style.display="block";
					lastPostFunc();
				}
			}
		}); 
		
	});