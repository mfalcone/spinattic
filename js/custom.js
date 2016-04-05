
var speed_language = 250;

var margenInferiorTour = 228;
var map_tour = false;
var embed_tour = false;
var share_tour = false;
var heightScreen;
var navMobile = false;
var popUp = false;

jQuery(document).ready(function(){
	
	function showMsg(msg){
		//message.html(msg);
				showMessage('', msg);
	}		

	$('#orderBy').click(function(e){
		//alert($('#val_orderBy').val());
		e.preventDefault();
		sentido = "asc";
		$(this).toggleClass("asc desc");
		if($(this).hasClass("desc")){
			sentido = "desc";
		}
		
		$("#val_orderBy").val(sentido);
		//alert($('#val_orderBy').val());
		document.form1.submit();
	});
	
	/*----------Comments functions----------------------------------------------------------------------------------------------------------------------------------*/
	/*---------- hidden in markup select environment (#env) BLOG or TOURS ------------------------------------------------------------------------------------------*/

	
	/*btn-new-comment-post*/
	jQuery('.btn-new-comment-post').click(function(e)	{
		if($('#guest_name').length > 0 && $('#guest_name').val() == '' || $('#guest_email').length > 0 && $('#guest_email').val() == ''){
				showMessage("Error", "You must complete all fields");
		}else{
			if($('#guest_email').length > 0 && !validateEmail($('#guest_email').val())){
				showMessage("Error", "Please, write a valid E-Mail address");
			}else{
				if($('#thecomment').val() != ''){
					var xmlhttp;
					if (window.XMLHttpRequest)
					  {// code for IE7+, Firefox, Chrome, Opera, Safari
					  xmlhttp=new XMLHttpRequest();
					  }
					else
					  {// code for IE6, IE5
					  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					  }
					xmlhttp.onreadystatechange=function()
					{
					  if (xmlhttp.readyState==4 && xmlhttp.status==200)
					  {
						respuesta = JSON.parse(xmlhttp.responseText);
						if(respuesta.result == 'ERROR'){
								var html = '<div class="overlay show-response chk_session_overlay">'
									  +'      <div class="pop">'
									  +'          <a href="#" class="closed"  onclick="hide_popup();"></a>'
									  +'          <h2>Ooops !</h2>'
									  +'          <div class="content_pop">'
									  +'              <form class="pop-up">'
									  +'                  <label>           '    
									  +'                      <p>Your session expired</p>'
									  +'                  </label>'
									  +'                    <div class="content-btn-pop">'
									  +'                        <a href="" class="red-button border-radius-4 go_login">Login</a>'
									  +'                    </div>'
									  +'             </form>'
									  +'          </div>'
									  +'      </div>'
									  +'  </div>';
							  
							var el = jQuery(html).appendTo('body');
							var $pop = jQuery(el).children('.pop');
							jQuery(el).fadeIn(200);
							 $(".go_login").click(function(event){
								 event.preventDefault();
								 jQuery(el).fadeOut(200);
								 $('.login-register .login').trigger("click");
							 });
						}else{
							$('#comment_list').html($('#comment_list').html()+respuesta.new_html);
							if($('#env').val() == 'tour'){
								notificate(respuesta.idtour, 3);
							}
						}
					  }
					}
					xmlhttp.open("GET","ajax_comment.php?guest_name="+encodeURIComponent($('#guest_name').val())+"&guest_email="+$('#guest_email').val()+"&env="+$('#env').val()+"&id="+$('#id').val()+"&a=ul&comment="+encodeURIComponent($('#thecomment').val().replace(new RegExp('\n','g'), '<br />')),true);
					xmlhttp.send();
					$('#thecomment').val('');
					if($('#env').val() == 'tour'){
						mixpanel.track("Comment");
					}
				}else{
					showMessage("Error", "You must complete the comment first");
				}
				return false;
			}
		}
	});	
	

	/*btn_remove_comment*/
	$('#comment_list').on('click', '.btn_remove_comment', function (e) {
	//jQuery('.btn_remove_comment').click(function(e)	{
		e.preventDefault();
		var idc = $(this).attr('href');
		//if(confirm("Are you sure?")){
		confirmMessage('Spinattic','Are you sure?', function(){

			var xmlhttp;
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			{
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			  {
				$('#comment_'+idc).hide();
			  }
			}
			xmlhttp.open("GET","ajax_comment.php?guest_name="+encodeURIComponent($('#guest_name').val())+"&guest_email="+$('#guest_email').val()+"&env="+$('#env').val()+"&id="+$('#id').val()+"&a=d&idc="+idc,true);
			xmlhttp.send();
		});
		return false;
	});		
	
	
	/*btn-new-reply-post*/
	$('#comment_list').on('click', '.btn-new-reply-post', function (e) {
		replying_id = $(this).data("replying-id");
		orig_post = $(this).data("orig-post");
		$.ajax({
			url : 'ajax_comment.php',
			type: 'GET',
			data: "env="+$('#env').val()+"&replying_id="+replying_id+"&orig_post="+orig_post+"&a=ul&comment="+encodeURIComponent($('#thereply_'+replying_id).val().replace(new RegExp('\n','g'), '<br />'))+"&id="+$('#id').val(),
			cache : false,
			success : function(response){
				respuesta = JSON.parse(response);
				if(respuesta.result != 'ERROR'){
					$('#span_replies_'+orig_post).html($('#span_replies_'+orig_post).html()+respuesta.new_html);
					$('#thereply_'+orig_post).val('');
					$('#reply_'+orig_post).hide();
					notificate(respuesta.id_comment, 5);
				}
			}
		});
	});		
	
	
	
	
	/*btn_reply_comment*/
	
	$('#comment_list').on('click', '.btn_reply_comment', function (e) {
	//jQuery('.btn_edit_comment').click(function(e)	{
		e.preventDefault();
		$('#reply_'+$(this).attr('href')).show();
		$('#thereply_'+$(this).attr('href')).val($(this).data('orig-name')+': ');
		$('#thereply_'+$(this).attr('href')).focus();
		$('html,body').animate({scrollTop: $('#reply_'+$(this).attr('href')).offset().top-100},'slow');
		return false;
	});		
	
	
	/*btn_edit_comment*/
	
	
	$('#comment_list').on('click', '.btn_edit_comment', function (e) {
	//jQuery('.btn_edit_comment').click(function(e)	{
		e.preventDefault();
		$('#comment_text_'+$(this).attr('href')).hide();
		$('#comment_edit_'+$(this).attr('href')).show();
		$('#thecomment'+$(this).attr('href')).select();
		return false;
	});		


	/*btn_cancel_reply*/

	$('#comment_list').on('click', '.btn_cancel_reply', function (e) {
	//jQuery('.btn_cancel_comment').click(function(e)	{
		e.preventDefault();
		$('#reply_'+$(this).attr('href')).hide();
		return false;
	});			
		
	
	
	/*btn_cancel_comment*/

	$('#comment_list').on('click', '.btn_cancel_comment', function (e) {
	//jQuery('.btn_cancel_comment').click(function(e)	{
		e.preventDefault();
		$('#comment_text_'+$(this).attr('href')).show();
		$('#comment_edit_'+$(this).attr('href')).hide();
		return false;
	});			
	
	
	/*btn_update_comment*/

	$('#comment_list').on('click', '.btn_update_comment', function (e) {
	//jQuery('.btn_update_comment').click(function(e)	{
		//alert($(this).attr('href'));
		var idc = $(this).attr('href');
		if($('#thecomment'+ idc).val() != ''){
			var xmlhttp;
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			{
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			  {
				$('#texto_comentario_'+idc).html(escapeHtml($('#thecomment'+idc).val()).replace(new RegExp('\n','g'), '<br />'));
				$('#comment_text_'+idc).show();
				$('#comment_edit_'+idc).hide();
			  }
			}
			xmlhttp.open("GET","ajax_comment.php?guest_name="+$('#guest_name').val()+"&guest_email="+$('#guest_email').val()+"&env="+$('#env').val()+"&idc="+idc+"&id="+$('#id').val()+"&a=ud&comment="+encodeURIComponent($('#thecomment'+ idc).val().replace(new RegExp('\n','g'), '<br />')),true);
			xmlhttp.send();
			//$('#thecomment'+$('.btn_update_comment').attr('href')).val('');
		}else{
			alert("You must complete the comment first")
		}
		return false;
	});		
	
	
/*------------------ end comments functions -------------------------------------------------------------------------------------------------------------------*/
	


	function escapeHtml(unsafe) {
	    return unsafe
	         .replace(/&/g, "&amp;")
	         .replace(/</g, "&lt;")
	         .replace(/>/g, "&gt;")
	         .replace(/"/g, "&quot;")
	         .replace(/'/g, "&#039;");
	 }

	
	
			
	//profile functions
	
	$('#select_tours').click(function(e){
		e.preventDefault();
		$('#tours').show();
		$('#follows').hide();
		$('#mod').val('tours');
		
		urlstring = location.href;
		secparam = urlstring.split("&")
	
		window.history.pushState('','',secparam[0]);		
		
		
		$('#select_tours').addClass("selected");
		$('.followsInProfile').removeClass("selected");
		setupBlocks();

	})


	$('.followsInProfile').click(function(e){
		$('#loading').show();
		
		

		
		e.preventDefault();
		order = $(this).data("order");
		
		if($(this).data("mod") == undefined){
			mod = $('#mod').val();
		}else{
			mod = $(this).data("mod");
		}

		urlstring = location.href;
		secparam = urlstring.split("&")
	
		window.history.pushState('','',secparam[0]+'&'+mod);		
		
		uid = $('#uid').val();
		cant_reg = $('#cant_reg').val();
				
		$('#select_tours').removeClass("selected");
		
		
		if(!($(this).hasClass("orderers"))){
			$('.followsInProfile').removeClass("selected");
			$('#order_by_cant_tours').addClass("selected");
		}else{
			$('.orderers').removeClass("selected");
		}

		$(this).addClass("selected");
		
		$.ajax({
			url : 'ajax_get_follows.php',
			type: 'GET',
			data: 'uid='+uid+'&mod='+mod+'&o='+order+'&cant_reg='+cant_reg,
			cache : false,
			success : function(response){
				$('#follows_data').html(response);
				$('#tours').hide();
				$('#follows').show();
				$('#loading').hide();
				$('#mod').val(mod);
				if($('.nomorefollows').length > 0){$('.nomorefollows').remove();}
				jQuery('.btn_follow').unbind('click');
				$('.btn_follow').click(function(){
					follow($(this));
				});

			}

		});	
		
	})
	
	//end profile functions
	
	
	
	jQuery( "#scenelist" ).sortable({
		scroll:true,
		scrollSpeed:5,
		stop: function(){
			verificar(true);
			},
		placeholder: "pano-item-placeholder",
		items: "> div.pano-item" ,
		cursor: "move"

	});       
	
				
	jQuery(window).resize(function(){
		heightScreen = jQuery(window).height();
		setupBlocks();
		tourHight();
				/*menu accordion*/
				if(jQuery(".menu").hasClass("ui-accordion")){
					jQuery('.menu').accordion('destroy');
					}
				jQuery('.menu').accordion({ 
						active: false
						,header : "a.browse"
						,collapsible:true
						,heightStyle: "content"
						,activate: function( event, ui ) {
								/*if( jQuery(ui.newHeader).parent().attr('class') == 'content_accorion_items')*/
								jQuery(ui.newHeader).parent().addClass('active');
								jQuery(ui.oldHeader).parent().removeClass('active');			
						}	
				});
	});

	heightScreen = jQuery(window).height();
	tourHight();

	/*menu accordion*/
	jQuery('.acor').accordion({ 
		active: false
		,collapsible:true
		,heightStyle: "content"
		,activate: function( event, ui ) {
			/*if( jQuery(ui.newHeader).parent().attr('class') == 'content_accorion_items')*/
			jQuery(ui.newHeader).parent().addClass('active');
			jQuery(ui.oldHeader).parent().removeClass('active');			
		}	
	});
	
	/*delete notification*/
	/*delete notification*/
	$('#notif_list').on('click', 'a', function (event) {
		//alert ($(this).attr('href'));
		//alert ($(this).attr('rel'));
		//if(document.getElementById("action")){
			//action = document.getElementById("action").value;
			action='notifications';
			id = $(this).attr('rel');
			$('#'+$(this).attr('href')).fadeOut();
 
			var xmlhttp;
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			{
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			  {
				if(xmlhttp.responseText == 'success'){
					//alert("ajax_del_item.php?a="+action+"&id="+id);
				}
			  }
			  
			}
			xmlhttp.open("GET","ajax_del_item.php?a="+action+"&id="+$(this).attr('rel'),true);
			xmlhttp.send();
			return false;
		//}
	});	

	
	/*follow_btn*/
	jQuery('.btn_follow').click(function()	{
		follow($(this));
	});


	
	
	
	/*Login / Register functions ------------------------------------------------------------------------------------------------------------------------*/
	
	/*login_btn*/
	jQuery('.login').click(function(){
		if( popUp == false){
			jQuery('.overlay_login').stop().fadeIn(250);
			popUp = true;
		}
		return false;
	});
	
	jQuery('.forgot_pwd').click(function(){
			hide_popup();
			jQuery('.forgotpassword').stop().fadeIn(250);
			popUp = true;
		return false;
	});

	jQuery('.dismiss').click(function(){
		$(this).closest('div').fadeOut();
		return false;
	});	
	
	/*register_btn*/
	jQuery('.register').click(function(){
		if( popUp == false){
			jQuery('.overlay_register').stop().fadeIn(250);
			popUp = true;
		}
		return false;
	});

	/*Facebook btn*/
	jQuery('.fb-bt').click(function(){
		fb_login(); //definido en fb.js
	});	
	
	/*Googleplus btn*/
	jQuery('.gplus-bt').click(function(){
		gp_login(); //definido en gp.js
	});	

	jQuery(".overlay_register .fb-bt").click(function(){
		$('#regban').val('1');
	})
	
	jQuery(".overlay_register .gplus-bt").click(function(){
		$('#regban').val('1');
	})
	

	/*loginToRegister_btn*/
	jQuery('.loginToRegister').click(function(){
			hide_popup();
			jQuery('.overlay_register').stop().fadeIn(250);
			popUp = true;
		return false;
	});

	$('#resend').click(function(){
		$(this).hide();
		h = document.getElementById("h").value;
		//RESEND DATA BY AJAX
		var xmlhttp;
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById("respta").innerHTML=xmlhttp.responseText;
			}
		  }
		xmlhttp.open("GET","ajax_resend48.php?h="+h,true);
		xmlhttp.send();			
	});
	

	jQuery('.loginaction').click(function(){
		sendLogin();
	});
	
	jQuery('.reset_pwd').click(function(){
		sendReset();
	});
	
	function sendReset(){
		email = document.getElementById('reset_email').value;
		
		if(email == ''){
			document.getElementById("reset_pop").innerHTML='<div class="message_box error_m in_pop"><p>Please complete all fields</p></div>';
		}else{
			//SEND RESET BY AJAX

			var xmlhttp;
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("reset_pop").innerHTML=xmlhttp.responseText;
					$('#reset_email').hide();
					$('.reset_pwd').hide();
				}
			  }
			xmlhttp.open("GET","ajax_reset.php?e="+email,true);
			xmlhttp.send();			
		}
		return false;

}
	
