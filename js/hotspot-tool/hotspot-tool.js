var krpano;
var current_scene_id = null;

var scenesSettings = new Hashtable();

var ddData=null;
var scenes_retrieved = false;

/*
 * HOTSPOT ITEM TEMPLATE 
 *
 */
var hs_template = 
    '<div class="hs %TYPE% %OBJECT_ID%" data-id="%OBJECT_ID%">'+ 
        '<div class="icon-type"> '+ 
            ' <img src="images/icons/hspot-%TYPE%.png" width="24"> '+ 
        '</div> '+ 
        '<div class="loader-item">'+ 
            '   <h3>%TITLE%</h3> '+                             
        '</div>   '+                
        '<a class="hs-remove" href="#"></a>'+
        '<br clear="all">'+ 
    '</div>\n\
    <div class="settings" style="display:none;">%SETTINGS%</div>';

/*
 * HOTSPOT'S TYPES TEMPLATES 
 *
 */
var hs_settings_info_template_new = 
    '<label class="title">'+             
        '<input placeholder = "Title" '+
                'value="" '+                
                'name="hs-title" '+
                'class="info-tour border-radius-4 hs-title">'+ 
    '</label><br clear="all">'+ 
    '<label class="text">'+                         
        '<textarea placeholder = "Type your text here..."'+                  
                  'name="hs-text" '+
                  'class="info-tour border-radius-4 form-user hs-text"></textarea>'+ 
    '</label><br clear="all">';

var hs_settings_info_template = 
    '<label class="title">'+             
        '<input placeholder = "Title"'+
                'value="%TITLE%" '+                
                'name="hs-title" '+
                'class="info-tour border-radius-4 hs-title">'+ 
    '</label><br clear="all">'+ 
    '<label class="text">'+                         
        '<textarea placeholder = "Type your text here..."'+                  
                  'name="hs-text" '+
                  'class="info-tour border-radius-4 form-user hs-text">%TEXT%</textarea>'+ 
    '</label><br clear="all">';

var hs_settings_link_template = '<div class="dropdown-scenes"></div><br clear="all">';

var hs_settings_media_template_new = 
    '<div class="media-container">'+             
        '<div id="%INPUT_NAME%" class="mediaUpload" style="border: 1px solid #666666;border-radius: 22px;font-size: 14px;height: 20px;margin-left: 30px;overflow: hidden;padding: 8px;width: 175px;">'+
        'Select a picture to upload'+'<input type="file" name="%INPUT_NAME%" style="margin-left:300px;">'+
        '</div>'+        
        '<br> <div id="size_%INPUT_NAME%" style="font-size: 12px;margin: 0 auto;width: 155px;"></div>'+
        //'<img id="hs-media-loader" src="js/hotspot-tool/loader-b.gif" style="display:none;width:21px;margin:10px 120px;">'+
        //'<br clear="all">'+
    '</div>';

var hs_settings_media_template = 
    '<div class="media-container">'+             
        '<img src="%FILE_PATH%" alt="%FILE_NAME%" height="45"/>'+             
        '<p>%FILE_NAME%</p>'+
        '<br clear="all">'+
        //'<textarea style="margin-left:0;" class="info-tour border-radius-4 form-user tooltip" name="tooltip" onblur="if(this.value == \'\') this.value=\'Type your tooltip here...\';" onfocus="if(this.value == \'Type your tooltip here...\')this.value=\'\';">%TOOLTIP%</textarea>'+
        '<input type="text" value="%TOOLTIP%" style="margin-left:0;" class="info-tour border-radius-4 form-user tooltip" name="tooltip" placeholder="Type your tooltip here..."/>'+
        '<br clear="all">'+
    '</div>';

function sceneAlreadyLoaded(scene_id)
{
    return scenesSettings.containsKey(scene_id) && scenesSettings.get(scene_id).initialized;
}

function initTool()
{
    krpano = document.getElementById("krpanoSWFObject");

    if (!sceneAlreadyLoaded(current_scene_id))
    {    
        scenesSettings.put(current_scene_id, {
            id:null, 
            name:null,        
            hotspots:null,
            last_hotspot_index:null,
            view: {
                hlookat:       000,
                vlookat:       000,
                fovtype:       'MFOV',
                fov:           '95.000',
                maxpixelzoom: '1.5',
                fovmin:        '60',
                fovmax:        '120',
                limitview:    'auto'  
            },            
            preview:null,
            cube:null,
            cubemobile:null,
            initialized:false
        });

        scenesSettings.get(current_scene_id).id    = current_scene_id;
        scenesSettings.get(current_scene_id).name  = 'scene_'+current_scene_id;
    }    
  
    getCurrentHotspots();    
}

