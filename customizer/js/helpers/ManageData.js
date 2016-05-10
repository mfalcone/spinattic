define([
	'jquery',
	'underscore',
	'backbone',
   'helpers/ManageTour',
   'helpers/HelpFunctions',
], function($, _, Backbone,ManageTour,HelpFunctions){

	var ManageData =  function(){

			this.saveSceneOnTour = function(scenename,nodename,newVal,parent){

		
					_.each(tourData.krpano.scene,function(elem){
					
					if(elem._name == scenename){
						if(!parent){
						elem[nodename] = newVal;
						}else{
						elem[parent][nodename] = newVal;
						}
						$("#tour").data("scene",elem);
						$("#sceneMenu").find("#"+scenename).data("scene",elem);
					}

				})

				this.saveServer();      
			}

			this.SaveNewSceneOrder = function(callback){

				var scenes = []
				_.each($("#sceneMenu li"),function(el,i){
					if($(el).data("scene")){
						var scene = $(el).data("scene");
						if($(el).data("hotspots")){
							var hotspots = $(el).data("hotspots");
							scene.hotspots = hotspots;
						}
						scenes.push(scene);
					}
				})
				tourData.krpano.scene = scenes;
				this.saveServer(callback);
			}

			

			this.deleteSceneFromServer = function(scenes,callB){
					var este = this;
					$("#publishController").trigger("savingtour");
					
					var scenesDel = scenes.join('|');
					var jsonstr = JSON.stringify(tourData)
					var accion ="del_scene";
					var mydata = "json="+jsonstr+"&id="+scenesDel+"&action="+accion+"&d=1"
				
				$.ajax({
					url:'php/updater.php',
					type:'POST',
					data:mydata,
					success:function(res){
						res = JSON.parse(res);
						if(res.state!="saved"){
							location.href= location.protocol+"//"+location.host;
						}
						$("#sceneMenu .ready-to-del").fadeOut(function(){
							$(this).remove();
							if($("#sceneMenu .ready-to-del").size() == 0){
								var scenestoAdd = []
								_.each($("#sceneMenu li"),function(el,i){
									if($(el).data("scene")){
										var myscene = $(el).data("scene");
										if($(el).data("hotspots")){
											var hotspots = $(el).data("hotspots");
											myscene.hotspots = hotspots;
										}
										scenestoAdd.push(myscene);
									}
								})
								tourData.krpano.scene = scenestoAdd;
								callB();
							}
						})
						if(res){
							var res = JSON.parse(res);
							var fecha = res.date;
						}else{
							fecha = "";
						}
						$("#publishController").trigger("savedtour",[fecha])

					},
					error:function(xhr, ajaxOptions, thrownError){
						console.log(xhr)
					}
				})	
			}

			this.pushHotspot = function(sceneName,hotspot){

				_.each(tourData.krpano.scene,function(elem){
				if(elem._name == sceneName){
					if(!elem.hotspot){
						elem.hotspot = [];
						elem.hotspot.push(hotspot);
						$("#tour").data("scene",elem);
					}else{
						elem.hotspot.push(hotspot);
						$("#tour").data("scene",elem);
					}
					$("#sceneMenu #"+elem._name).data("hotspots",elem.hotspot)
					$("#sceneMenu #"+elem._name).data("scene",elem)
				}
				
			})
				this.saveServer();
			},

			this.changeDataInHotSpot = function(sceneName,hotspot){
				_.each(tourData.krpano.scene,function(elem){
						if(elem._name == sceneName){

								_.each(elem.hotspot,function(hs,ind){
									if(hs._name == hotspot._name){
											elem.hotspot[ind] = hotspot;
										}
								})
						$("#sceneMenu #"+elem._name).data("hotspots",elem.hotspot)
						}
					})
				this.saveServer();
			},

			this.removeHotSpot = function(sceneName,hotspotname){
				_.each(tourData.krpano.scene,function(elem){
						if(elem._name == sceneName){

								_.each(elem.hotspot,function(hs,ind){
									if(hs._name == hotspotname){
											elem.hotspot[ind] = null;
											delete elem.hotspot[ind];
											elem.hotspot.splice(ind,1)
										}
								})
							$("#sceneMenu #"+elem._name).data("hotspots",elem.hotspot)
					
							if(elem.hotspot[0] === undefined){
									delete elem.hotspot;
									$("#sceneMenu #"+elem._name).removeData("hotspots")
							}
						}
					})
				this.saveServer();
			},

			this.saveSettings = function(e){
				if($(e.target).attr("type") == "checkbox"){
					if($(e.target).is(":checked")){
						valueToInsert = "true";
					}else{
						valueToInsert ="false";
					}
				}else if($(e.target).prop("tagName") == "LI"){
					valueToInsert = $(e.target).data("value")
				}else{
					valueToInsert = $(e.target).val()
				}
				tourData.krpano[$(e.target).data("obj")][$(e.target).data("bind")]=valueToInsert;
				this.saveServer();
			},

			this.saveTourData = function(elem,val){
				tourData.krpano.datatour[elem] = val;
				this.saveServer();
			}

			this.pushSkill = function(skill,callback){
				myskill = skill;
				if(tourData.krpano.skill){
					tourData.krpano.skill.push(myskill)
				}else{
					tourData.krpano.skill = [];
					tourData.krpano.skill.push(myskill);					
				}
				this.saveServer(callback);
			}

			this.editSkill = function(json,callback){
				_.each(tourData.krpano.skill,function(skill,ind){
					if(skill._kind == json._kind){
							tourData.krpano.skill[ind] = json;
						}
				})
				this.saveServer(callback);
			}

			this.removeSkill = function(kind,callback){

				_.each(tourData.krpano.skill,function(skill,ind){
						if(skill._kind == kind){
							tourData.krpano.skill[ind] = null;
							delete tourData.krpano.skill[ind];
							tourData.krpano.skill.splice(ind,1)
						}
				})
				this.saveServer(callback);	
			}

			this.pushStyle = function(json){
				
				var equal = false;
				_.each(tourData.krpano.style,function(elem,ind){
					if(elem._name == json._name){
						tourData.krpano.style[ind] = json;
						equal = true;
					}
				})
				if(!equal){
				tourData.krpano.style.push(json);
				}
			}

			this.removeStyle = function(name){

				indexToremove = [];
				_.each(tourData.krpano.style,function(elem,ind){

					var elemname = elem._name;
					var elemray = elemname.split("_");
					
					if(elemray[1] == name){
						indexToremove.push(ind);
					}
				})

				var arr = $.grep(tourData.krpano.style, function(n, i) {
				    return $.inArray(i, indexToremove) ==-1;
				});
				tourData.krpano.style = arr;

				this.saveServer();	
			}

			this.mapData = function(lat, lng,heading,sceneIndex){
				var shouldChange = false;
				if(sceneIndex == "settings"){
					if(lat!=tourData.krpano.settings._lat||lng!=tourData.krpano.settings._long){
						tourData.krpano.settings._lat = lat;
						tourData.krpano.settings._long = lng;
						tourData.krpano.settings._location_heading = heading;
						shouldChange = true;
					}
				}else{
					if(lat!=tourData.krpano.scene[sceneIndex]._lat || lng!=tourData.krpano.scene[sceneIndex]._lng){
						tourData.krpano.scene[sceneIndex]._lat = lat;
						tourData.krpano.scene[sceneIndex]._lng = lng;
						tourData.krpano.scene[sceneIndex]._heading = heading;
						$("#sceneMenu li:eq("+sceneIndex+")").data("scene")._lat = lat;
						$("#sceneMenu li:eq("+sceneIndex+")").data("scene")._lng = lng;
						$("#sceneMenu li:eq("+sceneIndex+")").data("scene")._heading = heading;
						shouldChange = true;
					}
				}
				if(shouldChange){
					this.saveServer();
				}
			}
			this.resetThumb = function(cbak){
				var id = location.hash.split("/")[1];
				$.ajax({
					url:'php/updater.php',
					type:'POST',
					data:"action=reset_tour_thumb&id="+id,
					success:function(res){
						res = JSON.parse(res);
						if(res.state!="saved"){
							location.href= location.protocol+"//"+location.host;
						}
						tourData.krpano.datatour.tour_thumb_path = res.path;

						cbak();
						/*$("#live-tour-img-uploader img").attr("src",res.path);
						$("#reset-live-thumb").hide();
						tourData.krpano.datatour.tour_thumb_path = res.path;
						$("#live-tour-img-uploader").data("imgsrc",res.path);*/
					}
				})
			}
			this.saveServer = function(fun,action){
				$("#publishController").trigger("savingtour");
				var jsonstr = JSON.stringify(tourData)
				jsonstr = encodeURIComponent(jsonstr);
				var id = location.hash.split("/")[1];
				var mydata;
				if(action){
					mydata = "json="+jsonstr+"&id="+action.scene_id+"&action="+action.accion+"&d=1"
				}else{
					mydata = "json="+jsonstr+"&id="+id
				}

				$.ajax({
					url:'php/updater.php',
					type:'POST',
					data:mydata,
					success:function(res){
						var res = JSON.parse(res);
						if(res.state!="saved"){
							location.href= location.protocol+"//"+location.host;
						}

						if(fun){
							fun()
						}
						$("#publishController").trigger("savedtour",[res.date])
						tourData.krpano.datatour.date_updated = res.date; 
					},
					error:function(xhr, ajaxOptions, thrownError){
						console.log(xhr)
					}
				})
			}

			this.saveLive = function(live,callb){
				var jsonstr = JSON.stringify(tourData)
				jsonstr = encodeURIComponent(jsonstr);

				var id = location.hash.split("/")[1];
				var toLive;
				if(live == "live"){
					toLive = "1";
				}else{
					toLive = "-1"
				}
				var mydata = "json="+jsonstr+"&id="+id+"&tolive="+toLive;
				$.ajax({
					url:'php/updater.php',
					type:'POST',
					data:mydata,
					success:function(res){
						var res = JSON.parse(res);
						if(res.state!="saved"){
							location.href= location.protocol+"//"+location.host;
						}
						if(toLive == "1"){
							tourData.krpano.datatour.date_published = res.date;
						}else{
							tourData.krpano.datatour.date_published = "";
						}
						if(callb){
							callb()
						}
					},
					error:function(xhr, ajaxOptions, thrownError){
						console.log(xhr)
					}
				})
			}
}

	return ManageData;
	
});