function sendLogin(){
	
		user = document.getElementById('user').value;
		pass = document.getElementById('pass').value;
		remember = document.getElementById('remember').checked;
		
		if (remember){r = 1;}else{r=0;};
		
		if(user == '' || pass == ''){
			document.getElementById("login_pop").innerHTML='<div class="message_box error_m in_pop"><p>Please complete all fields</p></div>';
		}else{
			//SEND LOGIN DATA BY AJAX
			var xmlhttp;
			if (window.XMLHttpRequest)
			  {// code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp=new XMLHttpRequest();
			  }
			else
			  {// code for IE6, IE5
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			  }
			xmlhttp.onreadystatechange=function()
			  {
			  if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					respuesta = JSON.parse(xmlhttp.responseText);
					if (respuesta.success == 'ok'){
						if($('#s_h').val() != respuesta.id){
							window.location.href="home.php";
							mixpanel.identify(respuesta.id);
							
							mixpanel.people.set({
								"$email": respuesta.email,
								
								"$name": respuesta.username
							});								
							mixpanel.track("User log in");
						}else{
							hide_popup();
						}
					}else{
						document.getElementById("login_pop").innerHTML=respuesta.msg;
					}
				}
			  }
			xmlhttp.open("GET","ajax_login.php?r="+r+"&u="+user+"&p="+pass,true);
			xmlhttp.send();			
		}
		return false;

}