function getCurrentHotspots()
{  
    //var xml_path = "tours/"+gTour_id+"/tour"+xml_version+".xml";
    var xml_path = "php-stubs/get_xml_from_db.php";
    var data = "tour-id="+gTour_id+"&d="+$('#draft_subscript').val();
    
    scenes_retrieved = false;    
    
    doAjaxRq(
        'POST', 
        xml_path, 
        data, 
        function(){
            $('#hs-loader').css('display', 'block');
        }, 
        function(response){
            $('#hs-loader').css('display', 'none');
            
            if (!sceneAlreadyLoaded(current_scene_id))
            {    
                var xmlDoc  = $.parseXML( response );
                var $xml    = $( xmlDoc );

                var indexes = new Array(0,0);
                var $scene = $xml.find( "[name='"+scenesSettings.get(current_scene_id).name+"']" );
                //var $scene = $xml.find( "[name='scene_"+current_scene_id+"']" );

                var $view          = $scene.find( "view" );
                var $preview       = $scene.find( "preview" );
                var $image         = $scene.find( "image" );
                var $hotspots      = $scene.find( "hotspot" );

                scenesSettings.get(current_scene_id).preview     = $preview.attr('url');
                scenesSettings.get(current_scene_id).cube        = $image.children('cube').attr('url');
                scenesSettings.get(current_scene_id).cubemobile  = $image.children('mobile').children('cube').attr('url');
                scenesSettings.get(current_scene_id).view        = {
                                                                        hlookat:       $view.attr('hlookat'),
                                                                        vlookat:       $view.attr('vlookat'),
                                                                        fovtype:       $view.attr('fovtype'),
                                                                        fov:           $view.attr('fov'),
                                                                        maxpixelzoom: $view.attr('maxpixelzoom'),
                                                                        fovmin:        $view.attr('fovmin'),
                                                                        fovmax:        $view.attr('fovmax'),
                                                                        limitview:    $view.attr('limitview')
                                                                   };
                scenesSettings.get(current_scene_id).hotspots    = new Hashtable();
                scenesSettings.get(current_scene_id).initialized = true;
                                     
                $hotspots.each(function(i, hs)
                {   
                    var $hs        = $(hs);
                    var extra_data = {};
                    var arrName    = $hs.attr('name').split('_'); // scene_%d
                    
                    var _type      = arrName[0];
                    var _idx       = arrName[1];
                                        
                    indexes.push(_idx);

                    switch (_type)
                    {
                        case 'media':
                            extra_data = ($hs.attr('pic'))? {  
                                                pic:  $hs.attr('pic'),
                                                tooltip:  $hs.attr('tooltip')
                                            } : null;
                                                
                            break;
                        case 'info':
                            extra_data = ($hs.attr('infotitle'))? {  
                                                infotitle:  Encoder.htmlEncode($hs.attr('infotitle')),
                                                infotext:  Encoder.htmlEncode($hs.attr('infotext'))
                                            } : null;
                            break;
                        case 'link':
                            extra_data = ($hs.attr('linkedscene'))? {                    
                                                linkedscene: $hs.attr('linkedscene')
                                            } : null;
                            break;    
                    }

                    scenesSettings.get(current_scene_id).hotspots.put(parseInt(_idx), {
                            idx: _idx,
                            ath: $hs.attr('ath'),
                            atv: $hs.attr('atv'),
                            name: $hs.attr('name'),
                            zoom: $hs.attr('zoom'),                    
                            scale: $hs.attr('scale'),                        
                            type: _type,        // extracted from hotspot name 
                            extra: extra_data                        
                        });
                    
                    
                });    
            
                scenesSettings.get(current_scene_id).last_hotspot_index = Math.max.apply(Math, indexes);

            }
            

            loadCurrentScene( 
                    scenesSettings.get(current_scene_id).view, 
                    {
                        url: scenesSettings.get(current_scene_id).preview
                    }, 
                    {
                        cubeurl:    scenesSettings.get(current_scene_id).cube,
                        mobileurl:  scenesSettings.get(current_scene_id).cubemobile
                    }
            );


            scenesSettings.get(current_scene_id).hotspots.each(function(i, hs)
            {   
                addHotspotFromXML(hs);                
            });
            
            executeAccordion();
            captureRemoveButtons();
            
            $('.hs-collection').jScrollPane();
        }
    );
}





