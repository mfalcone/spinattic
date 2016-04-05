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
        '<input  onfocus="if(this.value == \'Title\')this.value=\'\';" '+
                'onblur="if(this.value == \'\') this.value=\'Title\';" '+
                'value="Title" '+                
                'name="hs-title" '+
                'class="info-tour border-radius-4 form-user hs-title">'+ 
    '</label><br clear="all">'+ 
    '<label class="text">'+                         
        '<textarea onfocus="if(this.value == \'Type your text here...\')this.value=\'\';" '+
                  'onblur="if(this.value == \'\') this.value=\'Type your text here...\';" '+                  
                  'name="hs-text" '+
                  'class="info-tour border-radius-4 form-user hs-text">Type your text here...</textarea>'+ 
    '</label><br clear="all">';

var hs_settings_info_template = 
    '<label class="title">'+             
        '<input  onfocus="if(this.value == \'Title\')this.value=\'\';" '+
                'onblur="if(this.value == \'\') this.value=\'Title\';" '+
                'value="%TITLE%" '+                
                'name="hs-title" '+
                'class="info-tour border-radius-4 form-user hs-title">'+ 
    '</label><br clear="all">'+ 
    '<label class="text">'+                         
        '<textarea onfocus="if(this.value == \'Type your text here...\')this.value=\'\';" '+
                  'onblur="if(this.value == \'\') this.value=\'Type your text here...\';" '+                  
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
        '<input type="text" value="%TOOLTIP%" style="margin-left:0;" class="info-tour border-radius-4 form-user tooltip" name="tooltip" onblur="if(this.value == \'\') this.value=\'Type your tooltip here...\';" onfocus="if(this.value == \'Type your tooltip here...\')this.value=\'\';"/>'+
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
                                                infotitle: $hs.attr('infotitle'),
                                                infotext: $hs.attr('infotext')
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
                    infotitle: 'Title',
                    infotext: 'Type your text here...'
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
                    tooltip : 'Type your tooltip here...'
                };

                var $settings = $('#'+input_name).parent().parent();

                $settings.html(
                    hs_settings_media_template.
                            replace(/%FILE_PATH%/g, 'tours/'+gTour_id+'/photos/'+filename).
                            replace(/%FILE_NAME%/g, filename).
                            replace(/%TOOLTIP%/g, 'Type your tooltip here...')
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
    $('#drop-zone .pano-item a.edit-hotspots').unbind('click');
    $('.hotspot-tool .save-close').unbind('click');
    $('.hotspot-tool .set-startup-view').unbind('click');    
}

function hs_bind_all()
{
    $('#drop-zone .pano-item a.edit-hotspots').bind('click', function()
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