function sendRegister(){
		email = document.getElementById('r_email').value;
		email1 = document.getElementById('r_email1').value;
		nickname = document.getElementById('r_nickname').value;
		name = document.getElementById('r_name').value;
		pass = document.getElementById('r_pass').value;
		pass1 = document.getElementById('r_pass1').value;
		subscribe = 0;
		if($("#receive-emails").is(':checked')){
			subscribe = 1;	
		}
		if(email == '' || email1 == '' || name ==='' || pass == '' || pass1 == '' || nickname == ''){
			document.getElementById("register_pop").innerHTML='<div class="message_box error_m in_pop"><p>Please complete all fields</p></div>';
		}else{
		
			if($('.not').length > 0){
				document.getElementById("register_pop").innerHTML='<div class="message_box error_m in_pop"><p>Please fill in all the fields correctly</p></div>';
			}else{
				if(!$("#terminos").is(':checked')){
					document.getElementById("register_pop").innerHTML='<div class="message_box error_m in_pop"><p>Please accept our Terms of Service</p></div>';
				}else{
					//SEND REGISTER DATA BY AJAX
					var xmlhttp;
					if (window.XMLHttpRequest)
					  {// code for IE7+, Firefox, Chrome, Opera, Safari
					  xmlhttp=new XMLHttpRequest();
					  }
					else
					  {// code for IE6, IE5
					  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					  }
					xmlhttp.onreadystatechange=function()
					  {
					  if (xmlhttp.readyState==4 && xmlhttp.status==200)
						{
							if(xmlhttp.responseText == 'exists'){
								document.getElementById("register_pop").innerHTML='<div class="message_box error_m in_pop"><p>Sorry, the E-Mail already exists</p></div>';
							}else{
								if(xmlhttp.responseText == 'nickexists'){
									document.getElementById("register_pop").innerHTML='<div class="message_box error_m in_pop"><p>Sorry, the User Name you choosed already exists. Please select another</p></div>';
								}else{
									$(".data_pop").html(xmlhttp.responseText);
										mixpanel.track("New user Validation");
								}
							}
						}
					  }
					xmlhttp.open("GET","ajax_reg.php?nn="+nickname+"&n="+name+"&e="+email+"&p="+pass,true);
					xmlhttp.send();	
				}
			}
		}
		return false;
}
	
	jQuery('.registeraction').click(function(){
		sendRegister();
	});