function setAsStartupView()
{
    scenesSettings.get(current_scene_id).view = {
        hlookat      : krpano.get('view.hlookat'),
        vlookat      : krpano.get('view.vlookat'),
        fovtype      : krpano.get('view.fovtype'),
        fov          : krpano.get('view.fov'),
        maxpixelzoom : krpano.get('view.maxpixelzoom'),
        fovmin       : krpano.get('view.fovmin'),
        fovmax       : krpano.get('view.fovmax'),
        limitview    : krpano.get('view.limitview')
    };
}

function resetCollection()
{
    $('.hs-collection').html('<br><br><br><br><br><br><br><br><br>');
}

function beforeAppend(data)
{
    var settings_template=null;
    
    switch (data.type)
    {
        case 'info':
            settings_template = (data.extra)? 
                                    hs_settings_info_template.
                                    replace(/%TITLE%/g, data.extra.infotitle).
                                    replace(/%TEXT%/g, data.extra.infotext) :
                                    
                                    hs_settings_info_template_new ;
            break;
        case 'media':
            //var salt = CryptoJS.lib.WordArray.random(128/8);
            //var hash = CryptoJS.MD5(salt);
            
            settings_template = (data.extra)? 
                                  hs_settings_media_template.
                                    replace(/%FILE_PATH%/g, 'tours/'+gTour_id+'/photos/'+data.extra.pic).
                                    replace(/%FILE_NAME%/g, data.extra.pic).
                                    replace(/%TOOLTIP%/g, data.extra.tooltip):
                                    
                                  hs_settings_media_template_new.                                   
                                    replace(/%INPUT_NAME%/g, 'input_file_'+data.idx);
            break;
        case 'link':
            settings_template = (data.extra)? 
                                    hs_settings_link_template :
                                    hs_settings_link_template ;
            break;
        default:
            alert('Wrong hotspot type!');
            break;    
    }
    
    return settings_template;
}

function afterAppend(data)
{
    switch (data.type)
    {
        case 'info':
            if  (!data.extra) 
            {    
                scenesSettings.get(current_scene_id).hotspots.get(parseInt(data.idx)).extra = {
                    infotitle: '',
                    infotext: ''
                };                
            }    
            
            $('.'+data.name).next().children('label.title').children('input').change(function(){
                scenesSettings.get(current_scene_id).hotspots.get(parseInt(data.idx)).extra.infotitle = $(this).val();
            });
            $('.'+data.name).next().children('label.text').children('textarea').change(function(){
                scenesSettings.get(current_scene_id).hotspots.get(parseInt(data.idx)).extra.infotext = $(this).val();
            });
            break;
        case 'media':
            if (!(data.extra))    
            {
                enableFileUploadingOnHotspot('input_file_'+data.idx, data.idx);
            }
            else 
            {
                $('.'+data.name).next().children('.media-container').children('input').change(function(){
                    scenesSettings.get(current_scene_id).hotspots.get(parseInt(data.idx)).extra.tooltip = $(this).val();
                });  
            }
            break;
        case 'link':
            scene_id_selected = (data.extra)? data.extra.linkedscene.split('_')[1]:0;
            loadDropdownOnHotspotLink('.'+data.name, data.idx, scene_id_selected);
            break;
        default:
            alert('Wrong hotspot type!');
            break;    
    }    
}

function addHotspotToCollection(data)
{
    var settings_template = beforeAppend(data);   

    $('.hs-collection').prepend(
                        hs_template.replace(/%TYPE%/g, data.type).
                                    replace(/%OBJECT_ID%/g, data.name).
                                    replace(/%TITLE%/g, 'ID: '+data.name).
                                    replace(/%SETTINGS%/g, settings_template)
    );    
       
    afterAppend(data);
}

function removeHotspotFromCollection(identifier)
{ 
    var arr      = identifier.split('_');
    var toRemove = arr[1];
    
    scenesSettings.get(current_scene_id).hotspots.remove(parseInt(toRemove));
  
    $('.hs-collection .'+identifier).next().remove();
    $('.hs-collection .'+identifier).remove();
}

function addHotspotFromXML(data)
{ 
    var _ath   =  data.ath; 
    var _atv   =  data.atv; 
    var _name  =  data.name;
    var _zoom  = 'true';
    var _scale = '0.7';     
    var _type  =  data.type;    
    var _url   =  base_path+'hspot-'+_type+'.png';

    addHotspotToKrpano({
        ath: _ath,
        atv: _atv,
        name: _name,
        zoom: _zoom,
        url: _url,
        scale: _scale,
        type: _type
    });

    addHotspotToCollection(data);
}

