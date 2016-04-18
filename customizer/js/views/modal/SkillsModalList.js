define([
	'jquery',
	'underscore',
	'backbone',
	'jqueryui',
	'views/modal/Modal',
	'models/main/ModalModel',
	'views/modal/AlertView',
	'text!templates/modal/skilllist.html',
	'views/sidebar/SkinCustomizerItem',
	'mCustomScrollbar',
	'x2js',
   'helpers/ManageData',
   'helpers/ManageTour',

], function($, _, Backbone, jqueryui, Modal, ModalModel, AlertView, skilllist, SkinCustomizerItem, mCustomScrollbar,x2js,ManageData,ManageTour){

	var SkillsModalList = Modal.extend({
		
		initialize: function () {
		_.bindAll(this);        
		 _.extend(this.events, Modal.prototype.events);
		},
		events:{

				 },
		
		renderExtend:function(){

			var este = this;


			
			var myid = this.myid;
			var compiledTemplate = _.template(skilllist);
			$("#"+myid+" .inner-modal").html(compiledTemplate);

			
			this.verticalCent();
			this.fullfillSkillList();
			

			$("#"+this.myid+" header .fa-close").unbind("click")

			$("#"+this.myid+" header .fa-close").click(function(){
				$(this).parents(".modal").fadeOut(function(){

					este.undelegateEvents();
					$(this).parents(".overlay").remove();

				});
			})

			$("#"+this.myid+" header h2").text("Skills")
			$("#"+this.myid).parents(".overlay").css("background-color","rgba(0,0,0,0)")

		},

		fullfillSkillList :function(){
			var myid = this.myid;
			var este = this;
			var user_level = $(".main-header .user").data("level");
			switch(user_level){
					case "FREE":
					user_level = 0;
					break;
					case "ADVANCED":
					user_level = 1;
					break;
					case "PRO":
					user_level = 2
					break;
				}

			$.ajax({
				url:"data/json.php?t=s&d=1&id_tour="+location.hash.split("/")[1],
				dataType:"json",
				success:function(data){

					$("#"+myid+" .loading").hide();
					_.each(data,function(elem,ind){
						var title = "<h3>"+elem.title+"</h3>";
						var descrip = "<p>"+elem.description+"</p>";
						elem.label = elem.title+" "+elem.description;
						switch(elem.level){
							case "FREE":
							elem.nivel = 0;
							break;
							case "ADVANCED":
							elem.nivel = 1;
							break;
							case "PRO":
							elem.nivel = 2
							break;
						}
						var $bt
						if(elem.blocked_description == "added"){
							$bt =$('<span class="added-skill">Added</span>')
						}else{
							
							if(user_level<elem.nivel){
								$bt = $('<a href="" id="add-item-'+elem.id+'" class="upgrade-skill">Upgrade</a>').data("skill",elem);	
							}else{
								$bt = $('<a href="" id="add-item-'+elem.id+'" class="add-skill"><i class="fa fa-plus"></i>Add</a>').data("skill",elem);
							}
							$bt.data("blocked",elem.blocked);
							$bt.data("no_delete_if_free",elem.no_delete_if_free);
							$bt.data("blocked_description",elem.blocked_description);
							$bt.data("customizable",elem.customizable);
							$bt.data("allow_customize",elem.allow_customize);

						}
						
						var $li = $('<li id="item'+elem.id+'">'+title+descrip+'</li>');
						$li.append($bt);
						if(elem.level == "FREE"){
							$("#"+myid+" .free-skills ul").append($li);   
						}else if(elem.level == "PRO"){
							$("#"+myid+" .pro-skills ul").append($li); 
						}else if(elem.level == "ADVANCED"){
							$("#"+myid+" .advanced-skills ul").append($li); 
						}else{
							delete $li;
						}                  
					})

					$("#skill-list-search").autocomplete({
							source: data,
							appendTo:"#skill-search-results",

							focus: function( event, ui ) {
								$( "#skill-list-search" ).val( ui.item.title );
								return false;
								}, 
							select:function(event,ui){
								var myTitle = ui.item.title;

								$("#skill-list-search").val(myTitle);
								$("#"+myid+" .skill-list li").hide()
								$("#"+myid+" .inner-modal h2").show();
								$("#"+myid+" .skill-list li#item"+ui.item.id).show();
								return false;
							},
							search:function(event,ui){
								$("#"+myid+" .skill-list li").hide();
								$("#"+myid+" .inner-modal h2").hide();
							},
							change: function(event,ui){
								if(!ui.item){
									$( "#skill-list-search" ).val('');
									$("#"+myid+" .skill-list li").show()
									$("#"+myid+" .inner-modal h2").show();
								}                               
							}
						}).data("ui-autocomplete")._renderItem = function(ul,item){

								return $( "<li></li>" )
								.data( "item.autocomplete", item )
								.append( "<dl><dt>" + item.title + "</dt><dd>" + item.description + "</dd></dl>" )
								.appendTo( ul ); 

						};      

					este.events = este.events || {};
					 var addSkill = 'click #' + myid + ' .add-skill';
					 var clearSearch = 'click #' + myid + ' .clear-btn';
					 var upgradeSkill = 'click #' + myid + ' .upgrade-skill';
					este.events[addSkill] = 'addSkilltoCustomizer';
					este.events[clearSearch] = 'clearSearch';
					este.events[upgradeSkill] = 'upgradeClick';
					este.delegateEvents(); 

					$(".modal .skills").mCustomScrollbar({
						theme:"minimal-dark",
						scrollInertia:300
					});


				},

				error:function(jqXHR){
				}
			})
		},

		addSkilltoCustomizer : function(evt) {
			evt.preventDefault();
			var este = this;
			var myid = this.myid;
			var name = $(evt.target).prop("tagName")
			if(name == "I"){
			var skill = $(evt.target).parent("a").data("skill");
			var $elem = $(evt.target).parent("a");
			}else{
			var skill = $(evt.target).data("skill");
			var $elem = $(evt.target);
			}
			if($elem.data("blocked") == "1"){
				var mensj = $elem.data("blocked_description");
				este.showMsg(mensj);
				return;
			}
			
			$elem.text("adding...")
			$.ajax({
				url:"data/xml.php?t=skills&c=1&id="+skill.id,
				 dataType: "html",
				 success:function(data){
					var SkillItemModel = Backbone.Model.extend({});
					var x2js = new X2JS({attributePrefix:"_"});
					var tourSkill =  x2js.xml_str2json( data );
					//tourSkill.skill._no_delete_if_free = $elem.data("no_delete_if_free");
					if(skill.id=="4"){
					
						$.ajax({
							url:"data/json.php?t=dg",
							dataType:"json",
							async:false, 
							success:function(data){
								console.log(tourSkill)	
								tourSkill.skill.plugin._url = data.url;
								}
							})

					}
					
					skillItemModel = new SkillItemModel({tourSkill:tourSkill.skill,no_delete_if_free:$elem.data("no_delete_if_free"),customizable:$elem.data("customizable"),allow_customize:$elem.data("allow_customize")});

					var skinCustomizerItem = new SkinCustomizerItem({model:skillItemModel});
					skinCustomizerItem.render();
					
					var reloadEverything = function(){
						var manageTour = new ManageTour();
						var resetskilllist = function(){
							este.fullfillSkillList();
						}
						manageTour.reloadTour(resetskilllist)
					}
					
					var manageData = new ManageData();
					manageData.pushSkill(tourSkill,reloadEverything)
					$("#"+myid+" .skill-list").html("");
					$("#"+myid+" .loading").show("");

				 }
			})


		},

		clearSearch: function (ev) {
			$(ev.target).siblings('input').val('');
			$("#"+this.myid+" .skill-list li").show()
			$("#"+this.myid+" .inner-modal h2").show();
		},

		showMsg: function(msg){
			var modalModel = new ModalModel({msg:msg})
			var alertView = new AlertView({model:modalModel});
			alertView.render("alert",alertView.renderExtend);
		},

		upgradeClick:function(evt){
			evt.preventDefault();
			var mensj = "This is a Skill for advanced users and you have Free account."
			this.showMsg(mensj);
		}

	})      
	
	return SkillsModalList;
	
});