/* Comentado por las validaciones.

	jQuery('.overlay_register').find( "input" ).keypress(function (e) {
		if (e.keyCode == 13) {
			sendRegister();
		}
	});
*/	
	
	jQuery('.overlay_login').find( "input" ).keypress(function (e) {
		if (e.keyCode == 13) {
			sendLogin();
		}
	});	
	
	
	
	//Registering Validations -------------------------- 
	
	$("#r_name").focusout(function(e){
		validate_input(e);
	});

	$("#r_nickname").focusout(function(e){
		validate_input(e);
	});	

	$("#r_email").focusout(function(e){
		validate_input(e);
	});
	
	$("#r_email1").focusout(function(e){
		validate_input(e);
	});

	$("#r_pass").focusout(function(e){
		validate_input(e);
	});
	
	$("#r_pass1").focusout(function(e){
		validate_input(e);
	});
	
	function validate_input(e){
		
		$elemp = $(e.target).next(".validate");

		$w_value = $(e.target).val();

		$w_id = $(e.target).attr('id');

		switch($w_id){
		case 'r_name':
			if($w_value == ""){
				type_val = "not";
				message = "The full name must not be null.";
			}else{
				type_val = "ok";
			}
			break;
		case 'r_nickname':
			if ($w_value == ""){
				type_val = "not";
				message = "The username must not be null.";
			}else{

				var xmlhttp;
				if (window.XMLHttpRequest){
					// code for IE7+, Firefox, Chrome, Opera, Safari
				  	xmlhttp=new XMLHttpRequest();
				}else{
					// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function(){
					if (xmlhttp.readyState==4 && xmlhttp.status==200){
						if(xmlhttp.responseText == "exist"){
							type_val = "not";
							message = "Sorry, the User Name you choosed already exists. Please select another";
						}else{
							type_val = "ok";
						}
					}
			  	};
				xmlhttp.open("GET","ajax_reg.php?reg_action=check_user&username="+$w_value,false);
				xmlhttp.send();

			}
			break;
		case 'r_email':
			if ($w_value == ""){
				type_val = "not";
				message = "The email address must not be null.";
			}else{
				if (!validateEmail($w_value)){
					type_val = "not";
					message = "Please write a valid E-Mail address";
				}else{

					var xmlhttp;
					if (window.XMLHttpRequest){
						// code for IE7+, Firefox, Chrome, Opera, Safari
					  	xmlhttp=new XMLHttpRequest();
					}else{
						// code for IE6, IE5
						xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange=function(){
						if (xmlhttp.readyState==4 && xmlhttp.status==200){
							if(xmlhttp.responseText == "exist"){
								type_val = "not";
								message = "Sorry, the E-Mail already exists";
							}else{
								type_val = "ok";
							}
						}
				  	};
					xmlhttp.open("GET","ajax_reg.php?reg_action=check_email&email="+$w_value,false);
					xmlhttp.send();
					
					//type_val = "ok";
				}
			}
			break;
		case 'r_email1':
			if ($w_value == ""){
				type_val = "not";
				message = "The email address must not be null.";
			}else{
				if($w_value != $('#r_email').val()){
					type_val = "not";
					message = "E-Mails don´t match";
				}else{
					type_val = "ok";
				}
			}				
			break;
		case 'r_pass':
			if ($w_value == ""){
				type_val = "not";
				message = "The password must not be null.";
			}else{
				if($w_value.length<6){
					type_val = "not";
					message = "Wrong password format.<br>The password must be at least 6 characters";
				}else{
					type_val = "ok";
				}
			}
			break;
		case 'r_pass1':
			if ($w_value == ""){
				type_val = "not";
				message = "The password must not be null.";
			}else{
				if($w_value != $('#r_pass').val()){
					type_val = "not";
					message = "The password do not match";
				}else{
					type_val = "ok";
				}
			}				
			break;
		}
					
		$ballon = $(".ballon");
	
		if(type_val == "not"){
			$elemp.removeClass("ok").addClass("not");
			$elemp.fadeIn();
			elempos = $elemp.offset();
			$ballon.fadeIn();
			$(".ballon").css({"left" : elempos.left+"px" , "top": + elempos.top - $(".overlay_register").offset().top});
			$ballon.text(message);
		
		}else{
			$elemp.removeClass("not").addClass("ok");
			$elemp.fadeIn();
			$(".ballon").hide();
		}

	}		
	//End Registering Validations --------------------------
	
	
	/*End Login / Register functions ------------------------------------------------------------------------------------------------------------------*/
	
	
	
	
	
	
	/*Blog --------------------------------------------------------------------------------------------------------------------------------------------*/
	
	jQuery('.udblogdata').click(function(e){
		e.preventDefault();
		title = $('#title').val();
		cat = $('#cat').val();
		if(title == ''){
			showMessage('Error', 'Please complete the title of the post');
		}else{
			if(cat == ''){
				showMessage('Error', 'Please select a category');
			}else{
				document.getElementById("form1").submit();
			}
		}
	});

	jQuery('.remove-blog-post').click(function(e){
		if($('#post_id').length > 0){
			id = $('#post_id').val();	
		}else{
			obj = e.target;
			id = $(obj).data('id');
		}
		
		confirmMessage('Manage Blog','Are you sure?', function(){
			$.ajax({
				url : 'ajax_del_post.php',
				type: 'POST',
				data: 'id='+id,
				cache : false,
				success : function(response){
					if(response == 'success'){
						window.location.href="manager_posts.php";
					}else{
						showMessage('ERROR', "An error has occurred or you have not enough privileges");		        		
					}
				}
			});		
		});		
	});
	
	/*End Blog ----------------------------------------------------------------------------------------------------------------------------------------------*/
	
	/*Pages--------------------------------------------------------------------------------------------------------------------------------------------------*/
	jQuery('.udpagedata').click(function(e){
		e.preventDefault();
		title = $('#title').val();
		privacy = $('#privacy').val();
		if(title == ''){
			showMessage('Error', 'Please complete the title of the page');
		}else{
			if (privacy == ''){
				showMessage('Error', 'Please select a privacy');
			}else{
			document.getElementById("form1").submit();
			}
		}
	});
	

	
	jQuery('.remove-static-page').click(function(e){
		if($('#page_id').length > 0){
			id = $('#page_id').val();	
		}else{
			obj = e.target;
			id = $(obj).data('id');
		}
		
		confirmMessage('Manage Static Pages','Are you sure?', function(){
			$.ajax({
				url : 'ajax_del_page.php',
				type: 'POST',
				data: 'id='+id,
				cache : false,
				success : function(response){
					if(response == 'success'){
						window.location.href="manager_pages.php";
					}else{
						showMessage('ERROR', "An error has occurred or you have not enough privileges");		        		
					}
				}
			});		
		});		
	});
	/*End pages ----------------------------------------------------------------------------------------------------------------------------------------------*/
	
	//Delete profile
	jQuery('.deluserdata').click(function(e){
		confirmMessage('Delete Account','Are you sure you want to delete your account?', function(){
			$.ajax({
				url : 'ajax_del_profile.php',
				type: 'GET',
				cache : false,
				success : function(response){
					if (response == 'SUCCESS'){
						showMessage('SEND MAIL', "We sent you an email to confirm the deletion of your account. To confirm this action click on the link in that email, otherwise ignore and nothing will happend.");
					}
				}
			});
		});
	});
	
	
	jQuery('.uduserdata').click(function(){
		document.getElementById("mensajes").innerHTML = '';
		ud_username = document.getElementById('ud_username').value;
		ud_nickname = document.getElementById('ud_nickname').value;
		url_name = document.getElementById('ud_website').value;
		ud_email = document.getElementById('ud_email').value;
		orig_email = document.getElementById('orig_email').value;
		ud_fnac = document.getElementById('ud_fnac').value;
		array_fnac = document.getElementById('ud_fnac').value.split("/");
		month_fnac = array_fnac[0];
		day_fnac = array_fnac[1];
		year_fnac = array_fnac[2];
		ud_country = document.getElementById('ud_country').value;
		ud_state = document.getElementById('ud_state').value;
		ud_city = document.getElementById('ud_city').value;
		ud_website = document.getElementById('ud_website').value;
		ud_twitter = document.getElementById('ud_twitter').value;
		ud_facebook = document.getElementById('ud_facebook').value;
		fb_name = document.getElementById('fb_name').value;
		gp_name = document.getElementById('gp_name').value;


		if(url_name == '' || ud_nickname == '' || ud_username == '' || (ud_email == '' && fb_name == '' && gp_name == '')){
			document.getElementById("mensajes").innerHTML = '<div class="message_box error_m" ><p>Please complete all required fields</p></div>';
		}else{
			if(ud_fnac != '' && (isNaN(day_fnac) || isNaN(month_fnac) || isNaN(year_fnac) || day_fnac > 31 || day_fnac < 1 || month_fnac > 12 || day_fnac < 1 || year_fnac < 1000 || year_fnac > 9999)){
				document.getElementById("mensajes").innerHTML = '<div class="message_box error_m" ><p>Birth date must be in "mm/dd/yyyy" format</p></div>';
			}else{
				if (ud_email != orig_email){
					confirmMessage('ATTENTION','You have changed your E-Mail address.\nSince this is your login user, your account will be automatically unlogged and we will send you an E-Mail with a link to re-activate you account !\nAre you sure?', function(){

						document.getElementById("lo").value=1;
						document.getElementById("update_form").submit();
					});
					/*
					if (confirm('ATTENTION: You have changed your E-Mail address.\nSince this is your login user, your account will be automatically unlogged and we will send you an E-Mail with a link to re-activate you account !\nAre you sure?')) {
						document.getElementById("lo").value=1;
						document.getElementById("update_form").submit();					
					}
					*/
				}else{
					document.getElementById("update_form").submit();
				}

			}
		}
	});

	jQuery('.uduser').click(function(){

		document.getElementById("mensajes").innerHTML = '';
			
		ud_password = document.getElementById('ud_password').value;
		ud_repeat_password = document.getElementById('ud_repeat_password').value;

		if(ud_password == '' || ud_repeat_password == ''){
			document.getElementById("mensajes").innerHTML = '<div class="message_box error_m" ><p>Please set a new password</p></div>';
		}else{
			if(ud_password.length < 6){
				document.getElementById("mensajes").innerHTML='<div class="message_box error_m"><p>Wrong password format.<br>The password must be at least 6 characters.</p></div>';
			}else{		
				if(ud_password != ud_repeat_password){
					document.getElementById("mensajes").innerHTML = '<div class="message_box error_m" ><p>Passwords don´t match</p></div>';
				}else{		
					//UPDATE DATA BY AJAX
					var xmlhttp;
					if (window.XMLHttpRequest)
					  {// code for IE7+, Firefox, Chrome, Opera, Safari
					  xmlhttp=new XMLHttpRequest();
					  }
					else
					  {// code for IE6, IE5
					  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					  }
					xmlhttp.onreadystatechange=function()
					  {
					  if (xmlhttp.readyState==4 && xmlhttp.status==200)
						{
							document.getElementById("mensajes").innerHTML=xmlhttp.responseText;
							if ($("#change_pwd_btn").length > 0){
								$("#change_pwd_btn").hide();
								$("#login_pwd_btn").show();
								$("#inputs").hide();
								$("#titulo").html('<h3>Login</h3>');
								
//								$("#span_login").html('');
							}	
						}
					  }
					xmlhttp.open("GET","udpwd.php?h="+$('#h').val() + "&ud_password="+ud_password,true);
					xmlhttp.send();			
				}
			}
		}
	});

	
	
	
	jQuery('.closed').click(function(){
		hide_popup();
	});
	


	/*btn language*/
	jQuery('.language').hover(
		function(){
			jQuery('.select_language').stop().fadeIn( speed_language);
		},
		function(){
			jQuery('.select_language').stop().fadeOut( speed_language);
		}
	);

/*	jQuery('.select_language').on("mouseleave",function(){
		jQuery('.select_language').stop().fadeOut( speed_language);
	});*/


	/*btn language*/
	jQuery('.search').hover(
		function(){
			jQuery('.predictive').stop().fadeIn(250, "easeOutCirc")
		},
		function(){
			jQuery('.predictive').stop().fadeOut(250, "easeOutCirc")
		}
	);


	jQuery('.loading').click(function(){
		jQuery(this).fadeOut(250);
	});


	
	/*btn_map*/
	jQuery(".btn_map").click(function(){
		if(map_tour == false){
			jQuery(this).addClass('active');
			jQuery('.content_map').stop().animate({'width':'500px'}, 250);
			jQuery('.content_tour').stop().animate({'left':'544px'}, 250, function(){
				map_tour = true;
				//initialize();
			});
		}else{
			jQuery(this).removeClass('active');
			jQuery('.content_map').stop().animate({'width':'0px'}, 250);
			jQuery('.content_tour').stop().animate({'left':'42px'}, 250, function(){
				map_tour = false;
				//jQuery('.content_map').empty();	
			});
		}
		return false;
	});
	
	/*btn embed*/
	jQuery(".btn_embed").click(function(){
		if(embed_tour == false){
			jQuery(this).addClass('active');
						
						var headerHeight = 69;
						var offsetTop = jQuery(this).offset().top-headerHeight;                        
						jQuery('.embed').css('top', offsetTop);
						
			jQuery('.embed').stop().fadeIn(350, function(){
				embed_tour = true;
			});


			jQuery('.btn_share').removeClass('active');
			jQuery('.share').stop().animate({'width':'0'}, 400, "easeOutCirc",function(){
				jQuery('.share').stop().fadeOut(0,function(){
					share_tour = false;
				});	
			});
		}
		else{
			jQuery(this).removeClass('active');
			jQuery('.embed').stop().fadeOut(350,function(){
				embed_tour = false;
			});
		}
		return false;
	});

	/*btn share*/
	jQuery(".btn_share").click(function(){
		if(share_tour == false){
			jQuery(this).addClass('active');
						
						var headerHeight = 69;
						var offsetTop = jQuery(this).offset().top-headerHeight;                        
						jQuery('.share').css('top', offsetTop);
						
			jQuery('.share').stop().fadeIn(0, function(){
				jQuery('.share').stop().animate({'width':'150px'}, 400, "easeOutCirc", function(){
					share_tour = true;
				});	
			});

			jQuery('.btn_embed').removeClass('active');
			jQuery('.embed').stop().fadeOut(250,function(){
				embed_tour = false;
			});
		}
		else{
			jQuery(this).removeClass('active');
			jQuery('.share').stop().animate({'width':'0'}, 400, "easeOutCirc",function(){
				jQuery('.share').stop().fadeOut(0,function(){
					share_tour = false;
				});	
			});
		}
		return false;
	});
	
		jQuery('#browse-file, .upload-more, .upload-cloud').click(function(e){
			if (!$(e.target).hasClass('select-from')) {
				$("#browseFile").trigger('click'); 
				return false;
			}
		});        

	
	/*nav mobile*/
	jQuery('.nav_btn_mobile').click(function(){
		if(navMobile == false){
			jQuery('.wrapper').stop().animate({'left':'255px'},250, "easeOutCirc");
			jQuery('.header').stop().animate({'left':'255px'},250, "easeOutCirc");
			jQuery('.search').stop().animate({'left':'0'},250, "easeOutCirc");
			jQuery('.nav_btn_mobile').stop().addClass('nav_btn_mobile_active');
			jQuery('.nav').stop().animate({'left':'0'},250, "easeOutCirc", function(){
				navMobile = true;
			});
		}
		else{
			jQuery('.wrapper').stop().animate({'left':'0'},250, "easeOutCirc");
			jQuery('.header').stop().animate({'left':'0'},250, "easeOutCirc");
			jQuery('.search').stop().animate({'left':'-255px'},250, "easeOutCirc");
			jQuery('.nav_btn_mobile').stop().removeClass('nav_btn_mobile_active');
			jQuery('.nav').stop().animate({'left':'-255px'},250, "easeOutCirc", function(){
				navMobile = false;
			});
		}

	});
	
	
	//SUGERENCIA PARA IMPLEMENTACION DE BUSQUEDA PREDICTIVA
	/* jQuery( "#search-input" ).autocomplete(	 
	 {
		source: function( request, response ) 
		{
			jQuery.ajax({
				url: "http://ws.geonames.org/searchJSON",
				dataType: "jsonp",
				data: {
				featureClass: "P",
				style: "full",
				maxRows: 12,
				name_startsWith: request.term
				},
				success: function( data ) {
					response( jQuery.map( data.geonames, function( item ) {
						return {
							label: item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
							value: item.name
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			alert( ui.item ?
			"Selected: " + ui.item.label :
			"Nothing selected, input was " + this.value);
		},
		open: function() {
			jQuery( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			jQuery( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
*/

	jQuery('.nav #notifications').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		if($('.notif_item').length == 0){   //si no hay notificaciones, tomo las 1eras, definido en ajaxscrillnotif.js
			get_notif('');
		}
		
		jQuery("#notifications-wrapper").toggle();

	})
	

	jQuery(document).click(function(){
		jQuery("#notifications-wrapper").hide();
			$("body").css({ "overflow-y": 'auto' })
	})
	
	
	$(".content_nav, .overlay_login .pop, .overlay_register .pop").mCustomScrollbar({
		theme:"minimal-dark",
		scrollInertia:300,
		callbacks:{
			onScrollStart:function(){
				$(".ballon").hide();
			}
		}		
	});	

	$(".content_nav, .overlay_login .pop, .overlay_register .pop").on({
			mouseenter:function(){
				$('body').on({
					           'mousewheel': function(e) {
					           e.preventDefault();
					           e.stopPropagation();
					           }
					       });
			},
			mouseleave:function(){
				 $('body').unbind('mousewheel');
			}
		})
	
	});