function newHotspot(_type)
{ 
    var _idx = ++scenesSettings.get(current_scene_id).last_hotspot_index;

    var _ath   =  krpano.get('view.hlookat')-Math.floor(Math.random() * 45); 
    var _atv   =  krpano.get('view.vlookat')-Math.floor(Math.random() * 25); 
    var _name  =  _type+'_' + scenesSettings.get(current_scene_id).last_hotspot_index;
    var _zoom  = 'true';
    var _scale = '0.7';    
    var _url   = base_path+'hspot-'+_type+'.png';
    
    var hs = {   
        idx: _idx,
        ath: _ath,
        atv: _atv,
        name: _name,
        zoom: _zoom,        
        scale: _scale,
        type: _type,
        extra: null
    };    
    
    scenesSettings.get(current_scene_id).hotspots.put(parseInt(_idx), hs);
    
    addHotspotToKrpano({
        ath: _ath,
        atv: _atv,
        name: _name,
        zoom: _zoom,
        url: _url,
        scale: _scale,
        type: _type
    });

    var api = $('.hs-collection').data('jsp');    
    api.destroy();

    addHotspotToCollection(hs);

    executeAccordion();
    captureRemoveButtons();
    
    $('.hs-collection').jScrollPane();

    selectHotspot(_name);
}

function addHotspotToKrpano(data)
{ 
    krpano.call('addhotspot('+data.name+');');
    krpano.call('set(hotspot['+data.name+'].url,'+data.url+');');

    krpano.call('set(hotspot['+data.name+'].ath,'+data.ath+');');
    krpano.call('set(hotspot['+data.name+'].atv,'+data.atv+');');

    krpano.call('set(hotspot['+data.name+'].scale,'+data.scale+');');
    krpano.call('set(hotspot['+data.name+'].zoom,'+data.zoom+');');
    
    krpano.call('set(hotspot['+data.name+'].ondown, draghotspot() );');
    krpano.call('set(hotspot['+data.name+'].onup, selecthotspot('+data.name+') );');
}

function removeHotspotFromScene(identifier)
{
    krpano.call('removehotspot('+identifier+');');
    
    removeHotspotFromCollection(identifier);
}

function selectHotspot(selected)
{
    selected = (selected instanceof jQuery) ? selected : $('.'+selected);
    selected = selected.next();
    
    if(!selected.hasClass('active'))
    {   
        allPanels = $('.hs-collection div.settings');
        allPanels.removeClass('active').slideUp('fast');

        selected.addClass('active').slideDown('fast', function() {
            // Animation complete.        
            var api = $('.hs-collection').data('jsp');    
            api.scrollToElement(selected.prev(), true, 'swing');
        });
    }
}

function updatePosition(identifier, ath, atv)
{   
    //var scene = scenesSettings.get(current_scene_id);   
    var arr      = identifier.split('_');
    var toUpdate = arr[1];
    
    scenesSettings.get(current_scene_id).hotspots.get(parseInt(toUpdate)).ath = parseFloat(ath);
    scenesSettings.get(current_scene_id).hotspots.get(parseInt(toUpdate)).atv = parseFloat(atv);
           
}

function captureRemoveButtons()
{
    $('.hs-collection div.hs a.hs-remove').unbind('click');
    
    $('.hs-collection div.hs a.hs-remove').bind('click', function() {
        removeHotspotFromScene($(this).parent().attr('data-id'));
    });
}

function executeAccordion()
{
    var allPanels = $('.hs-collection > div.settings').hide();
    
    $('.hs-collection > div.hs').unbind('click');
    
    $('.hs-collection > div.hs').bind('click', function() 
    {
        $this = $(this);
        $target =  $this.next();        
        
        if(!$target.hasClass('active')) 
        {    
            selectHotspot($this);                         
        }
        else
        {            
            allPanels.removeClass('active').slideUp('fast');
        }    
        
        
        return false;
    });

}

function loadDropdownOnHotspotLink(selector, hotspot_index, scene_id_selected)
{     
    if (!scenes_retrieved)
    {        
       // gTour_id = 1; // hardcoded
        
        $.get(  "php-stubs/scenes.php", 
                "action=getall&user-id=xxxx&tour-id="+gTour_id+"&scene-id="+current_scene_id+'&selected-scene='+scene_id_selected, 
                function( data ) 
                {
                    ddData   = JSON.parse(data); 

                    $(selector).next().children('.dropdown-scenes').ddslick({
                        data:ddData,
                        width:'90%',                
                        selectText: "Links to: Select a scene",
                        imagePosition:"left",
                        onSelected: function(data){
                            scenesSettings.get(current_scene_id).
                                hotspots.get(parseInt(hotspot_index)).
                                extra = {};

                            scenesSettings.get(current_scene_id).
                                hotspots.get(parseInt(hotspot_index)).
                                extra.linkedscene = 'scene_'+data.selectedData.value;         
                        }   
                    });

                    /*if (scene_id_selected)
                    {                
                        $(selector).next().children('.dropdown-scenes').ddslick('selectByValue', {value: scene_id_selected });
                    } */   

                    //$target.find('ul.dd-options').jScrollPane();

                   // scenes_retrieved = true;
                });
    }    
   /* else
    {
    
        $(selector).next().children('.dropdown-scenes').ddslick({
            data:ddData,
            width:'90%',
            selectText: "Links to: Select a scene",
            imagePosition:"left",
            onSelected: function(selectedData){
                
            }   
        });

        //$target.find('ul.dd-options').jScrollPane();
    }    */
}




