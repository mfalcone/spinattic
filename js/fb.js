// Load the SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function() {

	FB.init({
	    appId      : '540074362765530',
	    cookie     : true,  // enable cookies to allow the server to access the session
	    xfbml      : true,  // parse social plugins on this page
	    version    : 'v2.0' // use version 2.0
	});

	
};

function revokeFB(){
	FB.api(
		    "/me/permissions/",
		    "DELETE",
		    function (response) {
		      if (response && !response.error) {
		        /* handle the result */
		      }
		    }
	);
	
}

function getFBdata(){
	FB.api('/me', function(response) {
	    //console.log(response.name);
	    //console.log(response.id);
		//console.log(response);
	      
		FBname = '';
		FBemail = '';
		FBid = '';
		FBlink = '';
		
		FBlink = response.link;
		FBname = response.name;
		FBid = response.id; //uso el id de facebook como hashregistro
		FBemail = response.email;
		subscribe = 0;
		if($("#receive-emails").is(':checked')){
			subscribe = 1;	
		}		

		//SEND LOGIN/REGISTER DATA BY AJAX
		
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
			  //console.log (xmlhttp.responseText);
			  	respuesta = JSON.parse(xmlhttp.responseText);
				if (respuesta.success == 'ok'){
					
					if($('#s_h').val() != respuesta.id){
						window.location.href="home.php";
					}else{
						hide_popup();
					}					
					
					if($('#regban').val() == '1'){
						$('#regban').val('');
						mixpanel.track("New user from Facebook");
					}else{
						mixpanel.identify(respuesta.id);
						
						mixpanel.people.set({
						    "$email": respuesta.email,
						    
						    "$name": respuesta.username
						});								
						mixpanel.track("User log in");
					}						
					


				
					
				}else{
					$(".data_pop").html('<div class="message_box error_m" ><p>Sorry, we need an e-mail to sign you up</p><a href="'+location.href.replace('#', '')+'">Try again</a></div>');
					revokeFB();
				}
		    }
		  };
		xmlhttp.open("GET","ajax_FB_login_reg.php?mail="+FBemail+"&link="+FBlink+"&h="+FBid+"&n="+FBname+"&s="+subscribe,true);
		xmlhttp.send();		
		
		
	});		
}

/*
function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
}
*/

function statusChangeCallback(response) {
    if (response.status === 'connected') {
    	getFBdata();
    }
}

function fb_login(){
 FB.login(function(response) {
  statusChangeCallback(response);
 }, {scope: 'email,public_profile'});
}