jQuery(window).load(function(){

		setupBlocks();
		document.getElementById("loading").style.display="none";

})

function sendReport(type, extras){
	$.ajax({
		url : 'php-stubs/send_report.php',
		type: 'POST',
		async: false,
		data: 'type='+type+'&extras='+extras,
		cache : false,
		success : function(response){}
	});		
	
}


function tourHight(){
	totalHeight = heightScreen - margenInferiorTour;
	jQuery('.tour').stop().css({'height': totalHeight});
	jQuery('.modulo_user').stop().css({'top':totalHeight})
}

function hide_popup() 
{
	jQuery('.overlay').stop().fadeOut(250);
	popUp = false;	
	$(".ballon").hide();
	return false;

}

function clear_counters(object_class_id){
	jQuery('.'+object_class_id).hide();
}


/*post*/
var colCount = 0;
var colWidth = 0;
var margin = 6;
var windowWidth = 0;
var blocks = [];
var destacado;
var colsDestacado = 3;
var altoDestacado;
var anchoDestacado;

function setupBlocks() {

	anchoDestacado = jQuery('.post-highlight').width();
	//var altoDestacado = jQuery('.post-highlight').outerHeight(true)+(margin*2);        
		altoDestacado = jQuery('.post-highlight').outerHeight(true);
		
	
	windowWidth = jQuery('.wrapper').width() - 17;
	colWidth = jQuery('.post').outerWidth();
	blocks = [];
	colCount = Math.floor(windowWidth/(colWidth+margin*2));
//console.log(colCount)	;
	var colsDestacado = Math.floor(anchoDestacado/(colWidth));

	
	for(var i=0;i<colCount;i++)
	{
		if( i < colsDestacado)
			blocks.push(altoDestacado+margin);
		else
			blocks.push(margin);	
		   // console.log(i + ': ' + blocks[i])	;	
	}
	
	positionBlocks();
	
}



