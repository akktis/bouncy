
<link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css")?>'/>
<script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
<style>
	.select2-container--default .select2-selection--single {border-radius: 0px !important}
	.select2-container .select2-selection--single {height: 35px}
</style>

<style>
	.container {
	    margin-top: 10px;
	}
	.nav-tabs > li {
	    position:relative;
	}
	.nav-tabs > li > a {
	    display:inline-block;
	}
	.nav-tabs > li > span {
	    display:none;
	    cursor:pointer;
	    position:absolute;
	    right: 6px;
	    top: 8px;
	    color: red;
	}
	.nav-tabs > li:hover > span {
	    display: inline-block;
	}
</style>
<script src='<?php echo asset("vendor/sceeditor/js/jquery.sceditor.min.js")?>'></script>
<link rel="stylesheet" type="text/css" href="<?php echo asset("vendor/sceeditor/css/default.min.css")?>" />

<textarea name="json" style="display:none"></textarea>

<div class="form-group header-group-0 " id="form-group-name" style="">
	<label class="control-label col-sm-2">Name <span class="text-danger" title="This field is required">*</span></label>

	<div class="col-sm-10">
	<input type="text" title="Name" required="" class="form-control" name="name" id="name" value="<?php echo $row->name; ?>">
								
	<div class="text-danger"></div>
	<p class="help-block"></p>

	</div>
</div>

<div class="form-group header-group-0 " id="form-group-js_url" style="">
	<label class="control-label col-sm-2">Js Url </label>

	<div class="col-sm-10">
	<input type="text" title="Js Url" readonly="" maxlength="255" class="form-control" name="js_url" id="js_url" value="<?php echo $row->js_url; ?>">
								
	<div class="text-danger"></div>
	<p class="help-block"></p>

	</div>
</div>

<div class="form-group header-group-0 " id="form-group-company_id" style="">
	<label class="control-label col-sm-2">Company <span class="text-danger" title="This field is required">*</span></label>

	<div class="col-sm-10">
	<select style="width:100%" class="form-control select2-hidden-accessible" id="company_id" required="" name="company_id" tabindex="-1" aria-hidden="true" data-value="<?php echo $row->company_id; ?>">
		<option value="1" selected="">Akktis</option>
	</select>
	<div class="text-danger"></div>
	<p class="help-block"></p>

	</div>
</div>


<ul class="nav nav-tabs nav-rule" role="tablist">
    <li><a href="#" class="add-tab">+ Add Rule</a>
    </li>
</ul>
<div class="tab-content">
    	
    </div>
</div>




