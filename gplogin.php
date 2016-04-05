  <!-- Coloca este JavaScript as�ncrono justo delante de la etiqueta </body> -->
  <script type="text/javascript">
  (function() {
    var po = document.createElement('script');
    po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client:plusone.js?onload=render';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(po, s);
  })();

  function render() {
    gapi.signin.render('googlePlusBTN', {   //el ID del Div que contiene el boton 
      'callback': 'signinCallback',   //la funcion que se llama cuando se hace el login o no 
      //'clientid': '984905783520-hjpnm4e7q4dl5kfvctuo30na4jssncu9.apps.googleusercontent.com', //el ID del cliente que cre�
      'clientid': '984905783520-utpan90kap5caqntsjn4brs845btbjtt.apps.googleusercontent.com', //el ID del cliente que cre�
      'cookiepolicy': 'single_host_origin',
      'requestvisibleactions': 'http://schemas.google.com/AddActivity',
      'scope': 'https://www.googleapis.com/auth/plus.login'
    });
  }


//la funcion que se llama cuando se hace el login o no (seteada en render())

  function signinCallback(authResult) { 
  	  if (authResult['access_token']) {
  	    // Autorizado correctamente
  	    // Oculta el bot�n de inicio de sesi�n ahora que el usuario est� autorizado, por ejemplo:
  	    //document.getElementById('signinButton').setAttribute('style', 'display: none');
  	     console.log('ok');
  		//  alert("OK");
  	  } else if (authResult['error']) {
  	    // Se ha producido un error.
  	    // Posibles c�digos de error:
  	    //   "access_denied": el usuario ha denegado el acceso a la aplicaci�n.
  	    //   "immediate_failed": no se ha podido dar acceso al usuario de forma autom�tica.
  	     console.log('There was an error: ' + authResult['error']);
  		//  alert("ERROR");
  	  }
  	}  

  </script>
  <span class="label">Sign in with:</span>

  <!-- adentro del div se puede poner un span o un a href para aplicarle el dise�o al boton (se le aplica al elemento que esta adentro del div) -->
	<div id="googlePlusBTN">
		<a href="#" class="googleplus"></a>
	</div>