function positionBlocks() {
	jQuery('.post').each(function(){
		var min = Array.min(blocks);

		var index = jQuery.inArray(min, blocks);
			//alert(min+' '+blocks+' '+index)	;
		var leftPos;
		//altoDestacado = jQuery('.post-highlight').outerHeight(true)+(margin*2);
		//var anchoDestacado = jQuery('.post-highlight').width();
		var colsDestacado = Math.floor(anchoDestacado/(colWidth));

//		if( min < altoDestacado){
//			leftPos = margin+((colsDestacado)*(colWidth+margin));
//		}
//		else{
//			leftPos = margin+((index)*(colWidth+margin));			
//		}

				leftPos = margin+((index)*(colWidth+margin));	
				
		jQuery(this).stop().animate({
				'left':leftPos+'px'
				,'top':min+'px'
				,'opacity': '1'
			}, 500, "easeInOutCirc", function(){
			
				lastItemPos = $(".wrapper .post:last").position().top;
				itemHeight = $(".wrapper .post:last").height();

				$(".wrappper-posts").height(lastItemPos+itemHeight);
		});
		/*
		if( index < colsDestacado && min < altoDestacado)
		{
			//blocks[index] = altoDestacado+min+jQuery(this).outerHeight()+margin;
			blocks[index] = altoDestacado+jQuery(this).outerHeight()+margin;
		}
		else
		{
			blocks[index] = min+jQuery(this).outerHeight()+margin;
		}
		*/
		blocks[index] = min + jQuery(this).outerHeight(true);
	});	
}

