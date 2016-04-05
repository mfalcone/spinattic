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
			
			$.post(current_page+ "?" + current_vars + "action=getLastPosts&lastID="+$(".post:last").attr("id"),
	
			function(data){
				if (data != "") {
				$(".post:last").after(data);			
				
				//setupBlocks();						   				
				}
				document.getElementById("loading").style.display="none";
			});
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