function loadCurrentScene(view, preview, image)
{    
    krpano.call('loadscene('+
            'buffer,'+
                'preview.url='+preview.url+'&'+
                'image.cube.url='+image.cubeurl+'&'+
                'view.hlookat='+view.hlookat+'&'+
                'view.vlookat='+view.vlookat+'&'+
                'view.fovtype='+view.fovtype+'&'+
                'view.fov='+view.fov+'&'+
                'view.maxpixelzoom='+view.maxpixelzoom+'&'+
                'view.fovmin='+view.fovmin+'&'+
                'view.fovmax='+view.fovmax+'&'+
                'view.limitview='+view.limitview+'&'+                    
            ',REMOVESCENES'+
            ',NOBLEND);'); 
}


function exportHotspotsToString()
{
    if (scenesSettings.isEmpty()) return null;
    
    return '&scenes_changes='+JSON.stringify(scenesSettings.getEntries());    
}

function enableFileUploadingOnHotspot(input_name, hotspot_idx)
{
    var sizeBox = document.getElementById('size_'+input_name); // container for file size info
    //progress = document.getElementById('progress'); // the element we're using for a progress bar

    var uploader = new ss.SimpleUpload({
        button: input_name, // file upload button
        url: 'php-stubs/hotspot-image-uploading.php?input_name='+input_name+'&tour_id='+gTour_id, // server side handler
        name: input_name, // upload parameter name        
        progressUrl: 'php-stubs/uploadProgress.php', // enables cross-browser progress support (more info below)
        responseType: 'json',
        allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        maxSize: 204800, // kilobytes
        hoverClass: 'ui-state-hover',
        focusClass: 'ui-state-focus',
        disabledClass: 'ui-state-disabled',
        onSubmit: function(filename, extension) 
        {
            //$('#hs-media-loader').css('display', 'block');
            
            this.setFileSizeBox(sizeBox); // designate this element as file size container
          /*  this.setProgressBar(progress); // designate as progress bar*/
            },         
        onComplete: function(filename, response) 
        {
            //$('#hs-media-loader').css('display', 'none');
            
            if (!response) {
                alert(filename + 'upload failed');
                return false;            
            }
            
            var result = response.result;            
         
            if (result == 'SUCCESS')
            {    
                scenesSettings.get(current_scene_id).hotspots.get(parseInt(hotspot_idx)).extra = {
                    pic     : filename,
                    tooltip : ''
                };

                var $settings = $('#'+input_name).parent().parent();

                $settings.html(
                    hs_settings_media_template.
                            replace(/%FILE_PATH%/g, 'tours/'+gTour_id+'/photos/'+filename).
                            replace(/%FILE_NAME%/g, filename).
                            replace(/%TOOLTIP%/g, '')
                );

                $settings.children('.media-container').children('input').change(function(){
                    scenesSettings.get(current_scene_id).hotspots.get(parseInt(hotspot_idx)).extra.tooltip = $(this).val();
                });    
            }
            else
            {
                var msg = response.msg;
                
                showUploadingResult(msg);
            }    
        }
    });        
}


function JSONClone(obj) {
   return JSON.parse(JSON.stringify(obj))
}

function hideUploadingResult() 
{
    jQuery('.uploading-result').stop().fadeOut(250);
    popUp = false;
    
    return false;
}

function showUploadingResult(message)
{
    var html = '<div class="overlay show-response uploading-result">'
              +'      <div class="pop">'
              +'          <a href="#" class="closed"  onclick="hideUploadingResult();"></a>'
              +'          <h2></h2>'
              +'          <form class="pop-up">'
              +'              <label>           '    
              +'                  <p>'+message+'</p>'
              +'              </label>'
              +'                <div class="content-btn-pop">'
              +'                    <a onclick="hideUploadingResult();" class="grey-button border-radius-4" href="#">OK</a>'
              +'                </div>'
              +'          </form>'
              +'      </div>'
              +'  </div>';
      
    var el = jQuery(html).appendTo('body');
    var $pop = jQuery(el).children('.pop');
  
    //$pop.css('margin-top', -parseInt(jQuery(html).height()/2));
    $pop.css('margin-top', -100);
    
    
    jQuery(el).fadeIn(200);
    
    //return false;
}