Array.min = function(arr) {
	return Math.min.apply(Math, arr);
};

/*Create notification*/
function notificate(target, type){

	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	{
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	  {
		 //alert(xmlhttp.responseText); /*do something if you want*/
	  }
	}
	xmlhttp.open("GET","ajax_notice.php?type="+type+"&t="+target,true);
	xmlhttp.send();
	return false;		
}

function changePage(e){
	e.preventDefault(); 
	p = $(e.target).data("page"); 
	document.form1.action = location.href; 
	$('#p').val(p); 
	document.form1.submit(); 
}

function follow(t){
	btn_pressed = $(t);
	var action = 'f';
	if(btn_pressed.attr('class').indexOf("following") >= 0){
		action = 'u';
	}
	
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	{
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	  {
		if(xmlhttp.responseText == 'success'){
			btn_pressed.toggleClass("follow following");
			if(action == 'f'){
				btn_pressed.children(".following-text").text("Following");
				btn_pressed.children(".unfollow-text").text("Unfollow");
				$("#nav_following").text(parseFloat($("#nav_following").text())+1);
				mixpanel.track("Follow user");
			}else{
				btn_pressed.children(".following-text").text("Follow");
				btn_pressed.children(".unfollow-text").text("Follow");	
				$("#nav_following").text(parseFloat($("#nav_following").text())-1);
				mixpanel.track("Unfollow user");
			}
		}else{

			var html = '<div class="overlay show-response chk_session_overlay">'
				  +'      <div class="pop">'
				  +'          <a href="#" class="closed"  onclick="hide_popup();"></a>'
				  +'          <h2>Ooops !</h2>'
				  +'          <div class="content_pop">'
				  +'              <form class="pop-up">'
				  +'                  <label>           '    
				  +'                      <p>Your session expired</p>'
				  +'                  </label>'
				  +'                    <div class="content-btn-pop">'
				  +'                        <a href="" class="red-button border-radius-4 go_login">Login</a>'
				  +'                    </div>'
				  +'             </form>'
				  +'          </div>'
				  +'      </div>'
				  +'  </div>';
		  
		var el = jQuery(html).appendTo('body');
		var $pop = jQuery(el).children('.pop');
		jQuery(el).fadeIn(200);
		 $(".go_login").click(function(event){
			 event.preventDefault();
			 jQuery(el).fadeOut(200);
			 $('.login-register .login').trigger("click");
		 });
		}
			
		if(action == 'f'){notificate(btn_pressed.attr('rel'), 1);};
	  }
	}
	xmlhttp.open("GET","ajax_follow.php?a="+action+"&id="+btn_pressed.attr('rel'),false);
	xmlhttp.send();
	return false;
}


////////////////////////////////////////////////////////////////////////////////////
