(function() {
    var po = document.createElement('script');
    po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client:plusone.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
  })();


	var loginFinished = function(authResult) {
	  if (authResult) {
	    if (authResult['status']['signed_in'] && authResult['status']['method'] == 'PROMPT') {
	      gapi.auth.setToken(authResult);
	      getdata();
	    }
	  }
	  };


	  function getdata(){
		    // Carga las bibliotecas oauth2 para habilitar los métodos userinfo.
		    gapi.client.load('oauth2', 'v2', function() {
		          var request = gapi.client.oauth2.userinfo.get();
		          request.execute(getDataCallback);
		        });
		  }

	  function getDataCallback(obj){

		  console.log(obj['id']);
		  console.log(obj['name']);
		  console.log(obj['email']);
		  console.log(obj['picture']);
		  console.log(obj);
		  subscribe = 0;
			if($("#receive-emails").is(':checked')){
				subscribe = 1;	
			}		  

			GPname = obj['name'];
			GPid = obj['id']; //uso el id de googleplus como hashregistro
			GPemail = obj['email'];
			GPpic = obj['picture']; 
			
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
				  console.log (xmlhttp.responseText);
				  	respuesta = JSON.parse(xmlhttp.responseText);
					if (respuesta.success == 'ok'){

						if($('#s_h').val() != respuesta.id){
							window.location.href="home.php";
						}else{
							hide_popup();
						}						
						
						if($('#regban').val() == '1'){
							$('#regban').val('');
							mixpanel.track("New user from Google+");
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
					}
			    }
			  };
			xmlhttp.open("GET","ajax_GP_login_reg.php?pic="+GPpic+"&e="+GPemail+"&h="+GPid+"&n="+GPname+"&s="+subscribe,true);
			xmlhttp.send();				  
		  
	  }
	  
	  
	  var options = {'callback': loginFinished,
      //'clientid': '984905783520-hjpnm4e7q4dl5kfvctuo30na4jssncu9.apps.googleusercontent.com',
	  'clientid': '984905783520-utpan90kap5caqntsjn4brs845btbjtt.apps.googleusercontent.com',
      'cookiepolicy': 'single_host_origin',
      'requestvisibleactions': 'http://schemas.google.com/AddActivity',
      'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email'			  
	  };

	  function gp_login(){
	    gapi.auth.signIn(options);
	  }

 

function disconnectUser(access_token) {
	  var revokeUrl = 'https://accounts.google.com/o/oauth2/revoke?token=' +
	      access_token;

	  // Realiza una solicitud GET asíncrona.
	  $.ajax({
	    type: 'GET',
	    url: revokeUrl,
	    async: false,
	    contentType: "application/json",
	    dataType: 'jsonp',
	    success: function(nullResponse) {
	      // Lleva a cabo una acción ahora que el usuario está desconectado
	      // La respuesta siempre está indefinida.
	    },
	    error: function(e) {
	      // Gestiona el error
	      // console.log(e);
	      // Puedes indicar a los usuarios que se desconecten de forma manual si se produce un error
	      // https://plus.google.com/apps
	    }
	  });
	}
	// Se puede activar la desconexión haciendo clic en un botón
	$('#revokeButton').click(disconnectUser);
