window.onload = function() {

//AVATAR	

	var uploader_avatar = new ss.SimpleUpload({
	  button: document.getElementById('upload-avatar-btn'), // file upload button
	  url: 'php-stubs/avatar-image-uploading.php', // server side handler
	  name: 'uploadfile', // upload parameter name        
	  progressUrl: 'php-stubs/uploadProgress.php', // enables cross-browser progress support (more info below)
	  responseType: 'json',
	  allowedExtensions: ['jpg'],
	  maxSize: 1024, // kilobytes
	  hoverClass: 'ui-state-hover',
	  focusClass: 'ui-state-focus',
	  disabledClass: 'ui-state-disabled',
	  onSubmit: function(filename, extension) {
	     // this.setFileSizeBox(sizeBox); // designate this element as file size container
	     // this.setProgressBar(progress); // designate as progress bar
		  document.getElementById("elavatar").src = "images/wait.gif";
	      document.getElementById("nav_avatar").src = "images/wait.gif";		  
	    },         
	  onComplete: function(filename, response) {
	      if (!response) {
	          alert(filename + 'upload failed');
	          return false;            
	      }

	      	var result = response.result;
	      	var msg = response.msg;
	    	var nombre_file  = response.file;
	      	  
		      //alert(filename + ' ' + nombre_file + ' upload CORRECT !');
	    	  //alert(nombre_file);
    	
		      document.getElementById("elavatar").src = "images/users/" + nombre_file;
		      document.getElementById("nav_avatar").src = "images/users/" + nombre_file;
		      return false; 
	    },
	    onExtError: function(){
		    alert("This is not a permitted file type. Only JPG files are allowed");
	    },
	    onSizeError: function(filename){
		    alert(filename+" is too big. (1024K max file size)");
	    }	
	});    


	

//COVER

	var uploader_cover = new ss.SimpleUpload({
	  button: document.getElementById('upload-cover-btn'), // file upload button
	  url: 'php-stubs/cover-image-uploading.php', // server side handler
	  name: 'uploadfile', // upload parameter name        
	  progressUrl: 'php-stubs/uploadProgress.php', // enables cross-browser progress support (more info below)
	  responseType: 'json',
	  allowedExtensions: ['jpg'],
	  maxSize: 5120, // kilobytes (5MB)
	  hoverClass: 'ui-state-hover',
	  focusClass: 'ui-state-focus',
	  disabledClass: 'ui-state-disabled',
	  onSubmit: function(filename, extension) {
	     // this.setFileSizeBox(sizeBox); // designate this element as file size container
	     // this.setProgressBar(progress); // designate as progress bar
		  //document.getElementById('elcover').style.backgroundSize = "200px 200px";
		  //document.getElementById('elcover').style.backgroundImage = "url('images/wait.gif')";
		 document.getElementById('upload-cover-btn').innerHTML = 'Uploading image <img src="images/wait.gif" width="20" height="20">';
				 
	    },         
	  onComplete: function(filename, response) {
	      if (!response) {
	          alert(filename + 'upload failed');
	          return false;            
	      }

	      	var result = response.result;
	      	var msg = response.msg;
	    	var nombre_file  = response.file;
	      	  
		      //alert(filename + ' ' + nombre_file + ' upload CORRECT !');
	    	  //alert(msg);
	    	  //document.getElementById('elcover').style.backgroundSize = "";
	    	  document.getElementById('elcover').style.background = "url('images/users/cover/" + nombre_file + "') repeat-x left";
	    	  document.getElementById('upload-cover-btn').innerHTML = 'Change cover';
		      return false; 
	    },
	    onExtError: function(){
		    alert("This is not a permitted file type. Only JPG files are allowed");
	    },
	    onSizeError: function(filename){
		    alert(filename+" is too big. (5MB max file size)");
	    }	
	});

};