	$(document).ready(function(){

 		$('.inner-notifications').scroll(function(){

			if($('.inner-notifications').scrollTop() + $('.inner-notifications').innerHeight() >= $('.inner-notifications')[0].scrollHeight){

				if($('.nomore_notif').length == 0){
					get_notif('next');
				}
			}
		}); 
 		

 		
	});
	
	
	function get_notif(next) 
	{ 
		document.getElementById("loader").style.display="block";
		query_string = '';
		if(next == 'next'){
			query_string = "?action=getLastPosts&lastID="+$(".notif_item:last").attr("id");
		}
		
		$.post("ajax_get_notif.php"+query_string,

		function(data){
			if (data != "") {
				$('#notif_list').html($('#notif_list').html()+data);
				
				//Bindeo click sobre el item para pasar a checked = 1 en BD
				$('.notif_item').click(function(e){
					elid = $(this).data('notif_id');
					$.ajax({
						url : 'ajax_check_notif.php',
						type: 'POST',
						async: false,
						data: 'id='+elid,
						cache : false,
						success : function(response){}
					});	
				})				
			}
			$(".inner-notifications").mCustomScrollbar({
				theme:"minimal-dark",
				scrollInertia:100,
				callbacks:{
					onTotalScroll:function(){
					//console.log("in")
						if($('.nomore_notif').length == 0){
									document.getElementById("loader").style.display="block";
									$(".inner-notifications").mCustomScrollbar("scrollTo","bottom",{scrollInertia:1,  timeout:1});
									get_notif('next');
						}
					}
				}
			});

			
	$("#notifications-wrapper li").on({
		mouseenter:function(){
			//console.log("on")
			$("body").css({ "overflow-y": 'hidden' })
		},
		mouseleave:function(){
			//console.log("out")
			$("body").css({ "overflow-y": 'auto' })
		}
	})

			document.getElementById("loader").style.display="none";
		});
		};	