//----------------------------------

$(document).ready(function(){  
    hs_bind_all();
});


function hs_unbind_all()
{
    $('#scenelist .pano-item a.edit-hotspots').unbind('click');
    $('.hotspot-tool .save-close').unbind('click');
    $('.hotspot-tool .set-startup-view').unbind('click');    
}

function hs_bind_all()
{
	
    $('#scenelist .pano-item a.edit-hotspots').bind('click', function()
    {
        current_scene_id = $(this).attr('data-id');
        current_thumb    = $(this).attr('data-thumb');        
        current_title    = $(this).parent().children('.loader-item').children('h3.otf-editable').text();

        jQuery('body').css('overflow', 'hidden');

        jQuery('.hotspot-tool img#editor-thumb-scene').attr('src', current_thumb);
        jQuery('.hotspot-tool .thumb-head-set h3').text(current_title);

        jQuery( '.hotspot-tool' ).fadeIn(250, function()
        {             
            embedpano({
                swf:swf_path, 
                id:"krpanoSWFObject", 
                xml: xml_tool_path, 
                html5:html5_param, 
                target:"krpano-container",
                onready:initTool,
                consolelog:false
            });                
        });
    });
    
    
    
    jQuery('.hotspot-tool .save-close').bind('click', function()
    {
        jQuery('.hotspot-tool img#editor-thumb-scene').attr('src', '');
        jQuery('.hotspot-tool .thumb-head-set h3').text('');
        jQuery('body').css('overflow', 'auto');        
        
        var api = $('.hs-collection').data('jsp');    
        api.destroy();
        
        removepano("krpanoSWFObject");
        resetCollection();
        
        hide_popup();
        
        verificar(true);
        
    });
    
    jQuery('.hotspot-tool .set-startup-view').bind('click', function()
    {
        setAsStartupView();        
        $(this).effect('highlight',1000);
    });    
}



/**
 * A Javascript object to encode and/or decode html characters using HTML or Numeric entities that handles double or partial encoding
 * Author: R Reid
 * source: http://www.strictly-software.com/htmlencode
 * Licences: GPL, The MIT License (MIT)
 * Copyright: (c) 2011 Robert Reid - Strictly-Software.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 * 
 * Revision:
 *  2011-07-14, Jacques-Yves Bleau: 
 *       - fixed conversion error with capitalized accentuated characters
 *       + converted arr1 and arr2 to object property to remove redundancy
 *
 * Revision:
 *  2011-11-10, Ce-Yi Hio: 
 *       - fixed conversion error with a number of capitalized entity characters
 *
 * Revision:
 *  2011-11-10, Rob Reid: 
 *		 - changed array format
 *
 * Revision:
 *  2012-09-23, Alex Oss: 
 *		 - replaced string concatonation in numEncode with string builder, push and join for peformance with ammendments by Rob Reid
 */