<script>
	var data = <?php echo $row->json; ?>;


	$('#company_id').select2({						  							  
		placeholder: {
			id: '-1', 
			text: '** Please select a Company'
		},
		allowClear: true,
		ajax: {								  	
			url: '<?php echo CRUDBooster::mainpath("find-data"); ?>',								    
			delay: 250,								   								    
			data: function (params) {
				var query = {
					q: params.term,
					format: "",
					table1: "bouncer.company",
					column1: "name",
					table2: "",
					column2: "",
					table3: "",
					column3: "",
					where: ""
				}
				return query;
			},
			processResults: function (data) {
				return {
					results: data.items
				};
			}								    								    
		},
		escapeMarkup: function (markup) { return markup; },	    
		minimumInputLength:1,

		initSelection: function(element, callback) {
			var id = $(element).val()?$(element).val():<?php echo $row->company_id; ?>;
			if(id!=='') {
				$.ajax('<?php echo CRUDBooster::mainpath("find-data"); ?>', {
					data: {
						id: id, 
						format: "",
						table1: "bouncer.company",
						column1: "name",
						table2: "",
						column2: "",
						table3: "",
						column3: ""
					},
					dataType: "json"
				}).done(function(data) {							                	
					callback(data.items[0]);	
					$('#company_id').html("<option value='"+data.items[0].id+"' selected >"+data.items[0].text+"</option>");			                	
				});
			}
		}							      
	});


	$("form").on('submit', function() {
		getAllData();
		//return false;
	});


	function getAllData() {
		var allTab = [];
		var tabs = $(".box-body > .tab-content > .tab-pane");
		for(var k = 0, h = tabs.length; k<h; k++) {
			var tab = {};
			var fields = $(tabs[k]).find(":input, table");
			for(var i = 0, l = fields.length; i<l; i++) {
				var f = $(fields[i]);
				if((name = f.attr("json-name")) != undefined) {
					var names = name.split('-');
					var c = tab;
					for(var j = 0, n = names.length; j<n; j++) {
						value = {};
						if(names.length-1 == j) {
							if(f.is('table')) {
								value = [];
								var tr = f.find('tr');
								for(var y = 0, v = tr.length; y<v; y++) {
									var inputs = $(tr[y]).find(":input");
									if(inputs.length > 0) {
										var obj = {};
										for(var o = 0, x = inputs.length; o<x; o++) {
											var b = $(inputs[o]);
											var n1 = b.attr('json-name-list');
											var v1 = '';
											if(n1 != undefined) {
												if(b.is(':checkbox')) {
													v1 = b.is(':checked');
												} else {
													v1 = b.val();
												}
												obj[n1] = v1;
											}
										}
										value.push(obj);
									}
								}
							} else {
								if(f.is(':checkbox')) {
									value = f.is(':checked');
								} else if(f.is('textarea') && f.hasClass('sceeditor')) {
									value = f.sceditor('instance').getBody().html();
								} else {
									value = f.val();
								}
							}
						} 
						if(!c.hasOwnProperty(names[j])) {
							c = c[names[j]] = value;
						} else {
							c = c[names[j]];
						}
					}
				}
			}
			allTab.push(tab);
		}
		console.log(allTab);
		$('[name=json]').val(JSON.stringify(allTab));

	}

	function load(data) {
		console.log(data);
		for(var i = 0, l = data.length; i<l; i++) {
			var tab = addTab('.nav-rule .add-tab');
			fillRuleTabs("#"+tab);
			$("[href=#"+tab+"]").tab('show');
			fillData(data[i], $("#"+tab));
		}
	}

	load(data);

	function fillData(data, el, parent) {
		if(parent == undefined) parent='';
		for(var key in data) {
			var low = data[key];
			if($.isPlainObject(low)) {
				for(var nd in low) {
					if($.isPlainObject(low[nd])) {
						fillData(low[nd], el, parent+key+'-'+nd+'-');
					} else {
						setValue(el, parent+key+'-'+nd, low[nd]);
					}
				}
			} else {
				setValue(el, parent+key, low);
			}
		}
		//
	}

	function setValue(el, name, value) {
		var field = el.find('[json-name='+name+']');
		if(field.length == 0) {
			field = el.find('[json-name-list='+name+']');
		}

		if(field.is(':checkbox')) {
			field.prop("checked", value);
		} else if (field.is('textarea') && field.hasClass('sceeditor')) {
			field.sceditor('instance').getBody().html(value);
		} else if($.isArray(value)) {
			for(var i = 0, l = value.length; i<l; i++) {
				addRow(field, name, value[i]);
			}
		} else {
			field.val(value);
		}
	}

	function tabMngr(selector) {
		let sel = $(selector);
		sel.on("click", "a", function (e) {
			e.preventDefault();
			if (!$(this).hasClass('add-tab')) {
				//$(this).parents('ul').next('.tab-content').find('.tab-pane').removeClass('active');
				//$(this.hash).addClass('active');
				sel.trigger('showtab', $(this).tab('show'));
			}
		})
		.on("click", "span", function () {
			var anchor = $(this).siblings('a');
			var hash = anchor[0].hash;
			$(hash).remove();
			$(this).parent().remove();
			sel.find("li").children('a').first().click();
			sel.trigger('removetab', this);
		});

		sel.find('.add-tab').click(function (e) {
			e.preventDefault();
			addTab(this);
		});
		return sel;
	}

	function guid() {
		function s4() {
			return Math.floor((1 + Math.random()) * 0x10000)
			.toString(16)
			.substring(1);
		}
		return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
	}

	function addTab(el) {
		var id = guid();
		var tabId = 'tab_' + id;
		var ul = $(el).parents('ul');
		$(el).closest('li').before('<li><a href="#tab_' + id + '">Rule #'+$(el).parents('ul').find('li').length+'</a> <span> x </span></li>');
		ul.next('.tab-content').append('<div class="tab-pane" id="' + tabId + '"></div>');
		ul.find('li:last').prev().find("a").trigger('click');
		ul.trigger('addtab', el);
		return tabId;
	}

	function removeRow() {
		var el = $(this);
		if(el.text().trim().toLowerCase() == "all") {
			clearRows(el.parent().prev("table"));
		} else {
			var table = el.parents("table");
			el.parents("tr").remove();
			afterReOrderRows(table);
		}
	}

	function moveRow() {
		var el = $(this);
		if(el.hasClass('json-editor-btn-movedown')) {
			downRow.call(el);
		} else if(el.hasClass('json-editor-btn-moveup')) {
			upRow.call(el);
		}
	}

	function upRow() {
		var el = $(this);
		var tr = el.parents('tr');
		if(tr.length > 0 && tr[0].rowIndex > 0) {
			var cIndex = tr[0].rowIndex;
			var table = tr.parents('table');
			table.find('tr').eq(cIndex-2).after(tr.clone());
			tr.remove(); 
			afterReOrderRows(table);
			applyEventOnTable(table);
		}
	}

	function downRow() {
		var el = $(this);
		var tr = el.parents('tr');
		if(tr.length > 0 && tr[0].rowIndex > 0) {
			var cIndex = tr[0].rowIndex;
			var table = tr.parents('table');
			var trs = table.find('tr');
			if(table.length < (cIndex+1)) {
				trs.eq(cIndex+1).after(tr.clone());
				tr.remove(); 
				afterReOrderRows(table);
				applyEventOnTable(table);
			}
		}
	}

	function afterReOrderRows(table) {
		var trs = table.find('tr');
		for(var i = 1, l = trs.length; i<l; i++) {
			var tr = $(trs[i]);
			if(i == 1) {
				tr.find('td:last .json-editor-btn-moveup').hide();
				tr.find('td:last .json-editor-btn-movedown').show();
			} else if(i == (l-1)) {
				tr.find('td:last .json-editor-btn-moveup').show();
				tr.find('td:last .json-editor-btn-movedown').hide();
			} else {
				tr.find('td:last .json-editor-btn-moveup, td:last .json-editor-btn-movedown').show();
			}
		}
	}

	function applyEventOnTable(table) {
		table.find('.json-editor-btn-delete').on('click', removeRow);
    	table.find('.json-editor-btn-movedown, .json-editor-btn-moveup').on('click', moveRow);
	}

	function addRowFromBtn() {
		var el = $(this);
		addRow(el.parent().prev('table'), el.attr('data-type'));
	}

	function addRow(table, type, value) {
		html = [];
		switch(type) {
			case 'actions-widget-configs':
			case 'widget-configs':
				html.push("<td>");
	    			html.push('<h4>Data</h4>');
	    			html.push('<div class="well well-sm">');
			   			html.push('<label>CountryCode</label>');
			   			html.push('<input type="text" placeholder="FR, EN ect..." class="form-control" json-name-list="countryCode">')
			   			html.push('<label>Query</label>');
			   			html.push('<input type="text" placeholder="cy:catergory, me:merchant, free text" class="form-control" json-name-list="query">')
			   			html.push('<label>Number</label>');
			   			html.push('<input type="text" placeholder="Number of result would you like to display" class="form-control" json-name-list="number">')
			   			html.push('<label>OutOf</label>');
			   			html.push('<input type="text" placeholder="search on how many products" class="form-control" json-name-list="outof">');
			   			html.push('<label>Sort By</label>');
			   			html.push('<input type="text" placeholder="Sort by field" class="form-control" json-name-list="sortBy">');
			   			html.push('<label>Sort Direction</label>');
			   			html.push('<select class="form-control" json-name-list="sortDir"><option value="desc">DESC</option><option value="asc">ASC</option></select>');
			   			html.push('<label>Custom Args</label>');
			   			html.push('<input type="text" placeholder="Custom Arguments" class="form-control" json-name-list="customArgs">');
			   			html.push('<label>ApiKey</label>');
			   			html.push('<input type="text" class="form-control" json-name-list="apikey">')
			   			html.push('<label><input json-name-list="discountOnly" type="checkbox"> Discount Only</label><br><br>');
			   		html.push("</div>");


		   			html.push('<h4>Display</h4>');
		   			html.push('<div class="well well-sm">');
			   			html.push('<label>Size</label><br>');
			   			html.push('<select class="form-control" json-name-list="size">');
			   				html.push('<option value="300x250">300x250</option>');
			   				html.push('<option value="336x280">336x280</option>');
			   				html.push('<option value="728x90">728x90</option>');
			   				html.push('<option value="300x600">300x600</option>');
			   				html.push('<option value="320x100">320x100</option>');
			   				html.push('<option value="160x600">160x600</option>');
			   				html.push('<option value="120x600">120x600</option>');
			   				html.push('<option value="1000x200">1000x200</option>');
			   				html.push('<option value="700x340">700x340</option>');
			   				html.push('<option value="770x772">770x772</option>');
			   			html.push('</select><br>');
			   			html.push('<label><input json-name-list="displayDiscount" type="checkbox"> Display Discount</label><br>');
			   			html.push('<label><input json-name-list="displayTitle" type="checkbox"> Display title</label><br>');
			   			html.push('<label><input json-name-list="displayPrice" type="checkbox"> Display price</label><br>');
			   			html.push('<label><input json-name-list="displayPriceOld" type="checkbox"> Display Old Price</label><br>');
			   			html.push('<label><input json-name-list="displayButton" type="checkbox"> Display button</label><br>');
			   			html.push('<label><input json-name-list="displayPhoto" type="checkbox"> Display photo</label><br>');
			   			html.push('<label><input json-name-list="displayMerchandLogo" type="checkbox"> Display Merchand logo</label><br>');
			   			html.push('<label>Merchand logo placement</label>');
			   			html.push('<select class="form-control" json-name-list="merchandLogoPlacement">');
			   				html.push('<option value="top">TOP</option>');
			   				html.push('<option value="bottom">BOTTOM</option>');
			   				html.push('<option value="left">LEFT</option>');
			   				html.push('<option value="right">RIGHT</option>');
			   			html.push('</select>');
			   			html.push('<label>Button Text</label>');
			   			html.push('<input class="form-control" placeholder="Leave blank to display the price" json-name-list="buttonText">');
			   			html.push('<label>Where to display</label>');
			   			html.push('<input class="form-control" placeholder="id or class where you want to display this widget like #thisisMyid .ThisIsMyClass" json-name-list="whereToDisplay">');
			   			html.push('<label>Preset Style</label><br>');
			   			html.push('<select class="select2" json-name-list="presetStryle">');
			   				html.push('<option value="monetize_1" data-img="<?php echo asset("img/monetize_1.png")?>">Monetize #1</option>');
			   				html.push('<option value="monetize_2" data-img="<?php echo asset("img/monetize_2.png")?>">Monetize #2</option>');
			   			html.push('</select><br>');
			   			html.push('<label>Custom Style</label>');
			   			html.push('<textarea class="form-control" placeholder="__CLASSNAME___img {}" json-name-list="style"></textarea>');
			   		html.push("</div>");
			   	html.push("</td>");
			   	html.push('<td>');
					html.push('<div class="btn-group" style="margin: 0px; padding: 0px;">');
						html.push('<button type="button" title="Delete" class="btn btn-default json-editor-btn-delete  delete" data-i="1"><i class="fa fa-times"></i> </button>');
						html.push('<button type="button" title="Move up" class="btn btn-default json-editor-btn-moveup  moveup" data-i="1"><i class="fa fa-arrow-up"></i> </button>');
						html.push('<button type="button" title="Move down" class="btn btn-default json-editor-btn-movedown  movedown" data-i="1" style="display: none;"><i class="fa fa-arrow-down"></i> </button>');
					html.push('</div>');
				html.push('</td>');
			break;
			case 'restriction-referrer-referrers':
			case 'restriction-referrer-type':
				html.push('<td class=" compact">');
					html.push('<div class=" bouncer-non-margin-leftRight" style="margin-bottom: 0px;">');
						html.push('<select class="form-control" json-name-list="method">');
							html.push('<option value="content">content</option>');
							html.push('<option value="strict">strict</option>');
							html.push('<option value="startWith">startWith</option>');
							html.push('<option value="endWith">endWith</option>');
							html.push('<option value="dontContent">dontContent</option>');
						html.push('</select>');
					html.push('</div>');
				html.push('</td>');
				html.push('<td class=" compact">');
					html.push('<div class=" bouncer-non-margin-leftRight" style="margin-bottom: 0px;">');
						html.push('<input type="text" class="form-control" json-name-list="referrer">');
					html.push('</div>');
				html.push('</td>');
				html.push('<td>');
					html.push('<div class="btn-group" style="margin: 0px; padding: 0px;">');
						html.push('<button type="button" title="Delete" class="btn btn-default json-editor-btn-delete  delete" data-i="1"><i class="fa fa-times"></i> </button>');
						html.push('<button type="button" title="Move up" class="btn btn-default json-editor-btn-moveup  moveup" data-i="1"><i class="fa fa-arrow-up"></i> </button>');
						html.push('<button type="button" title="Move down" class="btn btn-default json-editor-btn-movedown  movedown" data-i="1" style="display: none;"><i class="fa fa-arrow-down"></i> </button>');
					html.push('</div>');
				html.push('</td>');
			break;
			case 'restriction-url-tags':
			case 'restriction-referrer-url-tags':
				html.push('<td class=" compact">');
					html.push('<div class=" bouncer-non-margin-leftRight" style="margin-bottom: 0px;">');
						html.push("<input type='text' class='form-control' json-name-list='tag'>");
					html.push('</div>');
				html.push('</td>');
				html.push('<td class=" compact">');
					html.push('<div class=" bouncer-non-margin-leftRight" style="margin-bottom: 0px;">');
						html.push('<input type="text" class="form-control" json-name-list="value">');
					html.push('</div>');
				html.push('</td>');
				html.push('<td class=" compact">');
					html.push('<select class="form-control" json-name-list="method">');
						html.push('<option value="content">content</option>');
						html.push('<option value="strict">strict</option>');
						html.push('<option value="startWith">startWith</option>');
						html.push('<option value="endWith">endWith</option>');
						html.push('<option value="dontContent">dontContent</option>');
					html.push('</select>');
				html.push('</td>')
				html.push('<td>');
					html.push('<div class="btn-group" style="margin: 0px; padding: 0px;">');
						html.push('<button type="button" title="Delete" class="btn btn-default json-editor-btn-delete  delete" data-i="1"><i class="fa fa-times"></i> </button>');
						html.push('<button type="button" title="Move up" class="btn btn-default json-editor-btn-moveup  moveup" data-i="1"><i class="fa fa-arrow-up"></i> </button>');
						html.push('<button type="button" title="Move down" class="btn btn-default json-editor-btn-movedown  movedown" data-i="1" style="display: none;"><i class="fa fa-arrow-down"></i> </button>');
					html.push('</div>');
				html.push('</td>');
			break;
			case 'restriction-dom-doms':
			case 'restriction-referrer-javascript-dom':
				html.push('<tr>');
    				html.push('<td class=" compact">');
    					html.push('<div class=" bouncer-non-margin-leftRight" style="margin-bottom: 0px;">');
    						html.push("<input type='text' class='form-control' json-name-list='dom'>");
    					html.push('</div>');
    				html.push('</td>');
    				html.push('<td class=" compact">');
    					html.push('<select class="form-control" json-name-list="contentFrom"><option>html</option><option>text</option></select>');
    				html.push('</td>')
    				html.push('<td class=" compact">');
    					html.push('<div class=" bouncer-non-margin-leftRight" style="margin-bottom: 0px;">');
    						html.push('<input type="text" class="form-control" json-name-list="value">');
    					html.push('</div>');
    				html.push('</td>');
    				html.push('<td class=" compact">');
    					html.push('<select class="form-control" json-name-list="method">');
    						html.push('<option value="content">content</option>');
    						html.push('<option value="strict">strict</option>');
    						html.push('<option value="startWith">startWith</option>');
    						html.push('<option value="endWith">endWith</option>');
    						html.push('<option value="dontContent">dontContent</option>');
    					html.push('</select>');
    				html.push('</td>')
    				html.push('<td style="padding: 0;padding-top: 8px;">');
    					html.push('<div class="btn-group" style="margin: 0px; padding: 0px;">');
    						html.push('<button type="button" title="Delete" class="btn btn-default json-editor-btn-delete  delete" data-i="0"><i class="fa fa-times"></i></button>');
    						html.push('<button type="button" title="Move down" class="btn btn-default json-editor-btn-movedown  movedown" data-i="0"><i class="fa fa-arrow-down"></i> </button>');
    					html.push('</div>');
    				html.push('</td>');
    			html.push('</tr>');
			break;
		}
		table.append("<tr>"+html.join('')+"</tr>");
		afterReOrderRows(table);
		applyEventOnTable(table);
		fillData(value, table.find('tr:last'));


		table.find("[json-name-list=presetStryle]").select2({
			width:'100%',
			escapeMarkup: function (markup) { return markup; },
			templateResult: function(repo) {
//				if (repo.loading) return repo.text;

				var markup = "<div class='select2-result-repository__meta'>" +
				"<div class='select2-result-repository__title'>" + repo.text + "</div></div><div class='select2-result-repository clearfix'>" +
				"<div class='select2-result-repository__avatar'><img style='width: 50%;' src='" + $(repo.element).attr("data-img") + "' /></div>" +
				"</div>";

				return markup;
			},
		}).on("change", function (e) {
			$(this).parent().find("[json-name-list=style]").val(getWidgetStyle($(this).val()));
		});
	}

	function getWidgetStyle(name) {
		switch(name) {
			case "monetize_1":
				return ""+
					".__CLASSNAME___link {"+"\n"+
					"	border: 1px solid #eee;"+"\n"+
					"	float:left;"+"\n"+
					"	margin:2px;"+"\n"+
					"	width: calc(33% - 4px);"+"\n"+
					"	text-decoration: none;"+"\n"+
					"	box-sizing: border-box;"+"\n"+
					"	font-family: \"Open sans\", arial, Helvetica;"+"\n"+
					"	position:relative;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___img {"+"\n"+
					"	height:100px;"+"\n"+
					"	width:auto;"+"\n"+
					"	background-repeat:no-repeat;"+"\n"+
					"	background-position: center;"+"\n"+
					"	background-size: contain;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					""+"\n"+
					".__CLASSNAME___button {"+"\n"+
					"	text-align:center;"+"\n"+
					"	padding:5px 10px 5px 10px;"+"\n"+
					"	max-width:75px;"+"\n"+
					"	background-color:white;"+"\n"+
					"	color:#999;"+"\n"+
					"	border:1px solid black;"+"\n"+
					"	margin:auto;"+"\n"+
					"	font-weight: bold;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___button:hover {"+"\n"+
					"	background-color:black;"+"\n"+
					"	color:white;"+"\n"+
					"	border:1px solid white;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___title {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:12px;"+"\n"+
					"   text-align: center;"+"\n"+
					"	margin: 10px 2px 10px 2px;"+"\n"+
    				"	text-overflow: ellipsis;"+"\n"+
    				"	height: 50px;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___merchant_logo_without_me {"+"\n"+
					"	position:absolute;"+"\n"+
					"	background-repeat: no-repeat;"+"\n"+
					"	width: 100px;"+"\n"+
					"	height: 100px;"+"\n"+
					"	top:0;"+"\n"+
					"	left:0;"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___price {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:20px;"+"\n"+
					"	text-align:center;"+"\n"+
					"	margin-bottom: 5px;"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___currency_eur::after {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:20px;"+"\n"+
					"	content:'\\20AC';"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___currency_usd::before {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:20px;"+"\n"+
					"	content:'\\0024';"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___priceOld {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:11px;"+"\n"+
					"	text-align:center;"+"\n"+
					"	text-decoration: line-through;"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___discount {"+"\n"+
					"	color:red;"+"\n"+
					"	font-size:14px;"+"\n"+
					"	text-align:center;"+"\n" +
					"}"+
					""+"\n"+
					".__CLASSNAME___discount::before {"+"\n"+
					"	color:red;"+"\n"+
					"	content:'-';"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___discount::after {"+"\n"+
					"	content:'%';"+"\n"+
					"	color:red;"+"\n"+
					"}";
			break
			case "monetize_2":
				return ""+
					".__CLASSNAME___link {"+"\n"+
					"	border: 1px solid #eee;"+"\n"+
					"	float:left;"+"\n"+
					"	margin:2px;"+"\n"+
					"	width: calc(50% - 4px);"+"\n"+
					"	text-decoration: none;"+"\n"+
					"	box-sizing: border-box;"+"\n"+
					"	font-family: \"Open sans\", arial, Helvetica;"+"\n"+
					"	position:relative;"+"\n"+
					"}"+"\n"+
					""+"\n"+

					".__CLASSNAME___link:hover >  .__CLASSNAME___wrap_button{"+"\n"+
					"	display:block;"+"\n"+
					"	position: absolute;"+"\n"+
					"   bottom: 2px;"+"\n"+
					"   left: 0;"+"\n"+
					"   right: 0;"+"\n"+
					"}"+"\n"+
					""+"\n"+
		
					".__CLASSNAME___link:hover > .__CLASSNAME___wrap_img {"+"\n"+
					"	opacity: 0.2;"+"\n"+
					"}"+"\n"+

					""+"\n"+
					".__CLASSNAME___link:hover > .__CLASSNAME___wrap_title {"+"\n"+
					"	display:block;"+"\n"+
					"}"+"\n"+
					""+"\n"+

					".__CLASSNAME___link:hover > .__CLASSNAME___wrap_price {"+"\n"+
					"	display:block;"+"\n"+
					"	position: absolute;"+"\n"+
					"   top: 2px;"+"\n"+
					"   left: 0;"+"\n"+
					"   right: 0;"+"\n"+
					"}"+"\n"+
					""+"\n"+

					".__CLASSNAME___link:hover > .__CLASSNAME___wrap_priceOld {"+"\n"+
					"	display:block;"+"\n"+
					"}"+"\n"+
					""+"\n"+

					".__CLASSNAME___link:hover > .__CLASSNAME___wrap_discount {"+"\n"+
					"	display:block;"+"\n"+
					"}"+"\n"+
					""+"\n"+

					".__CLASSNAME___wrap_button {"+"\n"+
					"	display:none;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___wrap_title {"+"\n"+
					"	display:none;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___wrap_price {"+"\n"+
					"	display:none;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___wrap_priceOld {"+"\n"+
					"	display:none;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___wrap_discount {"+"\n"+
					"	display:none;"+"\n"+
					"}"+"\n"+

					""+"\n"+
					".__CLASSNAME___img {"+"\n"+
					"	height:70px;"+"\n"+
					"	width:auto;"+"\n"+
					"	background-repeat:no-repeat;"+"\n"+
					"	background-position: center;"+"\n"+
					"	background-size: contain;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___button {"+"\n"+
					"	text-align:center;"+"\n"+
					"	padding:5px 10px 5px 10px;"+"\n"+
					"	max-width:75px;"+"\n"+
					"	background-color:white;"+"\n"+
					"	color:#999;"+"\n"+
					"	border:1px solid black;"+"\n"+
					"	margin:auto;"+"\n"+
					"	font-weight: bold;"+"\n"+
					"	font-size: 78%;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___button:hover {"+"\n"+
					"	background-color:black;"+"\n"+
					"	color:white;"+"\n"+
					"	border:1px solid white;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___title {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:12px;"+"\n"+
					"   text-align: center;"+"\n"+
					"	margin: 10px 2px 10px 2px;"+"\n"+
    				"	text-overflow: ellipsis;"+"\n"+
    				"	height: 50px;"+"\n"+
					"}"+"\n"+
					""+"\n"+
					".__CLASSNAME___merchant_logo_without_me {"+"\n"+
					"	position:absolute;"+"\n"+
					"	background-repeat: no-repeat;"+"\n"+
					"	width: 100px;"+"\n"+
					"	height: 100px;"+"\n"+
					"	top:0;"+"\n"+
					"	left:0;"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___price {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:100%;"+"\n"+
					"	text-align:center;"+"\n"+
					"	margin-bottom: 5px;"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___currency_eur::after {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:100%;"+"\n"+
					"	content:'\\20AC';"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___currency_usd::before {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:100%;"+"\n"+
					"	content:'\\0024';"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___priceOld {"+"\n"+
					"	color:#999;"+"\n"+
					"	font-size:11px;"+"\n"+
					"	text-align:center;"+"\n"+
					"	text-decoration: line-through;"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___discount {"+"\n"+
					"	color:red;"+"\n"+
					"	font-size:14px;"+"\n"+
					"	text-align:center;"+"\n" +
					"}"+
					""+"\n"+
					".__CLASSNAME___discount::before {"+"\n"+
					"	color:red;"+"\n"+
					"	content:'-';"+"\n"+
					"}"+
					""+"\n"+
					".__CLASSNAME___discount::after {"+"\n"+
					"	content:'%';"+"\n"+
					"	color:red;"+"\n"+
					"}";
		break;
		}
	}

	function clearRows(table) {
		table.find('tr:gt(0)').remove();
	}

	function fillRuleTabs(tab) {
		var tab = $(tab);
		if(tab.html() != '') return;
		var html = [];
		html.push('<div class="col-md-4">');
			html.push('<div class="well well-sm">');
				html.push('<h3>Help</h3><br>dadsada');
			html.push('</div>');
		html.push('</div>');
		html.push('<div class="col-md-8">');
			html.push('<fieldset>');
	    		html.push('<h3>Events</h3><br>');
	    		html.push('<label><input json-name="events-onOutsideWindow" type="checkbox"> When the cursor is outside the window</label><br>');
	    		html.push('<label><input json-name="events-onLoad" type="checkbox"> On load</label><br>');
	    		html.push('<label><input json-name="events-onClickButton" type="checkbox"> On click button</label><br>');
	    	html.push('</fieldset>');
	    html.push('</div>');


	    html.push('<div class="col-md-4">');
	    	html.push('<div class="well well-sm">');
				html.push('<h3>Help</h3><br>dadsada');
			html.push('</div>');
		html.push('</div>');
		html.push('<div class="col-md-8">');
	    	html.push('<fieldset>');
	    		html.push('<h3>Restriction</h3>');
			    html.push('<ul class="nav nav-tabs nav-restriction" role="tablist">');
			        html.push('<li class="active"><a href="#restriction_01-'+tab.attr('id')+'" data-toggle="tab">Based on referrer</a></li>');
			        html.push('<li><a href="#restriction_02-'+tab.attr('id')+'" data-toggle="tab">Based on url tags</a></li>');
			        html.push('<li><a href="#restriction_03-'+tab.attr('id')+'" data-toggle="tab">Based on javascript dom</a></li>');
			        html.push('<li><a href="#restriction_04-'+tab.attr('id')+'" data-toggle="tab">Device and Languages</a></li>');
			        html.push('</li>');
			    html.push('</ul>');
			    html.push('<div class="tab-content">');
			        html.push('<div class="tab-pane active" id="restriction_01-'+tab.attr('id')+'">');
			        	html.push('<label>Referrer type will include or exclude the the referrer below</label>');
			        	html.push('<select class="form-control" json-name="restriction-referrer-referrer_type">');
			        		html.push('<option value="onlyIf">onlyIf</option>');
			        		html.push('<option value="exclude">exclude</option>');
			        	html.push('</select>');

			        	html.push('<div class="well well-sm">');
			        		html.push('<table class="table table-bordered" json-name="restriction-referrer-referrers" style="width: auto; max-width: none;">');
			        			html.push('<tr>');
			        				html.push('<th>Method</th>');
			        				html.push('<th>Referrer url</th>');
			        				html.push('<th> </th>');
			        			html.push('</tr>');
							html.push('</table>');
							html.push('<div class="btn-group">');
								html.push('<button type="button" data-type="restriction-referrer-type" title="Add row" class="btn btn-default json-editor-btn-add "><i class="fa fa-plus"></i> row</button>');
								html.push('<button type="button" title="Delete Last row" class="btn btn-default json-editor-btn-delete " style="display: none;"><i class="fa fa-times"></i> Last row</button>');
								html.push('<button type="button" title="Delete All" class="btn btn-default json-editor-btn-delete "><i class="fa fa-times"></i> All</button>');
							html.push('</div>');
						html.push('</div>');
			        html.push('</div>');
			        html.push('<div class="tab-pane" id="restriction_02-'+tab.attr('id')+'">');
			        	html.push('<label>Tags type : if one of tags or all of tags are considerate as inclusive</label>');
			        	html.push('<select class="form-control" json-name="restriction-url-tags_type">');
			        		html.push('<option value="oneOfThem">oneOfThem</option>');
			        		html.push('<option value="allOfThem">allOfThem</option>');
			        	html.push('</select>');

			        	html.push('<div class="well well-sm">');
			        		html.push('<table class="table table-bordered" style="width: auto; max-width: none;" json-name="restriction-url-tags">');
			        			html.push('<tr>');
			        				html.push('<th>Tag (vca)</th>');
			        				html.push('<th>Value (123)</th>');
			        				html.push('<th>Method</th>');
			        				html.push('<th> </th>');
			        			html.push('</tr>');
							html.push('</table>');
							html.push('<div class="btn-group">');
								html.push('<button type="button" title="Add row" data-type="restriction-referrer-url-tags" class="btn btn-default json-editor-btn-add "><i class="fa fa-plus"></i> row</button>');
								html.push('<button type="button" title="Delete Last row" class="btn btn-default json-editor-btn-delete " style="display: none;"><i class="fa fa-times"></i> Last row</button>');
								html.push('<button type="button" title="Delete All" class="btn btn-default json-editor-btn-delete "><i class="fa fa-times"></i> All</button>');
							html.push('</div>');
						html.push('</div>');
			        html.push('</div>');
			        html.push('<div class="tab-pane" id="restriction_03-'+tab.attr('id')+'">');
			        html.push('<label>Tags type : if one of tags or all of tags are considerate as inclusive</label>');
			        	html.push('<select class="form-control" json-name="restriction-dom-doms_type">');
			        		html.push('<option value="oneOfThem">oneOfThem</option>');
			        		html.push('<option value="allOfThem">allOfThem</option>');
			        	html.push('</select>');

			        	html.push('<div class="well well-sm">');
			        		html.push('<table class="table table-bordered" style="width: auto; max-width: none;" json-name="restriction-dom-doms">');
			        			html.push('<tr>');
			        				html.push('<th>dom</th>');
			        				html.push('<th>contentFrom</th>');
			        				html.push('<th>value</th>');
			        				html.push('<th>method</th>');
			        				html.push('<th> </th>');
			        			html.push('</tr>');
			        			
							html.push('</table>');
							html.push('<div class="btn-group">');
								html.push('<button type="button" title="Add row" data-type="restriction-referrer-javascript-dom" class="btn btn-default json-editor-btn-add "><i class="fa fa-plus"></i> row</button>');
								html.push('<button type="button" title="Delete Last row" class="btn btn-default json-editor-btn-delete " style="display: none;"><i class="fa fa-times"></i> Last row</button>');
								html.push('<button type="button" title="Delete All" class="btn btn-default json-editor-btn-delete "><i class="fa fa-times"></i> All</button>');
							html.push('</div>');
						html.push('</div>');
			        html.push('</div>');
			        html.push('<div class="tab-pane" id="restriction_04-'+tab.attr('id')+'">');
			        	html.push('<label><input type="checkbox" checked json-name="restriction-isMobile"> Only Mobile</label><br>');
	    				html.push('<label><input type="checkbox" checked json-name="restriction-isTablet"> Only Tablet</label><br>');
	    				html.push('<label><input type="checkbox" checked json-name="restriction-isDesktop"> Only Desktop</label><br>');
	    				html.push('<label>Languages (Only for : FR,EN,ES)</label><br>');
	    				html.push('<input type="text" json-name="restriction-languages" class="form-control">');
	    			html.push('</div>')
			    html.push('</div>');
	        html.push('</fieldset>');
	    html.push('</div>');


	    html.push('<div class="col-md-4">');
		html.push('</div>');
		html.push('<div class="col-md-8">');
	        html.push('<fieldset>');
	    		html.push('<h3>Actions</h3><br>');

	    		html.push('<ul class="nav nav-tabs nav-actions" role="tablist">');
			        html.push('<li class="active"><a href="#action_01-'+tab.attr('id')+'" data-toggle="tab">Bounce (add url in history)</a></li>');
			        html.push('<li><a href="#action_02-'+tab.attr('id')+'" data-toggle="tab">Notification</a></li>');
			        html.push('<li><a href="#action_03-'+tab.attr('id')+'" data-toggle="tab">Display popin</a></li>');
			        html.push('<li><a href="#action_04-'+tab.attr('id')+'" data-toggle="tab">Widget</a></li>');
			        html.push('</li>');
			    html.push('</ul>');
			    html.push('<div class="tab-content">');
			    	html.push('<div class="tab-pane active well well-sm"  id="action_01-'+tab.attr('id')+'">');
			    		html.push('<div class="row ">');
							html.push('<div class="col-md-12">');
								html.push('<div class=" checkbox" style="margin-top: 0px;">');
									html.push('<label style="font-weight: normal; font-size: 14px;">');
										html.push('Active<input type="checkbox" json-name="actions-addUrlInHistory-activate" style="display: inline-block; width: auto; position: relative; float: left;">');
									html.push('</label>');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');
						html.push('<div class="row">');
							html.push('<div class="col-md-12">');
								html.push('<div class="bouncer-non-margin-leftRight">');
									html.push('<label class=" control-label bouncer-label">url</label>');
									html.push('<input type="text" class="form-control" json-name="actions-addUrlInHistory-url">');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');
						html.push('<div class="row">');
							html.push('<div class="col-md-12">');
								html.push('<div class=" bouncer-non-margin-leftRight">');
									html.push('<label class=" control-label bouncer-label">title</label>');
									html.push('<input type="text" json-name="actions-addUrlInHistory-title" class="form-control">');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');
			    	html.push('</div>');

			    	html.push('<div class="tab-pane well well-sm" id="action_02-'+tab.attr('id')+'">');
			    		html.push('<div class="row">');
			    			html.push('<div class="col-md-12">');
			    				html.push('<div class=" checkbox" style="margin-top: 0px;">');
			    					html.push('<label style="font-weight: normal; font-size: 14px;">');
			    						html.push('Active<input type="checkbox" style="display: inline-block; width: auto; position: relative; float: left;" json-name="actions-addNotification-activate">');
			    					html.push('</label>');
			    				html.push('</div>');
			    			html.push('</div>');
			    		html.push('</div>');
			    		html.push('<div class="row">');
			    			html.push('<div class="col-md-12">');
			    				html.push('<div class=" bouncer-non-margin-leftRight">');
			    					html.push('<label class=" control-label bouncer-label">Service Worker Url</label>');
			    					html.push('<input type="text" class="form-control" json-name="actions-addNotification-serviceWorker">');
			    				html.push('</div>');
			    			html.push('</div>');
			    		html.push('</div>');
			    	
			    	html.push('</div>');

			    	html.push('<div class="tab-pane well well-sm" id="action_03-'+tab.attr('id')+'">');
			    		html.push('<div class="row">');
			    			html.push('<div class="col-md-12">');
			    				html.push('<div class=" checkbox" style="margin-top: 0px;">');
			    					html.push('<label style="font-weight: normal; font-size: 14px;">');
			    						html.push('Active<input type="checkbox" style="display: inline-block; width: auto; position: relative; float: left;" json-name="actions-displayPopin-activate">');

			    					html.push('</label>');
			    				html.push('</div>');
			    			html.push('</div>');
			    		html.push('</div>');


			    		html.push('<div class="row">');
			    			html.push('<div class="col-md-12">');
			    				html.push('<div class=" bouncer-non-margin-leftRight">');
			    					html.push('<label class=" control-label bouncer-label">Content</label>');
			    					html.push('<textarea json-name="actions-displayPopin-title" class="sceeditor"></textarea>');
			    				html.push('</div>');
			    			html.push('</div>');
			    		html.push('</div>');


			    	html.push('</div>');

			    	html.push('<div class="tab-pane well well-sm" id="action_04-'+tab.attr('id')+'">');


				    	html.push('<div class="row ">');
							html.push('<div class="col-md-12">');
								html.push('<div class=" checkbox" style="margin-top: 0px;">');
									html.push('<label style="font-weight: normal; font-size: 14px;">');
										html.push('Active<input type="checkbox" json-name="actions-widget-activate" style="display: inline-block; width: auto; position: relative; float: left;">');
									html.push('</label>');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');


				    	html.push("<table class='table table-bordered' json-name='actions-widget-configs' style='width: 100%; max-width: none;'>");
				    			html.push("<tr>");
				    				html.push("<td><!--dont remove--></td>");
				    				html.push("<td style='width:100px'><!--dont remove--></td>");
				    			html.push("</tr>");
							html.push("</table>");
							html.push('<div class="btn-group">');
								html.push('<button type="button" data-type="widget-configs" title="Add row" class="btn btn-default json-editor-btn-add "><i class="fa fa-plus"></i> row</button>');
								html.push('<button type="button" title="Delete Last row" class="btn btn-default json-editor-btn-delete " style="display: none;"><i class="fa fa-times"></i> Last row</button>');
								html.push('<button type="button" title="Delete All" class="btn btn-default json-editor-btn-delete "><i class="fa fa-times"></i> All</button>');
					   		html.push('</div>');
					   	html.push("</div>");


			    	html.push('</div>');


	    		html.push('</div>');
	    	html.push('</fieldset>');
	    html.push('</div>');



		html.push('<div class="col-md-4">');
		html.push('</div>');
		html.push('<div class="col-md-8">');	    
	        html.push('<fieldset>');
	    		html.push('<h3 data-toggle="collapse" data-target="#data-targeting-'+tab.attr('id')+'">Data Targeting</h3><br>');
	    		html.push('<div class="collapse" id="data-targeting-'+tab.attr('id')+'">')
		    		html.push('<h4>Product (Automatic mode or Manual with css query)</h4>');
		    		html.push('<div class="well well-sm">');
						html.push('<div class="row">');
							html.push('<div class="col-md-12">');
								html.push('<div class=" bouncer-non-margin-leftRight">');
									html.push('<label class=" control-label bouncer-label">Mode</label>');
									html.push('<select class="form-control" json-name="targeting-product-type">');
										html.push('<option value="auto">auto</option>');
										html.push('<option value="manual">manual</option>');
									html.push('</select>');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');
						html.push('<div class="row">');
							html.push('<div class="col-md-12">');
								html.push('<div class=" bouncer-non-margin-leftRight">');
									html.push('<label class=" control-label bouncer-label">CSS Query</label>');
									html.push('<input type="text" class="form-control" json-name="targeting-product-query">');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');
		   			html.push('</div>');



		   			html.push('<h4>Price (Automatic mode or Manual with css query)</h4>');
		    		html.push('<div class="well well-sm">');
						html.push('<div class="row">');
							html.push('<div class="col-md-12">');
								html.push('<div class=" bouncer-non-margin-leftRight">');
									html.push('<label class=" control-label bouncer-label">Mode</label>');
									html.push('<select class="form-control" json-name="targeting-price-type">');
										html.push('<option value="auto">auto</option>');
										html.push('<option value="manual">manual</option>');
									html.push('</select>');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');
						html.push('<div class="row">');
							html.push('<div class="col-md-12">');
								html.push('<div class=" bouncer-non-margin-leftRight">');
									html.push('<label class=" control-label bouncer-label">CSS Query</label>');
									html.push('<input type="text" class="form-control" json-name="targeting-price-query">');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');
		   			html.push('</div>');

		   			html.push('<h4>Category (Automatic mode or Manual with css query) </h4>');
		    		html.push('<div class="well well-sm">');
						html.push('<div class="row">');
							html.push('<div class="col-md-12">');
								html.push('<div class=" bouncer-non-margin-leftRight">');
									html.push('<label class=" control-label bouncer-label">Mode</label>');
									html.push('<select class="form-control" json-name="targeting-category-type">');
										html.push('<option value="auto">auto</option>');
										html.push('<option value="manual">manual</option>');
									html.push('</select>');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');
						html.push('<div class="row">');
							html.push('<div class="col-md-12">');
								html.push('<div class=" bouncer-non-margin-leftRight">');
									html.push('<label class=" control-label bouncer-label">CSS Query</label>');
									html.push('<input type="text" json-name="targeting-category-query" class="form-control">');
								html.push('</div>');
							html.push('</div>');
						html.push('</div>');
		   			html.push('</div>');
		   		html.push('</div>');
	    	html.push('</fieldset>');
    	html.push('</div>');


    	html.push('<div class="col-md-4">');
		html.push('</div>');
		html.push('<div class="col-md-8">');
	    	html.push('<fieldset>');
	    		html.push('<h3 data-toggle="collapse" data-target="#add-button-'+tab.attr('id')+'">Button</h3><br>');
	    		html.push('<div class="collapse well well-sm" id="add-button-'+tab.attr('id')+'">')
		    		html.push('<label>Title</label>');
		    		html.push('<input type="text" class="form-control" json-name="displayButton-title">');

		    		html.push('<label>Background Color</label>');
		    		html.push('<input type="color" class="form-control" json-name="displayButton-color">');

		    		html.push('<label>Font Color</label>');
		    		html.push('<input type="color" class="form-control" json-name="displayButton-fontColor">');
		    		

		    		html.push('<label>FontAwsome Icon</label>');
		    		html.push('<select class="form-control" json-name="displayButton-icon">');

		    		html.push('</select>');

		    		html.push('<label>Placement</label>');
		    		html.push('<select class="form-control" json-name="displayButton-placement">');
		    			html.push('<option>topRight</option>');
		    			html.push('<option>topLeft</option>');
		    			html.push('<option>bottomRight</option>');
		    			html.push('<option>bottomLeft</option>');
		    		html.push('</select>');

		    		html.push('<label>Css Class</label>');
		    		html.push('<input type="text" json-name="displayButton-class" class="form-control">');
		    	html.push('</div>');
	    	html.push('</fieldset>');
	    html.push('</div>');

	    

	    html.push('<div class="col-md-4">');
		html.push('</div>');
		html.push('<div class="col-md-8">');
	    	html.push('<fieldset>');
	    		html.push('<h3 data-toggle="collapse" data-target="#general-style-'+tab.attr('id')+'">General Style</h3><br>');

	    		html.push('<div class="collapse well well-sm" id="general-style-'+tab.attr('id')+'">');
		    		html.push('<label>Css</label>');
		    		html.push('<textarea class="form-control" json-name="style-value" style="height: 200px;"></textarea>');
		    	html.push('</div>');
	    	html.push('</fieldset>');
	    html.push('</div>');

    	tab.html(html.join(""));

    	tab.find('.sceeditor').sceditor({
			"width": "500px",
			"height": "450px",
          	"resizeWidth": false
		});
    	applyEventOnTable(tab);
    	tab.find('.json-editor-btn-add').on('click', addRowFromBtn);
    }

	tabMngr(".nav-rule").on('showtab', function(e,tab) {
		var cTab = $(tab.hash);
    	fillRuleTabs(cTab);
    	//tabMngr(cTab.find('.nav-restriction'));

	});
</script>