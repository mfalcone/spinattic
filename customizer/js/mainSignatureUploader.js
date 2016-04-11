require.config({
  waitSeconds: 500,
  paths: {
    jquery: 'lib/jquery/jquery-min',
    underscore: 'lib/underscore/underscore-min',
    backbone: 'lib/backbone/backbone-min',
    templates: '../templates',
    mCustomScrollbar: 'lib/mCustomScrollbar/jquery.mCustomScrollbar.concat.min',
    jqueryui:'lib/jqueryui/jquery-ui',
    bowser: 'lib/browserDetect/bowser',
    filedropsingle:'lib/filedrop/jquery.filedrop-single',

  },
  shim:{
    mCustomScrollbar:['jquery'],
    jqueryui:['jquery'],
  },
  urlArgs: "v=0.1.4" 

});

require([
  'jquery',
  'views/signatureuploader/MainSignatureUploader'
], function($,MainSignatureUploader){
    
    var mainSignatureUploader = new MainSignatureUploader();
    mainSignatureUploader.render();

     }
     
);