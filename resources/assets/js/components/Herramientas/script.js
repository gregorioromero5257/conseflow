// Code goes here

var CollapseTemplate = {
	params : {
		templateSelector : '.template',
		parentId : 'accordion'
	},

	init : function(_params) {
		//sobreescibimos los params
		this.params = Utils.extend({}, this.params, _params);
	},

	//cargamos el JSON inicial
	load : function(json) {
		// borramos el panel principal
		$("#" + this.params.parentId).empty();
		var _this = this;
		$.each(json.residus, function(i, val) {
			_this.draw(val.name, val.childs, undefined, val.url);
		});
	},

	draw : function(name, childs, panel, url) {
		console.log(childs);
		//pillamos un numero que se aumenta para los ids
		var numId = Global.getNextNumCollapseElement();
		//pillamos el template
		var template = $(this.params.templateSelector);
		//lo cloneamos
		var $newPanel = template.clone();
		var dataParentId = this.params.parentId;
		//si el panel no esta definido el padre es el container definido en los params
		if (panel !== undefined) {
			dataParentId = $(panel).find(".panel-collapse").attr("id");
		}

		//añadimos una id al panel
		$($newPanel).attr("id", "panel" + numId);
		//pintamos elemento header
		this.drawHeader(name,  $newPanel, numId, dataParentId);
		//manejamos los hijos
		this.drawChildNodes(childs,  $newPanel, numId, url);

		//si es el primer nivel añadimos al padre sino al panel creado
		if (panel === undefined) {
			$("#" + this.params.parentId).append($newPanel.show());
		} else {
			$(panel).find(".panel-body").append($newPanel);
		}
	},

	drawHeader : function(name,  $newPanel, numId, dataParentId) {
		$newPanel.find(".collapse").removeClass("in");
		$newPanel.find(".accordion-toggle")	.attr("href", "#collapse" + numId).text(name).attr(	"data-parent", dataParentId).attr("id", "link-"+numId);
		$newPanel.find(".panel-collapse").attr("id", "collapse" + numId).addClass("collapse").removeClass("in");
	},

	drawChildNodes : function(childs,  $newPanel, numId, url) {
		if(childs!==undefined && childs!==null)	{
			this.drawChildNodesArray(childs, $newPanel, numId);
		}	else if ((childs===undefined || childs===null)&& url!==undefined)	{
			this.drawChildNodesAjax(url, $newPanel, numId);
		}
	},

	//pinta los hijos apartir de un array
	drawChildNodesArray : function(childs,  $newPanel, numId) {
		var _this = this;
		// cargamos los hijos del array
		$.each(childs, function(i, val) {
			if (val.childs !== null) {
				_this.draw(val.name, val.childs, $newPanel, val.url);
			//si se cargan los hijos por ajax
			} else if (val.childs === null && val.url !== undefined && val.url !== null) {
				$newPanel.find(".panel-body").append("<ul class='list-group' id='list-group-"+numId+"'><li class='list-group-item'>loading</li></ul>");
				$("#" + _this.params.parentId).on('click', 'a#link-'+numId ,function() {
					var jqxhr = Utils.doAjax({}, val.url);
					jqxhr.done(function(dades) {
						var panel = $("#" + _this.params.parentId).find("#collapse"+numId+" > div.panel-body");
						$(panel).empty();
						$.each(dades.residus, function(i, val) {
							if(val.url===undefined || val.url===null)	{
								$(panel).append("<li class='list-group-item'>"+val.name+"</li>");
							}	else	{
								_this.draw(val.name, null, $("#panel"+numId), val.url);
							}
						});
						//una vez cargado en la DOM quitamos el evento para no hacer más llamadas ajax
						$("#" + _this.params.parentId).off('click', 'a#link-'+numId );
					});
				});

			}else {
				$newPanel.find(".panel-body").append("<ul class='list-group'><li class='list-group-item'>" + val.name + "</li></ul>");
			}
		});

	},

	//pinta los hijos apartir de una URL
	//TODO codigo duplicado (drawChildNodesArray)
	drawChildNodesAjax : function(url, $newPanel, numId) {
		var _this = this;
		$newPanel.find(".panel-body").append("<ul class='list-group' id='list-group-"+numId+"'><li class='list-group-item'>loading</li></ul>");
		//añadimos un cklick-event para que se carquen por ajax cuando se haga click en el link
		$("#" + _this.params.parentId).on('click', 'a#link-'+numId ,function() {
			//una vez se ha hecho click se llama a la URL por AJAX
			var jqxhr = Utils.doAjax({}, url);
			//manejamos la promesa
			jqxhr.done(function(dades) {
				var panel = $("#" + _this.params.parentId).find("#collapse"+numId+" > div.panel-body");
				$(panel).empty();
				$.each(dades.residus, function(i, val) {
					if(val.url===undefined || val.url===null)	{
						$(panel).append("<li class='list-group-item'>"+val.name+"</li>");
					}	else	{
						_this.draw(val.name, null, $("#panel"+numId), val.url);
					}
				});
				//una vez cargado en la DOM quitamos el evento para no hacer más llamadas ajax
				$("#" + _this.params.parentId).off('click', 'a#link-'+numId );
			});
		});

	}

};

Global =	{
	countCollapseElements: 0,
	getNextNumCollapseElement : function() {
		return this.countCollapseElements++;
	}
};

Utils = {
	// extender un object con otro, sobrescribe los properties que se llaman
	// igual
	extend : function(dest) {
		var sources = Array.prototype.slice.call(arguments, 1);
		sources.forEach(function(source) {
			Object.keys(source).forEach(function(key) {
				dest[key] = source[key];
			});
		});
		return dest;
	},

	// hacer la llamada ajax con JSON
	doAjax : function(params, _url) {
		return $.ajax({
			url : _url,
			dataType : 'json',
			data : params,
			cache : false,
			error : function(jqXHR, textStatus, errorThrown) {
				alert(textStatus);
			}
		});
	}
};
