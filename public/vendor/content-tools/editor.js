window.addEventListener('load', function() {
    var editor;
	ContentTools.DEFAULT_TOOLS= [
		['bold', 'italic', 'link', 'align-left', 'align-center', 'align-right'], 
		['heading', 'subheading', 'paragraph', 'unordered-list', 'ordered-list', 'table', 'indent', 'unindent', 'line-break'], 
		['image', 'video', 'preformatted'], 
		['undo', 'redo', 'remove'], 
		['insert-web-page']
	];
	
	var __hasProp = {}.hasOwnProperty;
	var __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; }
	
	ContentTools.Tools.InsertWebPage = (function(_super) {
		__extends(InsertWebPage, _super);

		function InsertWebPage() {
		  return InsertWebPage.__super__.constructor.apply(this, arguments);
		}

		ContentTools.ToolShelf.stow(InsertWebPage, 'insert-web-page');

		InsertWebPage.label = 'Insert Web Page';

		InsertWebPage.icon = 'subheading';

		InsertWebPage.canApply = function(element, selection) {
		  return !element.isFixed();
		};

		InsertWebPage.apply = function(element, selection, callback) {
		  var app, list, row, table, toolDetail;
		  toolDetail = {
			'tool': this,
			'element': element,
			'selection': selection
		  };
		  if (!this.dispatchEditorEvent('tool-apply', toolDetail)) {
			return;
		  }
		  app = this.editor();
		  element.blur();
		  if (element.nextContent()) {
			element.nextContent().focus();
		  } else if (element.previousContent()) {
			element.previousContent().focus();
		  }
		  if (!element.isMounted()) {
			callback(true);
			this.dispatchEditorEvent('tool-applied', toolDetail);
			return;
		  }
		  var IE =  navigator.userAgent.match(/msie/i);
		  $.fancybox({
            'width': 1024,
            'height': 768,
            'autoScale': false,
            'transitionIn': 'fade',
            'transitionOut': 'fade',
            'type': 'iframe',
            'href': 'http://www.example.com',
			'afterShow':function() {
				var iframe = $(this.content[1]);
				var input = this.content.find('input');
				var insert = this.content.find('button.btn-insert');
				this.content.find('button.btn-go').on('click', function() {
					console.log(iframe[0].src="/bouncer/admin/public/admin/download?url="+input.val());
				});
				
				insert.on('click', function() {
					 cursor = selection.get()[0] + 1;
					 tip = element.content.substring(0, selection.get()[0]);
					 tail = element.content.substring(selection.get()[1]);
					 //(iframe[0].contentDocument || iframe[0].contentWindow.document).body.innerHTML
					 
					 var id = "site_"+Math.floor((Math.random() * 999999) + 1);;
					 br = new HTMLString.String('<br><div id="'+id+'"></div>', element.content.preserveWhitespace());
					 
					 element.content = tip.concat(br, tail);
					 element.updateInnerHTML();
					 element.taint();
					 selection.set(cursor, cursor);
					 element.selection(selection);
					 
					 $("#"+id).html((iframe[0].contentDocument || iframe[0].contentWindow.document).body.innerHTML);
				});
			},
			'tpl': {
				'iframe':'<div>URL: <input type="text" placeholder="http://youtube.fr"><button class="btn btn-go">Go</button><button class="btn btn-insert">insert</button></div><iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0"></iframe>'
				//wrap     : '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
				//image    : '<img class="fancybox-image" src="{href}" alt="" />',
				//iframe   : '<iframe id="fancybox-frame{rnd}" name="fancybox-frame{rnd}" class="fancybox-iframe" frameborder="0" vspace="0" hspace="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen' + (IE ? ' allowtransparency="true"' : '') + '></iframe>',
				//error    : '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
				//closeBtn : '<a title="Close" class="fancybox-item fancybox-close" href="javascript:;"></a>',
				//next     : '<a title="Next" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
				//prev     : '<a title="Previous" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
			},
        });
		  
		  callback(true);
		  return this.dispatchEditorEvent('tool-applied', toolDetail);
		};

		return InsertWebPage;

	})(ContentTools.Tool);

	
	function imageUploader(dialog) {
		 var image, xhr, xhrComplete, xhrProgress;
	
		// Set up the event handlers
		dialog.addEventListener('imageuploader.cancelupload', function () {
			// Cancel the current upload

			// Stop the upload
			if (xhr) {
				xhr.upload.removeEventListener('progress', xhrProgress);
				xhr.removeEventListener('readystatechange', xhrComplete);
				xhr.abort();
			}

			// Set the dialog to empty
			dialog.state('empty');
		});
		
		
		dialog.addEventListener('imageuploader.clear', function () {
			// Clear the current image
			dialog.clear();
			image = null;
		});
		
		
		dialog.addEventListener('imageuploader.fileready', function (ev) {

			// Upload a file to the server
			var formData;
			var file = ev.detail().file;

			// Define functions to handle upload progress and completion
			xhrProgress = function (ev) {
				// Set the progress for the upload
				dialog.progress((ev.loaded / ev.total) * 100);
			}

			xhrComplete = function (ev) {
				var response;

				// Check the request is complete
				if (ev.target.readyState != 4) {
					return;
				}

				// Clear the request
				xhr = null
				xhrProgress = null
				xhrComplete = null

				// Handle the result of the upload
				if (parseInt(ev.target.status) == 200) {
					// Unpack the response (from JSON)
					response = JSON.parse(ev.target.responseText);

					// Store the image details
					image = {
						size: response.size,
						url: response.url
						};

					// Populate the dialog
					dialog.populate(image.url, image.size);

				} else {
					// The request failed, notify the user
					new ContentTools.FlashUI('no');
				}
			}

			// Set the dialog state to uploading and reset the progress bar to 0
			dialog.state('uploading');
			dialog.progress(0);

			// Build the form data to post to the server
			formData = new FormData();
			formData.append('image', file);

			// Make the request
			xhr = new XMLHttpRequest();
			xhr.upload.addEventListener('progress', xhrProgress);
			xhr.addEventListener('readystatechange', xhrComplete);
			xhr.open('POST', '/upload-image', true);
			xhr.send(formData);
		});
		
		function rotateImage(direction) {
			// Request a rotated version of the image from the server
			var formData;

			// Define a function to handle the request completion
			xhrComplete = function (ev) {
				var response;

				// Check the request is complete
				if (ev.target.readyState != 4) {
					return;
				}

				// Clear the request
				xhr = null
				xhrComplete = null

				// Free the dialog from its busy state
				dialog.busy(false);

				// Handle the result of the rotation
				if (parseInt(ev.target.status) == 200) {
					// Unpack the response (from JSON)
					response = JSON.parse(ev.target.responseText);

					// Store the image details (use fake param to force refresh)
					image = {
						size: response.size,
						url: response.url + '?_ignore=' + Date.now()
						};

					// Populate the dialog
					dialog.populate(image.url, image.size);

				} else {
					// The request failed, notify the user
					new ContentTools.FlashUI('no');
				}
			}

			// Set the dialog to busy while the rotate is performed
			dialog.busy(true);

			// Build the form data to post to the server
			formData = new FormData();
			formData.append('url', image.url);
			formData.append('direction', direction);

			// Make the request
			xhr = new XMLHttpRequest();
			xhr.addEventListener('readystatechange', xhrComplete);
			xhr.open('POST', '/rotate-image', true);
			xhr.send(formData);
		}

		dialog.addEventListener('imageuploader.rotateccw', function () {
			rotateImage('CCW');
		});

		dialog.addEventListener('imageuploader.rotatecw', function () {
			rotateImage('CW');
		});
		
		
		dialog.addEventListener('imageuploader.save', function () {
			var crop, cropRegion, formData;

			// Define a function to handle the request completion
			xhrComplete = function (ev) {
				// Check the request is complete
				if (ev.target.readyState !== 4) {
					return;
				}

				// Clear the request
				xhr = null
				xhrComplete = null

				// Free the dialog from its busy state
				dialog.busy(false);

				// Handle the result of the rotation
				if (parseInt(ev.target.status) === 200) {
					// Unpack the response (from JSON)
					var response = JSON.parse(ev.target.responseText);

					// Trigger the save event against the dialog with details of the
					// image to be inserted.
					dialog.save(
						response.url,
						response.size,
						{
							'alt': response.alt,
							'data-ce-max-width': response.size[0]
						});

				} else {
					// The request failed, notify the user
					new ContentTools.FlashUI('no');
				}
			}

			// Set the dialog to busy while the rotate is performed
			dialog.busy(true);

			// Build the form data to post to the server
			formData = new FormData();
			formData.append('url', image.url);

			// Set the width of the image when it's inserted, this is a default
			// the user will be able to resize the image afterwards.
			formData.append('width', 600);

			// Check if a crop region has been defined by the user
			if (dialog.cropRegion()) {
				formData.append('crop', dialog.cropRegion());
			}

			// Make the request
			xhr = new XMLHttpRequest();
			xhr.addEventListener('readystatechange', xhrComplete);
			xhr.open('POST', '/insert-image', true);
			xhr.send(formData);
		});
	}
	
	
	ContentTools.IMAGE_UPLOADER = imageUploader;
	ContentTools.StylePalette.add([
		new ContentTools.Style('Author', 'author', ['p'])
	]);
	
	editor = ContentTools.EditorApp.get();
	editor.init('*[data-editable]', 'data-name');
	
	editor.addEventListener('saved', function (ev) {
		var name, payload, regions, xhr;

		// Check that something changed
		regions = ev.detail().regions;
		if (Object.keys(regions).length == 0) {
			return;
		}

		// Set the editor as busy while we save our changes
		this.busy(true);

		// Collect the contents of each region into a FormData instance
		payload = new FormData();
		for (name in regions) {
			if (regions.hasOwnProperty(name)) {
				payload.append(name, regions[name]);
			}
		}

		// Send the update content to the server to be saved
		function onStateChange(ev) {
			// Check if the request is finished
			if (ev.target.readyState == 4) {
				editor.busy(false);
				if (ev.target.status == '200') {
					// Save was successful, notify the user with a flash
					new ContentTools.FlashUI('ok');
				} else {
					// Save failed, notify the user with a flash
					new ContentTools.FlashUI('no');
				}
			}
		};

		xhr = new XMLHttpRequest();
		xhr.addEventListener('readystatechange', onStateChange);
		xhr.open('POST', '/save-my-page');
		xhr.send(payload);
	});
});