Encoder = {

	// When encoding do we convert characters into html or numerical entities
	EncodeType : "entity",  // entity OR numerical

	isEmpty : function(val){
		if(val){
			return ((val===null) || val.length==0 || /^\s+$/.test(val));
		}else{
			return true;
		}
	},
	
	// arrays for conversion from HTML Entities to Numerical values
	arr1: ['&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;','&quot;','&amp;','&lt;','&gt;','&OElig;','&oelig;','&Scaron;','&scaron;','&Yuml;','&circ;','&tilde;','&ensp;','&emsp;','&thinsp;','&zwnj;','&zwj;','&lrm;','&rlm;','&ndash;','&mdash;','&lsquo;','&rsquo;','&sbquo;','&ldquo;','&rdquo;','&bdquo;','&dagger;','&Dagger;','&permil;','&lsaquo;','&rsaquo;','&euro;','&fnof;','&Alpha;','&Beta;','&Gamma;','&Delta;','&Epsilon;','&Zeta;','&Eta;','&Theta;','&Iota;','&Kappa;','&Lambda;','&Mu;','&Nu;','&Xi;','&Omicron;','&Pi;','&Rho;','&Sigma;','&Tau;','&Upsilon;','&Phi;','&Chi;','&Psi;','&Omega;','&alpha;','&beta;','&gamma;','&delta;','&epsilon;','&zeta;','&eta;','&theta;','&iota;','&kappa;','&lambda;','&mu;','&nu;','&xi;','&omicron;','&pi;','&rho;','&sigmaf;','&sigma;','&tau;','&upsilon;','&phi;','&chi;','&psi;','&omega;','&thetasym;','&upsih;','&piv;','&bull;','&hellip;','&prime;','&Prime;','&oline;','&frasl;','&weierp;','&image;','&real;','&trade;','&alefsym;','&larr;','&uarr;','&rarr;','&darr;','&harr;','&crarr;','&lArr;','&uArr;','&rArr;','&dArr;','&hArr;','&forall;','&part;','&exist;','&empty;','&nabla;','&isin;','&notin;','&ni;','&prod;','&sum;','&minus;','&lowast;','&radic;','&prop;','&infin;','&ang;','&and;','&or;','&cap;','&cup;','&int;','&there4;','&sim;','&cong;','&asymp;','&ne;','&equiv;','&le;','&ge;','&sub;','&sup;','&nsub;','&sube;','&supe;','&oplus;','&otimes;','&perp;','&sdot;','&lceil;','&rceil;','&lfloor;','&rfloor;','&lang;','&rang;','&loz;','&spades;','&clubs;','&hearts;','&diams;'],
	arr2: ['&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;','&#34;','&#38;','&#60;','&#62;','&#338;','&#339;','&#352;','&#353;','&#376;','&#710;','&#732;','&#8194;','&#8195;','&#8201;','&#8204;','&#8205;','&#8206;','&#8207;','&#8211;','&#8212;','&#8216;','&#8217;','&#8218;','&#8220;','&#8221;','&#8222;','&#8224;','&#8225;','&#8240;','&#8249;','&#8250;','&#8364;','&#402;','&#913;','&#914;','&#915;','&#916;','&#917;','&#918;','&#919;','&#920;','&#921;','&#922;','&#923;','&#924;','&#925;','&#926;','&#927;','&#928;','&#929;','&#931;','&#932;','&#933;','&#934;','&#935;','&#936;','&#937;','&#945;','&#946;','&#947;','&#948;','&#949;','&#950;','&#951;','&#952;','&#953;','&#954;','&#955;','&#956;','&#957;','&#958;','&#959;','&#960;','&#961;','&#962;','&#963;','&#964;','&#965;','&#966;','&#967;','&#968;','&#969;','&#977;','&#978;','&#982;','&#8226;','&#8230;','&#8242;','&#8243;','&#8254;','&#8260;','&#8472;','&#8465;','&#8476;','&#8482;','&#8501;','&#8592;','&#8593;','&#8594;','&#8595;','&#8596;','&#8629;','&#8656;','&#8657;','&#8658;','&#8659;','&#8660;','&#8704;','&#8706;','&#8707;','&#8709;','&#8711;','&#8712;','&#8713;','&#8715;','&#8719;','&#8721;','&#8722;','&#8727;','&#8730;','&#8733;','&#8734;','&#8736;','&#8743;','&#8744;','&#8745;','&#8746;','&#8747;','&#8756;','&#8764;','&#8773;','&#8776;','&#8800;','&#8801;','&#8804;','&#8805;','&#8834;','&#8835;','&#8836;','&#8838;','&#8839;','&#8853;','&#8855;','&#8869;','&#8901;','&#8968;','&#8969;','&#8970;','&#8971;','&#9001;','&#9002;','&#9674;','&#9824;','&#9827;','&#9829;','&#9830;'],
		
	// Convert HTML entities into numerical entities
	HTML2Numerical : function(s){
		return this.swapArrayVals(s,this.arr1,this.arr2);
	},	

	// Convert Numerical entities into HTML entities
	NumericalToHTML : function(s){
		return this.swapArrayVals(s,this.arr2,this.arr1);
	},


	// Numerically encodes all unicode characters
	numEncode : function(s){ 
		if(this.isEmpty(s)) return ""; 

		var a = [],
			l = s.length; 
		
		for (var i=0;i<l;i++){ 
			var c = s.charAt(i); 
			if (c < " " || c > "~"){ 
				a.push("&#"); 
				a.push(c.charCodeAt()); //numeric value of code point 
				a.push(";"); 
			}else{ 
				a.push(c); 
			} 
		} 
		
		return a.join(""); 	
	}, 
	
	// HTML Decode numerical and HTML entities back to original values
	htmlDecode : function(s){

		var c,m,d = s;
		
		if(this.isEmpty(d)) return "";

		// convert HTML entites back to numerical entites first
		d = this.HTML2Numerical(d);
		
		// look for numerical entities &#34;
		arr=d.match(/&#[0-9]{1,5};/g);
		
		// if no matches found in string then skip
		if(arr!=null){
			for(var x=0;x<arr.length;x++){
				m = arr[x];
				c = m.substring(2,m.length-1); //get numeric part which is refernce to unicode character
				// if its a valid number we can decode
				if(c >= -32768 && c <= 65535){
					// decode every single match within string
					d = d.replace(m, String.fromCharCode(c));
				}else{
					d = d.replace(m, ""); //invalid so replace with nada
				}
			}			
		}

		return d;
	},		

	// encode an input string into either numerical or HTML entities
	htmlEncode : function(s,dbl){
			
		if(this.isEmpty(s)) return "";

		// do we allow double encoding? E.g will &amp; be turned into &amp;amp;
		dbl = dbl || false; //default to prevent double encoding
		
		// if allowing double encoding we do ampersands first
		if(dbl){
			if(this.EncodeType=="numerical"){
				s = s.replace(/&/g, "&#38;");
			}else{
				s = s.replace(/&/g, "&amp;");
			}
		}

		// convert the xss chars to numerical entities ' " < >
		s = this.XSSEncode(s,false);
		
		if(this.EncodeType=="numerical" || !dbl){
			// Now call function that will convert any HTML entities to numerical codes
			s = this.HTML2Numerical(s);
		}

		// Now encode all chars above 127 e.g unicode
		s = this.numEncode(s);

		// now we know anything that needs to be encoded has been converted to numerical entities we
		// can encode any ampersands & that are not part of encoded entities
		// to handle the fact that I need to do a negative check and handle multiple ampersands &&&
		// I am going to use a placeholder

		// if we don't want double encoded entities we ignore the & in existing entities
		if(!dbl){
			s = s.replace(/&#/g,"##AMPHASH##");
		
			if(this.EncodeType=="numerical"){
				s = s.replace(/&/g, "&#38;");
			}else{
				s = s.replace(/&/g, "&amp;");
			}

			s = s.replace(/##AMPHASH##/g,"&#");
		}
		
		// replace any malformed entities
		s = s.replace(/&#\d*([^\d;]|$)/g, "$1");

		if(!dbl){
			// safety check to correct any double encoded &amp;
			s = this.correctEncoding(s);
		}

		// now do we need to convert our numerical encoded string into entities
		if(this.EncodeType=="entity"){
			s = this.NumericalToHTML(s);
		}

		return s;					
	},

	// Encodes the basic 4 characters used to malform HTML in XSS hacks
	XSSEncode : function(s,en){
		if(!this.isEmpty(s)){
			en = en || true;
			// do we convert to numerical or html entity?
			if(en){
				s = s.replace(/\'/g,"&#39;"); //no HTML equivalent as &apos is not cross browser supported
				s = s.replace(/\"/g,"&quot;");
				s = s.replace(/</g,"&lt;");
				s = s.replace(/>/g,"&gt;");
			}else{
				s = s.replace(/\'/g,"&#39;"); //no HTML equivalent as &apos is not cross browser supported
				s = s.replace(/\"/g,"&#34;");
				s = s.replace(/</g,"&#60;");
				s = s.replace(/>/g,"&#62;");
			}
			return s;
		}else{
			return "";
		}
	},

	// returns true if a string contains html or numerical encoded entities
	hasEncoded : function(s){
		if(/&#[0-9]{1,5};/g.test(s)){
			return true;
		}else if(/&[A-Z]{2,6};/gi.test(s)){
			return true;
		}else{
			return false;
		}
	},

	// will remove any unicode characters
	stripUnicode : function(s){
		return s.replace(/[^\x20-\x7E]/g,"");
		
	},

	// corrects any double encoded &amp; entities e.g &amp;amp;
	correctEncoding : function(s){
		return s.replace(/(&amp;)(amp;)+/,"$1");
	},


	// Function to loop through an array swaping each item with the value from another array e.g swap HTML entities with Numericals
	swapArrayVals : function(s,arr1,arr2){
		if(this.isEmpty(s)) return "";
		var re;
		if(arr1 && arr2){
			//ShowDebug("in swapArrayVals arr1.length = " + arr1.length + " arr2.length = " + arr2.length)
			// array lengths must match
			if(arr1.length == arr2.length){
				for(var x=0,i=arr1.length;x<i;x++){
					re = new RegExp(arr1[x], 'g');
					s = s.replace(re,arr2[x]); //swap arr1 item with matching item from arr2	
				}
			}
		}
		return s;
	},

	inArray : function( item, arr ) {
		for ( var i = 0, x = arr.length; i < x; i++ ){
			if ( arr[i] === item ){
				return i;
			}
		}
		return -1;
	}

}