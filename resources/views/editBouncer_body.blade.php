
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
<?php echo $row->company_id; ?>
<div class="form-group header-group-0 " id="form-group-company_id" style="">
	<label class="control-label col-sm-2">Company <span class="text-danger" title="This field is required">*</span></label>

	<div class="col-sm-10">
	<select style="width:100%" class="form-control select2-hidden-accessible" id="company_id" required="" name="company_id" tabindex="-1" aria-hidden="true" data-value="<?php echo $row->company_id; ?>">
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
			console.log('dddd', id);
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
	    				html.push('<label>Enable mntzm ');
			   			html.push('<input type="checkbox" json-name-list="mntzmEnabled"></label><br>')
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
			case "restriction-url-strings":
				html.push('<td class=" compact">');
					html.push('<div class=" bouncer-non-margin-leftRight" style="margin-bottom: 0px;">');
						html.push("<input type='text' class='form-control' json-name-list='string'>");
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
			case 'restriction-url-tags':
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
			        html.push('<li><a href="#restriction_03-'+tab.attr('id')+'" data-toggle="tab">Based on url string</a></li>');
			        html.push('<li><a href="#restriction_04-'+tab.attr('id')+'" data-toggle="tab">Based on javascript dom</a></li>');
			        html.push('<li><a href="#restriction_05-'+tab.attr('id')+'" data-toggle="tab">Device and Languages</a></li>');
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
								html.push('<button type="button" title="Add row" data-type="restriction-url-tags" class="btn btn-default json-editor-btn-add "><i class="fa fa-plus"></i> row</button>');
								html.push('<button type="button" title="Delete Last row" class="btn btn-default json-editor-btn-delete " style="display: none;"><i class="fa fa-times"></i> Last row</button>');
								html.push('<button type="button" title="Delete All" class="btn btn-default json-editor-btn-delete "><i class="fa fa-times"></i> All</button>');
							html.push('</div>');
						html.push('</div>');
			        html.push('</div>');

			        html.push('<div class="tab-pane" id="restriction_03-'+tab.attr('id')+'">');
			        	html.push('<label>String type : if one of strings or all of strings are considerate as inclusive</label>');
			        	html.push('<select class="form-control" json-name="restriction-url-strings_type">');
			        		html.push('<option value="oneOfThem">oneOfThem</option>');
			        		html.push('<option value="allOfThem">allOfThem</option>');
			        	html.push('</select>');

			        	html.push('<div class="well well-sm">');
			        		html.push('<table class="table table-bordered" style="width: auto; max-width: none;" json-name="restriction-url-strings">');
			        			html.push('<tr>');
			        				html.push('<th>Value (google.com/my/url)</th>');
			        				html.push('<th>Method</th>');
			        				html.push('<th> </th>');
			        			html.push('</tr>');
							html.push('</table>');
							html.push('<div class="btn-group">');
								html.push('<button type="button" title="Add row" data-type="restriction-url-strings" class="btn btn-default json-editor-btn-add "><i class="fa fa-plus"></i> row</button>');
								html.push('<button type="button" title="Delete Last row" class="btn btn-default json-editor-btn-delete " style="display: none;"><i class="fa fa-times"></i> Last row</button>');
								html.push('<button type="button" title="Delete All" class="btn btn-default json-editor-btn-delete "><i class="fa fa-times"></i> All</button>');
							html.push('</div>');
						html.push('</div>');
			        html.push('</div>');


			        html.push('<div class="tab-pane" id="restriction_04-'+tab.attr('id')+'">');
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
			        html.push('<div class="tab-pane" id="restriction_05-'+tab.attr('id')+'">');
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
		    			html.push('<option value="glass">glass</option>');
						html.push('<option value="music">music</option>');
						html.push('<option value="search">search</option>');
						html.push('<option value="envelope-o">envelope-o</option>');
						html.push('<option value="heart">heart</option>');
						html.push('<option value="star">star</option>');
						html.push('<option value="star-o">star-o</option>');
						html.push('<option value="user">user</option>');
						html.push('<option value="film">film</option>');
						html.push('<option value="th-large">th-large</option>');
						html.push('<option value="th">th</option>');
						html.push('<option value="th-list">th-list</option>');
						html.push('<option value="check">check</option>');
						html.push('<option value="remove">remove</option>');
						html.push('<option value="close">close</option>');
						html.push('<option value="times">times</option>');
						html.push('<option value="search-plus">search-plus</option>');
						html.push('<option value="search-minus">search-minus</option>');
						html.push('<option value="power-off">power-off</option>');
						html.push('<option value="signal">signal</option>');
						html.push('<option value="gear">gear</option>');
						html.push('<option value=<option value="cog">cog</option>>cog</option>');
						html.push('<option value="trash-o">trash-o</option>');
						html.push('<option value="home">home</option>');
						html.push('<option value="file-o">file-o</option>');
						html.push('<option value="clock-o">clock-o</option>');
						html.push('<option value="road">road</option>');
						html.push('<option value="download">download</option>');
						html.push('<option value="arrow-circle-o-down">arrow-circle-o-down</option>');
						html.push('<option value="arrow-circle-o-up">arrow-circle-o-up</option>');
						html.push('<option value="inbox">inbox</option>');
						html.push('<option value="play-circle-o">play-circle-o</option>');
						html.push('<option value="rotate-right">rotate-right</option>');
						html.push('<option value="repeat">repeat</option>');
						html.push('<option value="refresh">refresh</option>');
						html.push('<option value="list-alt">list-alt</option>');
						html.push('<option value="lock">lock</option>');
						html.push('<option value="flag">flag</option>');
						html.push('<option value="headphones">headphones</option>');
						html.push('<option value="volume-off">volume-off</option>');
						html.push('<option value="volume-down">volume-down</option>');
						html.push('<option value="volume-up">volume-up</option>');
						html.push('<option value="qrcode">qrcode</option>');
						html.push('<option value="barcode">barcode</option>');
						html.push('<option value="tag">tag</option>');
						html.push('<option value="tags">tags</option>');
						html.push('<option value="book">book</option>');
						html.push('<option value="bookmark">bookmark</option>');
						html.push('<option value="print">print</option>');
						html.push('<option value="camera">camera</option>');
						html.push('<option value="font">font</option>');
						html.push('<option value="bold">bold</option>');
						html.push('<option value="italic">italic</option>');
						html.push('<option value="text-height">text-height</option>');
						html.push('<option value="text-width">text-width</option>');
						html.push('<option value="align-left">align-left</option>');
						html.push('<option value="align-center">align-center</option>');
						html.push('<option value="align-right">align-right</option>');
						html.push('<option value="align-justify">align-justify</option>');
						html.push('<option value="list">list</option>');
						html.push('<option value="dedent">dedent</option>');
						html.push('<option value="outdent">outdent</option>');
						html.push('<option value="indent">indent</option>');
						html.push('<option value="video-camera">video-camera</option>');
						html.push('<option value="photo">photo</option>');
						html.push('<option value="image">image</option>');
						html.push('<option value="picture-o">picture-o</option>');
						html.push('<option value="pencil">pencil</option>');
						html.push('<option value="map-marker">map-marker</option>');
						html.push('<option value="adjust">adjust</option>');
						html.push('<option value="tint">tint</option>');
						html.push('<option value="edit">edit</option>');
						html.push('<option value="pencil-square-o">pencil-square-o</option>');
						html.push('<option value="share-square-o">share-square-o</option>');
						html.push('<option value="check-square-o">check-square-o</option>');
						html.push('<option value="arrows">arrows</option>');
						html.push('<option value="step-backward">step-backward</option>');
						html.push('<option value="fast-backward">fast-backward</option>');
						html.push('<option value="backward">backward</option>');
						html.push('<option value="play">play</option>');
						html.push('<option value="pause">pause</option>');
						html.push('<option value="stop">stop</option>');
						html.push('<option value="forward">forward</option>');
						html.push('<option value="fast-forward">fast-forward</option>');
						html.push('<option value="step-forward">step-forward</option>');
						html.push('<option value="eject">eject</option>');
						html.push('<option value="chevron-left">chevron-left</option>');
						html.push('<option value="chevron-right">chevron-right</option>');
						html.push('<option value="plus-circle">plus-circle</option>');
						html.push('<option value="minus-circle">minus-circle</option>');
						html.push('<option value="times-circle">times-circle</option>');
						html.push('<option value="check-circle">check-circle</option>');
						html.push('<option value="question-circle">question-circle</option>');
						html.push('<option value="info-circle">info-circle</option>');
						html.push('<option value="crosshairs">crosshairs</option>');
						html.push('<option value="times-circle-o">times-circle-o</option>');
						html.push('<option value="check-circle-o">check-circle-o</option>');
						html.push('<option value="ban">ban</option>');
						html.push('<option value="arrow-left">arrow-left</option>');
						html.push('<option value="arrow-right">arrow-right</option>');
						html.push('<option value="arrow-up">arrow-up</option>');
						html.push('<option value="arrow-down">arrow-down</option>');
						html.push('<option value="mail-forward">mail-forward</option>');
						html.push('<option value="share">share</option>');
						html.push('<option value="expand">expand</option>');
						html.push('<option value="compress">compress</option>');
						html.push('<option value="plus">plus</option>');
						html.push('<option value="minus">minus</option>');
						html.push('<option value="asterisk">asterisk</option>');
						html.push('<option value="exclamation-circle">exclamation-circle</option>');
						html.push('<option value="gift">gift</option>');
						html.push('<option value="leaf">leaf</option>');
						html.push('<option value="fire">fire</option>');
						html.push('<option value="eye">eye</option>');
						html.push('<option value="eye-slash">eye-slash</option>');
						html.push('<option value="warning">warning</option>');
						html.push('<option value="exclamation-triangle">exclamation-triangle</option>');
						html.push('<option value="plane">plane</option>');
						html.push('<option value="calendar">calendar</option>');
						html.push('<option value="random">random</option>');
						html.push('<option value="comment">comment</option>');
						html.push('<option value="magnet">magnet</option>');
						html.push('<option value="chevron-up">chevron-up</option>');
						html.push('<option value="chevron-down">chevron-down</option>');
						html.push('<option value="retweet">retweet</option>');
						html.push('<option value="shopping-cart">shopping-cart</option>');
						html.push('<option value="folder">folder</option>');
						html.push('<option value="folder-open">folder-open</option>');
						html.push('<option value="arrows-v">arrows-v</option>');
						html.push('<option value="arrows-h">arrows-h</option>');
						html.push('<option value="bar-chart-o">bar-chart-o</option>');
						html.push('<option value="bar-chart">bar-chart</option>');
						html.push('<option value="twitter-square">twitter-square</option>');
						html.push('<option value="facebook-square">facebook-square</option>');
						html.push('<option value="camera-retro">camera-retro</option>');
						html.push('<option value="key">key</option>');
						html.push('<option value="gears">gears</option>');
						html.push('<option value="cogs">cogs</option>');
						html.push('<option value="comments">comments</option>');
						html.push('<option value="thumbs-o-up">thumbs-o-up</option>');
						html.push('<option value="thumbs-o-down">thumbs-o-down</option>');
						html.push('<option value="star-half">star-half</option>');
						html.push('<option value="heart-o">heart-o</option>');
						html.push('<option value="sign-out">sign-out</option>');
						html.push('<option value="linkedin-square">linkedin-square</option>');
						html.push('<option value="thumb-tack">thumb-tack</option>');
						html.push('<option value="external-link">external-link</option>');
						html.push('<option value="sign-in">sign-in</option>');
						html.push('<option value="trophy">trophy</option>');
						html.push('<option value="github-square">github-square</option>');
						html.push('<option value="upload">upload</option>');
						html.push('<option value="lemon-o">lemon-o</option>');
						html.push('<option value="phone">phone</option>');
						html.push('<option value="square-o">square-o</option>');
						html.push('<option value="bookmark-o">bookmark-o</option>');
						html.push('<option value="phone-square">phone-square</option>');
						html.push('<option value="twitter">twitter</option>');
						html.push('<option value="facebook-f">facebook-f</option>');
						html.push('<option value="facebook">facebook</option>');
						html.push('<option value="github">github</option>');
						html.push('<option value="unlock">unlock</option>');
						html.push('<option value="credit-card">credit-card</option>');
						html.push('<option value="feed">feed</option>');
						html.push('<option value="rss">rss</option>');
						html.push('<option value="hdd-o">hdd-o</option>');
						html.push('<option value="bullhorn">bullhorn</option>');
						html.push('<option value="bell">bell</option>');
						html.push('<option value="certificate">certificate</option>');
						html.push('<option value="hand-o-right">hand-o-right</option>');
						html.push('<option value="hand-o-left">hand-o-left</option>');
						html.push('<option value="hand-o-up">hand-o-up</option>');
						html.push('<option value="hand-o-down">hand-o-down</option>');
						html.push('<option value="arrow-circle-left">arrow-circle-left</option>');
						html.push('<option value="arrow-circle-right">arrow-circle-right</option>');
						html.push('<option value="arrow-circle-up">arrow-circle-up</option>');
						html.push('<option value="arrow-circle-down">arrow-circle-down</option>');
						html.push('<option value="globe">globe</option>');
						html.push('<option value="wrench">wrench</option>');
						html.push('<option value="tasks">tasks</option>');
						html.push('<option value="filter">filter</option>');
						html.push('<option value="briefcase">briefcase</option>');
						html.push('<option value="arrows-alt">arrows-alt</option>');
						html.push('<option value="group">group</option>');
						html.push('<option value="users">users</option>');
						html.push('<option value="chain">chain</option>');
						html.push('<option value="link">link</option>');
						html.push('<option value="cloud">cloud</option>');
						html.push('<option value="flask">flask</option>');
						html.push('<option value="cut">cut</option>');
						html.push('<option value="scissors">scissors</option>');
						html.push('<option value="copy">copy</option>');
						html.push('<option value="files-o">files-o</option>');
						html.push('<option value="paperclip">paperclip</option>');
						html.push('<option value="save">save</option>');
						html.push('<option value="floppy-o">floppy-o</option>');
						html.push('<option value="square">square</option>');
						html.push('<option value="navicon">navicon</option>');
						html.push('<option value="reorder">reorder</option>');
						html.push('<option value="bars">bars</option>');
						html.push('<option value="list-ul">list-ul</option>');
						html.push('<option value="list-ol">list-ol</option>');
						html.push('<option value="strikethrough">strikethrough</option>');
						html.push('<option value="underline">underline</option>');
						html.push('<option value="table">table</option>');
						html.push('<option value="magic">magic</option>');
						html.push('<option value="truck">truck</option>');
						html.push('<option value="pinterest">pinterest</option>');
						html.push('<option value="pinterest-square">pinterest-square</option>');
						html.push('<option value="google-plus-square">google-plus-square</option>');
						html.push('<option value="google-plus">google-plus</option>');
						html.push('<option value="money">money</option>');
						html.push('<option value="caret-down">caret-down</option>');
						html.push('<option value="caret-up">caret-up</option>');
						html.push('<option value="caret-left">caret-left</option>');
						html.push('<option value="caret-right">caret-right</option>');
						html.push('<option value="columns">columns</option>');
						html.push('<option value="unsorted">unsorted</option>');
						html.push('<option value="sort">sort</option>');
						html.push('<option value="sort-down">sort-down</option>');
						html.push('<option value="sort-desc">sort-desc</option>');
						html.push('<option value="sort-up">sort-up</option>');
						html.push('<option value="sort-asc">sort-asc</option>');
						html.push('<option value="envelope">envelope</option>');
						html.push('<option value="linkedin">linkedin</option>');
						html.push('<option value="rotate-left">rotate-left</option>');
						html.push('<option value="undo">undo</option>');
						html.push('<option value="legal">legal</option>');
						html.push('<option value="gavel">gavel</option>');
						html.push('<option value="dashboard">dashboard</option>');
						html.push('<option value="tachometer">tachometer</option>');
						html.push('<option value="comment-o">comment-o</option>');
						html.push('<option value="comments-o">comments-o</option>');
						html.push('<option value="flash">flash</option>');
						html.push('<option value="bolt">bolt</option>');
						html.push('<option value="sitemap">sitemap</option>');
						html.push('<option value="umbrella">umbrella</option>');
						html.push('<option value="paste">paste</option>');
						html.push('<option value="clipboard">clipboard</option>');
						html.push('<option value="lightbulb-o">lightbulb-o</option>');
						html.push('<option value="exchange">exchange</option>');
						html.push('<option value="cloud-download">cloud-download</option>');
						html.push('<option value="cloud-upload">cloud-upload</option>');
						html.push('<option value="user-md">user-md</option>');
						html.push('<option value="stethoscope">stethoscope</option>');
						html.push('<option value="suitcase">suitcase</option>');
						html.push('<option value="bell-o">bell-o</option>');
						html.push('<option value="coffee">coffee</option>');
						html.push('<option value="cutlery">cutlery</option>');
						html.push('<option value="file-text-o">file-text-o</option>');
						html.push('<option value="building-o">building-o</option>');
						html.push('<option value="hospital-o">hospital-o</option>');
						html.push('<option value="ambulance">ambulance</option>');
						html.push('<option value="medkit">medkit</option>');
						html.push('<option value="fighter-jet">fighter-jet</option>');
						html.push('<option value="beer">beer</option>');
						html.push('<option value="h-square">h-square</option>');
						html.push('<option value="plus-square">plus-square</option>');
						html.push('<option value="angle-double-left">angle-double-left</option>');
						html.push('<option value="angle-double-right">angle-double-right</option>');
						html.push('<option value="angle-double-up">angle-double-up</option>');
						html.push('<option value="angle-double-down">angle-double-down</option>');
						html.push('<option value="angle-left">angle-left</option>');
						html.push('<option value="angle-right">angle-right</option>');
						html.push('<option value="angle-up">angle-up</option>');
						html.push('<option value="angle-down">angle-down</option>');
						html.push('<option value="desktop">desktop</option>');
						html.push('<option value="laptop">laptop</option>');
						html.push('<option value="tablet">tablet</option>');
						html.push('<option value="mobile-phone">mobile-phone</option>');
						html.push('<option value="mobile">mobile</option>');
						html.push('<option value="circle-o">circle-o</option>');
						html.push('<option value="quote-left">quote-left</option>');
						html.push('<option value="quote-right">quote-right</option>');
						html.push('<option value="spinner">spinner</option>');
						html.push('<option value="circle">circle</option>');
						html.push('<option value="mail-reply">mail-reply</option>');
						html.push('<option value="reply">reply</option>');
						html.push('<option value="github-alt">github-alt</option>');
						html.push('<option value="folder-o">folder-o</option>');
						html.push('<option value="folder-open-o">folder-open-o</option>');
						html.push('<option value="smile-o">smile-o</option>');
						html.push('<option value="frown-o">frown-o</option>');
						html.push('<option value="meh-o">meh-o</option>');
						html.push('<option value="gamepad">gamepad</option>');
						html.push('<option value="keyboard-o">keyboard-o</option>');
						html.push('<option value="flag-o">flag-o</option>');
						html.push('<option value="flag-checkered">flag-checkered</option>');
						html.push('<option value="terminal">terminal</option>');
						html.push('<option value="code">code</option>');
						html.push('<option value="mail-reply-all">mail-reply-all</option>');
						html.push('<option value="reply-all">reply-all</option>');
						html.push('<option value="star-half-empty">star-half-empty</option>');
						html.push('<option value="star-half-full">star-half-full</option>');
						html.push('<option value="star-half-o">star-half-o</option>');
						html.push('<option value="location-arrow">location-arrow</option>');
						html.push('<option value="crop">crop</option>');
						html.push('<option value="code-fork">code-fork</option>');
						html.push('<option value="unlink">unlink</option>');
						html.push('<option value="chain-broken">chain-broken</option>');
						html.push('<option value="question">question</option>');
						html.push('<option value="info">info</option>');
						html.push('<option value="exclamation">exclamation</option>');
						html.push('<option value="superscript">superscript</option>');
						html.push('<option value="subscript">subscript</option>');
						html.push('<option value="eraser">eraser</option>');
						html.push('<option value="puzzle-piece">puzzle-piece</option>');
						html.push('<option value="microphone">microphone</option>');
						html.push('<option value="microphone-slash">microphone-slash</option>');
						html.push('<option value="shield">shield</option>');
						html.push('<option value="calendar-o">calendar-o</option>');
						html.push('<option value="fire-extinguisher">fire-extinguisher</option>');
						html.push('<option value="rocket">rocket</option>');
						html.push('<option value="maxcdn">maxcdn</option>');
						html.push('<option value="chevron-circle-left">chevron-circle-left</option>');
						html.push('<option value="chevron-circle-right">chevron-circle-right</option>');
						html.push('<option value="chevron-circle-up">chevron-circle-up</option>');
						html.push('<option value="chevron-circle-down">chevron-circle-down</option>');
						html.push('<option value="html5">html5</option>');
						html.push('<option value="css3">css3</option>');
						html.push('<option value="anchor">anchor</option>');
						html.push('<option value="unlock-alt">unlock-alt</option>');
						html.push('<option value="bullseye">bullseye</option>');
						html.push('<option value="ellipsis-h">ellipsis-h</option>');
						html.push('<option value="ellipsis-v">ellipsis-v</option>');
						html.push('<option value="rss-square">rss-square</option>');
						html.push('<option value="play-circle">play-circle</option>');
						html.push('<option value="ticket">ticket</option>');
						html.push('<option value="minus-square">minus-square</option>');
						html.push('<option value="minus-square-o">minus-square-o</option>');
						html.push('<option value="level-up">level-up</option>');
						html.push('<option value="level-down">level-down</option>');
						html.push('<option value="check-square">check-square</option>');
						html.push('<option value="pencil-square">pencil-square</option>');
						html.push('<option value="external-link-square">external-link-square</option>');
						html.push('<option value="share-square">share-square</option>');
						html.push('<option value="compass">compass</option>');
						html.push('<option value="toggle-down">toggle-down</option>');
						html.push('<option value="caret-square-o-down">caret-square-o-down</option>');
						html.push('<option value="toggle-up">toggle-up</option>');
						html.push('<option value="caret-square-o-up">caret-square-o-up</option>');
						html.push('<option value="toggle-right">toggle-right</option>');
						html.push('<option value="caret-square-o-right">caret-square-o-right</option>');
						html.push('<option value="euro">euro</option>');
						html.push('<option value="eur">eur</option>');
						html.push('<option value="gbp">gbp</option>');
						html.push('<option value="dollar">dollar</option>');
						html.push('<option value="usd">usd</option>');
						html.push('<option value="rupee">rupee</option>');
						html.push('<option value="inr">inr</option>');
						html.push('<option value="cny">cny</option>');
						html.push('<option value="rmb">rmb</option>');
						html.push('<option value="yen">yen</option>');
						html.push('<option value="jpy">jpy</option>');
						html.push('<option value="ruble">ruble</option>');
						html.push('<option value="rouble">rouble</option>');
						html.push('<option value="rub">rub</option>');
						html.push('<option value="won">won</option>');
						html.push('<option value="krw">krw</option>');
						html.push('<option value="bitcoin">bitcoin</option>');
						html.push('<option value="btc">btc</option>');
						html.push('<option value="file">file</option>');
						html.push('<option value="file-text">file-text</option>');
						html.push('<option value="sort-alpha-asc">sort-alpha-asc</option>');
						html.push('<option value="sort-alpha-desc">sort-alpha-desc</option>');
						html.push('<option value="sort-amount-asc">sort-amount-asc</option>');
						html.push('<option value="sort-amount-desc">sort-amount-desc</option>');
						html.push('<option value="sort-numeric-asc">sort-numeric-asc</option>');
						html.push('<option value="sort-numeric-desc">sort-numeric-desc</option>');
						html.push('<option value="thumbs-up">thumbs-up</option>');
						html.push('<option value="thumbs-down">thumbs-down</option>');
						html.push('<option value="youtube-square">youtube-square</option>');
						html.push('<option value="youtube">youtube</option>');
						html.push('<option value="xing">xing</option>');
						html.push('<option value="xing-square">xing-square</option>');
						html.push('<option value="youtube-play">youtube-play</option>');
						html.push('<option value="dropbox">dropbox</option>');
						html.push('<option value="stack-overflow">stack-overflow</option>');
						html.push('<option value="instagram">instagram</option>');
						html.push('<option value="flickr">flickr</option>');
						html.push('<option value="adn">adn</option>');
						html.push('<option value="bitbucket">bitbucket</option>');
						html.push('<option value="bitbucket-square">bitbucket-square</option>');
						html.push('<option value="tumblr">tumblr</option>');
						html.push('<option value="tumblr-square">tumblr-square</option>');
						html.push('<option value="long-arrow-down">long-arrow-down</option>');
						html.push('<option value="long-arrow-up">long-arrow-up</option>');
						html.push('<option value="long-arrow-left">long-arrow-left</option>');
						html.push('<option value="long-arrow-right">long-arrow-right</option>');
						html.push('<option value="apple">apple</option>');
						html.push('<option value="windows">windows</option>');
						html.push('<option value="android">android</option>');
						html.push('<option value="linux">linux</option>');
						html.push('<option value="dribbble">dribbble</option>');
						html.push('<option value="skype">skype</option>');
						html.push('<option value="foursquare">foursquare</option>');
						html.push('<option value="trello">trello</option>');
						html.push('<option value="female">female</option>');
						html.push('<option value="male">male</option>');
						html.push('<option value="gittip">gittip</option>');
						html.push('<option value="gratipay">gratipay</option>');
						html.push('<option value="sun-o">sun-o</option>');
						html.push('<option value="moon-o">moon-o</option>');
						html.push('<option value="archive">archive</option>');
						html.push('<option value="bug">bug</option>');
						html.push('<option value="vk">vk</option>');
						html.push('<option value="weibo">weibo</option>');
						html.push('<option value="renren">renren</option>');
						html.push('<option value="pagelines">pagelines</option>');
						html.push('<option value="stack-exchange">stack-exchange</option>');
						html.push('<option value="arrow-circle-o-right">arrow-circle-o-right</option>');
						html.push('<option value="arrow-circle-o-left">arrow-circle-o-left</option>');
						html.push('<option value="toggle-left">toggle-left</option>');
						html.push('<option value="caret-square-o-left">caret-square-o-left</option>');
						html.push('<option value="dot-circle-o">dot-circle-o</option>');
						html.push('<option value="wheelchair">wheelchair</option>');
						html.push('<option value="vimeo-square">vimeo-square</option>');
						html.push('<option value="turkish-lira">turkish-lira</option>');
						html.push('<option value="try">try</option>');
						html.push('<option value="plus-square-o">plus-square-o</option>');
						html.push('<option value="space-shuttle">space-shuttle</option>');
						html.push('<option value="slack">slack</option>');
						html.push('<option value="envelope-square">envelope-square</option>');
						html.push('<option value="wordpress">wordpress</option>');
						html.push('<option value="openid">openid</option>');
						html.push('<option value="institution">institution</option>');
						html.push('<option value="bank">bank</option>');
						html.push('<option value="university">university</option>');
						html.push('<option value="mortar-board">mortar-board</option>');
						html.push('<option value="graduation-cap">graduation-cap</option>');
						html.push('<option value="yahoo">yahoo</option>');
						html.push('<option value="google">google</option>');
						html.push('<option value="reddit">reddit</option>');
						html.push('<option value="reddit-square">reddit-square</option>');
						html.push('<option value="stumbleupon-circle">stumbleupon-circle</option>');
						html.push('<option value="stumbleupon">stumbleupon</option>');
						html.push('<option value="delicious">delicious</option>');
						html.push('<option value="digg">digg</option>');
						html.push('<option value="pied-piper">pied-piper</option>');
						html.push('<option value="pied-piper-alt">pied-piper-alt</option>');
						html.push('<option value="drupal">drupal</option>');
						html.push('<option value="joomla">joomla</option>');
						html.push('<option value="language">language</option>');
						html.push('<option value="fax">fax</option>');
						html.push('<option value="building">building</option>');
						html.push('<option value="child">child</option>');
						html.push('<option value="paw">paw</option>');
						html.push('<option value="spoon">spoon</option>');
						html.push('<option value="cube">cube</option>');
						html.push('<option value="cubes">cubes</option>');
						html.push('<option value="behance">behance</option>');
						html.push('<option value="behance-square">behance-square</option>');
						html.push('<option value="steam">steam</option>');
						html.push('<option value="steam-square">steam-square</option>');
						html.push('<option value="recycle">recycle</option>');
						html.push('<option value="automobile">automobile</option>');
						html.push('<option value="car">car</option>');
						html.push('<option value="cab">cab</option>');
						html.push('<option value="taxi">taxi</option>');
						html.push('<option value="tree">tree</option>');
						html.push('<option value="spotify">spotify</option>');
						html.push('<option value="deviantart">deviantart</option>');
						html.push('<option value="soundcloud">soundcloud</option>');
						html.push('<option value="database">database</option>');
						html.push('<option value="file-pdf-o">file-pdf-o</option>');
						html.push('<option value="file-word-o">file-word-o</option>');
						html.push('<option value="file-excel-o">file-excel-o</option>');
						html.push('<option value="file-powerpoint-o">file-powerpoint-o</option>');
						html.push('<option value="file-photo-o">file-photo-o</option>');
						html.push('<option value="file-picture-o">file-picture-o</option>');
						html.push('<option value="file-image-o">file-image-o</option>');
						html.push('<option value="file-zip-o">file-zip-o</option>');
						html.push('<option value="file-archive-o">file-archive-o</option>');
						html.push('<option value="file-sound-o">file-sound-o</option>');
						html.push('<option value="file-audio-o">file-audio-o</option>');
						html.push('<option value="file-movie-o">file-movie-o</option>');
						html.push('<option value="file-video-o">file-video-o</option>');
						html.push('<option value="file-code-o">file-code-o</option>');
						html.push('<option value="vine">vine</option>');
						html.push('<option value="codepen">codepen</option>');
						html.push('<option value="jsfiddle">jsfiddle</option>');
						html.push('<option value="life-bouy">life-bouy</option>');
						html.push('<option value="life-buoy">life-buoy</option>');
						html.push('<option value="life-saver">life-saver</option>');
						html.push('<option value="support">support</option>');
						html.push('<option value="life-ring">life-ring</option>');
						html.push('<option value="circle-o-notch">circle-o-notch</option>');
						html.push('<option value="ra">ra</option>');
						html.push('<option value="rebel">rebel</option>');
						html.push('<option value="ge">ge</option>');
						html.push('<option value="empire">empire</option>');
						html.push('<option value="git-square">git-square</option>');
						html.push('<option value="git">git</option>');
						html.push('<option value="y-combinator-square">y-combinator-square</option>');
						html.push('<option value="yc-square">yc-square</option>');
						html.push('<option value="hacker-news">hacker-news</option>');
						html.push('<option value="tencent-weibo">tencent-weibo</option>');
						html.push('<option value="qq">qq</option>');
						html.push('<option value="wechat">wechat</option>');
						html.push('<option value="weixin">weixin</option>');
						html.push('<option value="send">send</option>');
						html.push('<option value="paper-plane">paper-plane</option>');
						html.push('<option value="send-o">send-o</option>');
						html.push('<option value="paper-plane-o">paper-plane-o</option>');
						html.push('<option value="history">history</option>');
						html.push('<option value="circle-thin">circle-thin</option>');
						html.push('<option value="header">header</option>');
						html.push('<option value="paragraph">paragraph</option>');
						html.push('<option value="sliders">sliders</option>');
						html.push('<option value="share-alt">share-alt</option>');
						html.push('<option value="share-alt-square">share-alt-square</option>');
						html.push('<option value="bomb">bomb</option>');
						html.push('<option value="soccer-ball-o">soccer-ball-o</option>');
						html.push('<option value="futbol-o">futbol-o</option>');
						html.push('<option value="tty">tty</option>');
						html.push('<option value="binoculars">binoculars</option>');
						html.push('<option value="plug">plug</option>');
						html.push('<option value="slideshare">slideshare</option>');
						html.push('<option value="twitch">twitch</option>');
						html.push('<option value="yelp">yelp</option>');
						html.push('<option value="newspaper-o">newspaper-o</option>');
						html.push('<option value="wifi">wifi</option>');
						html.push('<option value="calculator">calculator</option>');
						html.push('<option value="paypal">paypal</option>');
						html.push('<option value="google-wallet">google-wallet</option>');
						html.push('<option value="cc-visa">cc-visa</option>');
						html.push('<option value="cc-mastercard">cc-mastercard</option>');
						html.push('<option value="cc-discover">cc-discover</option>');
						html.push('<option value="cc-amex">cc-amex</option>');
						html.push('<option value="cc-paypal">cc-paypal</option>');
						html.push('<option value="cc-stripe">cc-stripe</option>');
						html.push('<option value="bell-slash">bell-slash</option>');
						html.push('<option value="bell-slash-o">bell-slash-o</option>');
						html.push('<option value="trash">trash</option>');
						html.push('<option value="copyright">copyright</option>');
						html.push('<option value="at">at</option>');
						html.push('<option value="eyedropper">eyedropper</option>');
						html.push('<option value="paint-brush">paint-brush</option>');
						html.push('<option value="birthday-cake">birthday-cake</option>');
						html.push('<option value="area-chart">area-chart</option>');
						html.push('<option value="pie-chart">pie-chart</option>');
						html.push('<option value="line-chart">line-chart</option>');
						html.push('<option value="lastfm">lastfm</option>');
						html.push('<option value="lastfm-square">lastfm-square</option>');
						html.push('<option value="toggle-off">toggle-off</option>');
						html.push('<option value="toggle-on">toggle-on</option>');
						html.push('<option value="bicycle">bicycle</option>');
						html.push('<option value="bus">bus</option>');
						html.push('<option value="ioxhost">ioxhost</option>');
						html.push('<option value="angellist">angellist</option>');
						html.push('<option value="cc">cc</option>');
						html.push('<option value="shekel">shekel</option>');
						html.push('<option value="sheqel">sheqel</option>');
						html.push('<option value="ils">ils</option>');
						html.push('<option value="meanpath">meanpath</option>');
						html.push('<option value="buysellads">buysellads</option>');
						html.push('<option value="connectdevelop">connectdevelop</option>');
						html.push('<option value="dashcube">dashcube</option>');
						html.push('<option value="forumbee">forumbee</option>');
						html.push('<option value="leanpub">leanpub</option>');
						html.push('<option value="sellsy">sellsy</option>');
						html.push('<option value="shirtsinbulk">shirtsinbulk</option>');
						html.push('<option value="simplybuilt">simplybuilt</option>');
						html.push('<option value="skyatlas">skyatlas</option>');
						html.push('<option value="cart-plus">cart-plus</option>');
						html.push('<option value="cart-arrow-down">cart-arrow-down</option>');
						html.push('<option value="diamond">diamond</option>');
						html.push('<option value="ship">ship</option>');
						html.push('<option value="user-secret">user-secret</option>');
						html.push('<option value="motorcycle">motorcycle</option>');
						html.push('<option value="street-view">street-view</option>');
						html.push('<option value="heartbeat">heartbeat</option>');
						html.push('<option value="venus">venus</option>');
						html.push('<option value="mars">mars</option>');
						html.push('<option value="mercury">mercury</option>');
						html.push('<option value="intersex">intersex</option>');
						html.push('<option value="transgender">transgender</option>');
						html.push('<option value="transgender-alt">transgender-alt</option>');
						html.push('<option value="venus-double">venus-double</option>');
						html.push('<option value="mars-double">mars-double</option>');
						html.push('<option value="venus-mars">venus-mars</option>');
						html.push('<option value="mars-stroke">mars-stroke</option>');
						html.push('<option value="mars-stroke-v">mars-stroke-v</option>');
						html.push('<option value="mars-stroke-h">mars-stroke-h</option>');
						html.push('<option value="neuter">neuter</option>');
						html.push('<option value="genderless">genderless</option>');
						html.push('<option value="facebook-official">facebook-official</option>');
						html.push('<option value="pinterest-p">pinterest-p</option>');
						html.push('<option value="whatsapp">whatsapp</option>');
						html.push('<option value="server">server</option>');
						html.push('<option value="user-plus">user-plus</option>');
						html.push('<option value="user-times">user-times</option>');
						html.push('<option value="hotel">hotel</option>');
						html.push('<option value="bed">bed</option>');
						html.push('<option value="viacoin">viacoin</option>');
						html.push('<option value="train">train</option>');
						html.push('<option value="subway">subway</option>');
						html.push('<option value="medium">medium</option>');
						html.push('<option value="yc">yc</option>');
						html.push('<option value="y-combinator">y-combinator</option>');
						html.push('<option value="optin-monster">optin-monster</option>');
						html.push('<option value="opencart">opencart</option>');
						html.push('<option value="expeditedssl">expeditedssl</option>');
						html.push('<option value="battery-4">battery-4</option>');
						html.push('<option value="battery-full">battery-full</option>');
						html.push('<option value="battery-3">battery-3</option>');
						html.push('<option value="battery-three-quarters">battery-three-quarters</option>');
						html.push('<option value="battery-2">battery-2</option>');
						html.push('<option value="battery-half">battery-half</option>');
						html.push('<option value="battery-1">battery-1</option>');
						html.push('<option value="battery-quarter">battery-quarter</option>');
						html.push('<option value="battery-0">battery-0</option>');
						html.push('<option value="battery-empty">battery-empty</option>');
						html.push('<option value="mouse-pointer">mouse-pointer</option>');
						html.push('<option value="i-cursor">i-cursor</option>');
						html.push('<option value="object-group">object-group</option>');
						html.push('<option value="object-ungroup">object-ungroup</option>');
						html.push('<option value="sticky-note">sticky-note</option>');
						html.push('<option value="sticky-note-o">sticky-note-o</option>');
						html.push('<option value="cc-jcb">cc-jcb</option>');
						html.push('<option value="cc-diners-club">cc-diners-club</option>');
						html.push('<option value="clone">clone</option>');
						html.push('<option value="balance-scale">balance-scale</option>');
						html.push('<option value="hourglass-o">hourglass-o</option>');
						html.push('<option value="hourglass-1">hourglass-1</option>');
						html.push('<option value="hourglass-start">hourglass-start</option>');
						html.push('<option value="hourglass-2">hourglass-2</option>');
						html.push('<option value="hourglass-half">hourglass-half</option>');
						html.push('<option value="hourglass-3">hourglass-3</option>');
						html.push('<option value="hourglass-end">hourglass-end</option>');
						html.push('<option value="hourglass">hourglass</option>');
						html.push('<option value="hand-grab-o">hand-grab-o</option>');
						html.push('<option value="hand-rock-o">hand-rock-o</option>');
						html.push('<option value="hand-stop-o">hand-stop-o</option>');
						html.push('<option value="hand-paper-o">hand-paper-o</option>');
						html.push('<option value="hand-scissors-o">hand-scissors-o</option>');
						html.push('<option value="hand-lizard-o">hand-lizard-o</option>');
						html.push('<option value="hand-spock-o">hand-spock-o</option>');
						html.push('<option value="hand-pointer-o">hand-pointer-o</option>');
						html.push('<option value="hand-peace-o">hand-peace-o</option>');
						html.push('<option value="trademark">trademark</option>');
						html.push('<option value="registered">registered</option>');
						html.push('<option value="creative-commons">creative-commons</option>');
						html.push('<option value="gg">gg</option>');
						html.push('<option value="gg-circle">gg-circle</option>');
						html.push('<option value="tripadvisor">tripadvisor</option>');
						html.push('<option value="odnoklassniki">odnoklassniki</option>');
						html.push('<option value="odnoklassniki-square">odnoklassniki-square</option>');
						html.push('<option value="get-pocket">get-pocket</option>');
						html.push('<option value="wikipedia-w">wikipedia-w</option>');
						html.push('<option value="safari">safari</option>');
						html.push('<option value="chrome">chrome</option>');
						html.push('<option value="firefox">firefox</option>');
						html.push('<option value="opera">opera</option>');
						html.push('<option value="internet-explorer">internet-explorer</option>');
						html.push('<option value="tv">tv</option>');
						html.push('<option value="television">television</option>');
						html.push('<option value="contao">contao</option>');
						html.push('<option value="500px">500px</option>');
						html.push('<option value="amazon">amazon</option>');
						html.push('<option value="calendar-plus-o">calendar-plus-o</option>');
						html.push('<option value="calendar-minus-o">calendar-minus-o</option>');
						html.push('<option value="calendar-times-o">calendar-times-o</option>');
						html.push('<option value="calendar-check-o">calendar-check-o</option>');
						html.push('<option value="industry">industry</option>');
						html.push('<option value="map-pin">map-pin</option>');
						html.push('<option value="map-signs">map-signs</option>');
						html.push('<option value="map-o">map-o</option>');
						html.push('<option value="map">map</option>');
						html.push('<option value="commenting">commenting</option>');
						html.push('<option value="commenting-o">commenting-o</option>');
						html.push('<option value="houzz">houzz</option>');
						html.push('<option value="vimeo">vimeo</option>');
						html.push('<option value="black-tie">black-tie</option>');
						html.push('<option value="fonticons">fonticons</option>');
						html.push('<option value="reddit-alien">reddit-alien</option>');
						html.push('<option value="edge">edge</option>');
						html.push('<option value="credit-card-alt">credit-card-alt</option>');
						html.push('<option value="codiepie">codiepie</option>');
						html.push('<option value="modx">modx</option>');
						html.push('<option value="fort-awesome">fort-awesome</option>');
						html.push('<option value="usb">usb</option>');
						html.push('<option value="product-hunt">product-hunt</option>');
						html.push('<option value="mixcloud">mixcloud</option>');
						html.push('<option value="scribd">scribd</option>');
						html.push('<option value="pause-circle">pause-circle</option>');
						html.push('<option value="pause-circle-o">pause-circle-o</option>');
						html.push('<option value="stop-circle">stop-circle</option>');
						html.push('<option value="stop-circle-o">stop-circle-o</option>');
						html.push('<option value="shopping-bag">shopping-bag</option>');
						html.push('<option value="shopping-basket">shopping-basket</option>');
						html.push('<option value="hashtag">hashtag</option>');
						html.push('<option value="bluetooth">bluetooth</option>');
						html.push('<option value="bluetooth-b">bluetooth-b</option>');
						html.push('<option value="percent">percent</option>');
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
