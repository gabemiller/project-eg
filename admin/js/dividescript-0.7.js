$(document).ready(function(e) {
	
	$.ajaxSetup({cache:false});

	$('label').each(function(index, element) {
        $(element).children('span').popover();
    });
	
	$('.information').popover();
	
	/**
	 * Notification
	 *
	 */
	 
	 var notify = $('.bottom-right');
	
	/**
	 * Set the date
	 *
	 */
	
	 $('.datetimepicker').datetimepicker({
      format: 'yyyy.MM.dd hh:mm:ss'
    });
	
	/**
	 * Ckeditor 
	 *
	 */
	  if($('#editor').length>0){
	  var editor = CKEDITOR.replace('editor',
	  		{
		 	 extraPlugins : 'div',//'autogrow',
			 //autoGrow_maxHeight: 800,
			 //contentsCss: '../css/bootstrap.min.css'
		});
	  }
	
	 /**
	  * Ajax Upload
	  *
	  * News, Event, Pages
	  */
	 
	  var ajaxForm = $('.ajax-form');
	  var formName = ajaxForm.attr('name');
	  var actionURL = ajaxForm.attr('action');
	  var formEvent = ajaxForm.data('event');
	  
	  ajaxForm.children('button').on('click',function(e){
		 e.preventDefault();
		 ajaxForm.children('p').children('textarea').html(editor.getData());
		 var datas = ajaxForm.serialize();
	 	 $.ajax({
			type: "post",
			url: actionURL,
			data: "event="+formEvent+"&"+datas,
			dataType:"json", 
			success: function(data,textStatus,jqXHR){
				console.log(data);
				switch(data.type){
				case 1:
					notify.notify({
						type: 'success',
						message: { 
							html: '<div><i class="icon-ok-sign"></i> '+data.message+'</div>' 
						},fadeOut: { 
							enabled: true, 
							delay: 2000 
						},
						onClosed: function(){
							if(data.reloadPage == true){
								location.reload();
							} 
						}
					}).show();
							  		
					break;
				default:
					notify.notify({
						type: 'danger',
						message: { 
							html: '<div><i class="icon-exclamation-sign"></i> '+data.message+'</div>' 
						}
					}).show();
					break;
				}
			},
			error: function(jqXHR,textStatus,errorThrown){
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				notify.notify({
						type: 'danger',
						message: { 
							html: '<div><i class="icon-exclamation-sign"></i> Nem sikerült elküldeni az adatokat!</div>' 
						}
				}).show();
			}
		 })
	 })
	 
	/**
	 * ImageUploader
	 *
	 */
	 
	 var formId = $('input[name=id]').attr('value');
	 var fileNum = 0;
	 
	 $('#jquery-wrapped-fine-uploader').fineUploader({
		  debug: true,
          request: {
            endpoint: 'uploadFile.php',
			inputName: 'file',
			customHeaders: { Accept: 'application/json' },
            params: {
				formName: formName,
				formId : formId,
                fileNum: function() {
                    fileNum++;
                    return fileNum;
                }
            }
          },
		  retry: {
            enableAuto: false,
            showButton: true
          },
		  chunking: {
            enabled: false
          },
		  validation: {
        		allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
        		sizeLimit: 20971520 // 2 Mb = 10 * 1024 * 1024 bytes
      		},
		  text: {
            uploadButton: '<div><i class="icon-upload"></i> Képfeltöltés</div>'
          },
          template: 
			  '<div class="qq-uploader span12">' 
			  +'<pre class="qq-upload-drop-area span12"><span>{dragZoneText}</span></pre>' 
			  +'<div class="qq-upload-button btn btn-success" style="width: auto;">{uploadButtonText}</div>' 
			  +'<span class="qq-drop-processing"><span>{dropProcessingText}</span><span class="qq-drop-processing-spinner"></span></span>' 
			  +'<ul class="qq-upload-list" style="margin-top: 10px; text-align: center;"></ul>' 
			  +'</div>',
          classes: {
            success: 'alert alert-success',
            fail: 'alert alert-error'
          },
		  showMessage: function(message) {
			notify.notify({
						type: 'danger',
						message: { 
							html: '<div><i class="icon-exclamation-sign"></i> ' + message + '</div>' 
						}
			}).show();
      	  },
		  display: {
		  	fileSizeOnSubmit: true
		  }
		  
      })
	  .on('complete',function(event,id,fileName,responseJson){
			var listParent = $('.qq-upload-list');
			var list = listParent.children('li:nth-child('+(id+1)+')').addClass(''+(id+1)+'_picture');
			var thumbParent = $('.thumbnails');
			console.log(responseJson);
			
			if(!isEmpty(responseJson)){
				
			var templateNew = '<li class="span6" style="display:none;" >'
							+'<div class="thumbnail">'
							+'<img src="'+responseJson.urlMini+'" data-url="'+responseJson.urlMini+'">'
							+'</div></li>';
			var time = 500;
			
			$('.'+(id+1)+'_picture').fadeOut(time);
			$(templateNew).appendTo(thumbParent).delay(time).fadeIn("fast");
			 
			$('.thumbnail').off('click').on('click','img', function(){
				var element = CKEDITOR.dom.element.createFromHtml( '<img src="'+$(this).data('url')+'" style="width: 200px;margin: 5px"/>' );
				CKEDITOR.instances.editor.insertElement( element );
			 });
			} else {
				list.addClass('span12').css('margin-bottom',5).css('margin-left',0);
			}
	  });
	  
	  
	  $('.thumbnail').on('click','img', function(){
				var element = CKEDITOR.dom.element.createFromHtml( '<img src="'+$(this).data('url')+'" style="width: 200px;margin: 5px"/>' );
				CKEDITOR.instances.editor.insertElement( element );
			 });
	  
	  /**
	   * Return true or false if the element is empty or not.
	   *
	   */
	
	 function isEmpty(element){
		if (
            element === ""          ||
            element === 0           ||
            element === "0"         ||
            element === null        ||
            element === "NULL"      ||
            element === undefined   ||
            element === false
        ) {
        return true;
    }
		if (typeof(element) === 'object') {
			var i = 0;
			for (key in element) {
				i++;
			}
			if (i === 0) { return true; }
		}
		return false;	
	 }
	 
	 /**
	  * Tooltip
	  *
	  */
	 
	 $('.edit-head').children('i').tooltip({title:'Szerkesztés'});
	 $('.watch-head').children('i').tooltip({title:'Megtekintés'});
	 $('.remove-head').children('i').tooltip({title:'Törlés'});
	 
	/**
	 * Datagrid
	 *
	 */
	 
	 var dataTable = $('#data-table');
	 
	  var colCount = 0;
	  dataTable.children('thead').children('tr').first().children('th').each(function () {
		  if ($(this).attr('colspan')) {
			  colCount += +$(this).attr('colspan');
		  } else {
			  colCount++;
		  }
	  });
	  
	  var array = new Array(colCount);
	  
	  for(var i=0;i<array.length;i++){
		array[i] = null; 
	  }
	   array[colCount-1] = { "bSortable": false };
	   array[colCount-2] = { "bSortable": false };
	   array[colCount-3] = { "bSortable": false };
			 
	   var dataOptions = {
					"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
					"sPaginationType": "bootstrap",
					"iDisplayLength": 25,
					"aoColumns": array,
					"oLanguage":{
						"sProcessing":   "Feldolgozás...",
						"sLengthMenu":   "_MENU_ találat oldalanként",
						"sZeroRecords":  "Nincs a keresésnek megfelelő találat",
						"sInfo":         "Találatok: _START_ - _END_ Összesen: _TOTAL_",
						"sInfoEmpty":    "Nulla találat",
						"sInfoFiltered": "(_MAX_ összes rekord közül szűrve)",
						"sInfoPostFix":  "",
						"sSearch":       "Keresés:",
						"sUrl":          "",
						"oPaginate": {
							"sFirst":    "Első",
							"sPrevious": "Előző",
							"sNext":     "Következő",
							"sLast":     "Utolsó"
						}
					}
				};
				
	  
	 
	 /**
	  * Ajax Delete
	  *	News, Events, Pages
	  */
	  	
		var removeElement = $('td.remove-element');
		var eventUrl = removeElement.parents('table').data('url');
		var content = $('.well').html();
	
	    removeElement.each(function(index, element) {
			var id = $(this).closest('tr').children('td').first().text();
			$(element).on('click',this,function(){
				var trElement = this.parentNode;
				var tr = $(this).closest('tr');
				
				$.ajax({
					type: "post",
					url: eventUrl,
					data: "event=remove&id="+id,
					dataType:"json",
					beforeSend: function(){
						return confirm("Biztos törölni akarod?"); 
					},
					success: function(data,textStatus,jqXHR){
						console.log(data);
						switch(data.type){
						case 1:
							notify.notify({
								type: 'success',
								message: { 
									html: '<div><i class="icon-ok-sign"></i> '+data.message+'</div>' 
								},
							}).show();
							
							if(data.page == true){
								
								console.log(tr.html());
								tr.remove();
								$('.well').html(content);
							}else {
								oDataTable.fnDeleteRow( oDataTable.fnGetPosition( trElement ) ) ;
								console.log('anyád');
							}
							break;
						default:
							notify.notify({
								type: 'danger',
								message: { 
									html: '<div><i class="icon-exclamation-sign"></i> '+data.message+'</div>' 
								}
							}).show();
							break;
						}
					},
					error: function(jqXHR,textStatus,errorThrown){
						console.log(jqXHR);
						console.log(textStatus);
						console.log(errorThrown);
						notify.notify({
								type: 'danger',
								message: { 
									html: '<div><i class="icon-exclamation-sign"></i> Nem sikerült elküldeni az adatokat!</div>' 
								}
						}).show();
					}
				 })
			 });
        });
		
	 /**
	  * Ajax Watch
	  *	News, Events, Pages
	  */
	  
	  var watchElement = $('td.watch-element');
	  var eventUrl = watchElement.parents('table').data('url');
	
	  watchElement.each(function(index, element) {
			var id = $(this).closest('tr').children('td').first().text();
			
			$(element).on('click',this,function(){
				
				$.ajax({
					type: "post",
					url: eventUrl,
					data: "event=watch&id="+id,
					dataType:"json",
					success: function(data,textStatus,jqXHR){
						console.log(data);
						if(data.page == true){
							$('.well').html(data.html);
						}else{
							$('<div class="modal hide fade">' + data.html + '</div>').modal();
						}
					},
					error: function(jqXHR,textStatus,errorThrown){
						console.log(jqXHR);
						console.log(textStatus);
						console.log(errorThrown);
						notify.notify({
								type: 'danger',
								message: { 
									html: '<div><i class="icon-exclamation-sign"></i> Nem sikerült lekérni az adatokat!</div>' 
								}
						}).show();
					}
				 })
			 });
        });
		
	/**
	 * Ajax Edit
	 *	News, Events, Pages
	 */
	 var editElement = $('td.edit-element');
	 var editUrl = editElement.parents('table').data('modify');
	 
	 editElement.each(function(index, element) {
        $(element).on('click',this,function(){
			//console.log(editUrl + " " + $(this).closest('tr').children('td').first().text());
			document.location.href = editUrl + "?id=" + $(this).closest('tr').children('td').first().text();
		});
    });
	 
		
    /**
	 * DataTable init
	 *
	 */
		
		 var oDataTable = $('#data-table').dataTable(dataOptions);
	
	
	/**
	 * Ajax login
	 *
	 */
	 
	 	var loginForm = $('.form-signin');
		var messageInfo = loginForm.children('.alert');
		
		loginForm.children('button').on('click',this,function(e){
			e.preventDefault();
			var data = loginForm.serialize();
			console.log(data);
			$.ajax({
				type: "post",
				url: loginForm.attr('action'),
				data: data,
				dataType:"json",
				success: function(data,textStatus,jqXHR){
					console.log(data);
					if(data.login){
						location.reload();
					} else {
						var message = '<i class="icon-exclamation-sign"></i> ' + data.message;
						messageInfo.slideUp('slow',function(){
							$(this).html(message);
							$(this).slideDown('slow');
						});
						
					}
					
				},
				error:  function(jqXHR,textStatus,errorThrown){
					console.log(jqXHR);
					console.log(textStatus);
					console.log(errorThrown);
					
					var message = '<i class="icon-exclamation-sign"></i> Nem sikerült elküldeni az adatokat!';
					messageInfo.slideUp('slow',function(){
							$(this).html(message);
							$(this).slideDown('slow');
					});
				}
			});
		});
		
		/**
		 * Ajax Data Modify
		 *
		 */
		 
      var adminForm = $('.admin-form');
	  
	  
	  adminForm.each(function(index, element) {
        var elementForm = $(element);
		var formName = elementForm.attr('name');
	  	var actionURL = elementForm.attr('action');
	  	var formEvent = elementForm.data('event');
		
		elementForm.find('button').on('click',function(e){
		 e.preventDefault();
		 var datas = elementForm.serialize();
	 	 $.ajax({
			type: "post",
			url: actionURL,
			data: "event="+formEvent+"&"+datas,
			dataType:"json", 
			success: function(data,textStatus,jqXHR){
				console.log(data);
				switch(data.type){
				case 1:
					notify.notify({
						type: 'success',
						message: { 
							html: '<div><i class="icon-ok-sign"></i> '+data.message+'</div>' 
							}
					}).show();
							  		
					break;
				default:
					notify.notify({
						type: 'danger',
						message: { 
							html: '<div><i class="icon-exclamation-sign"></i> '+data.message+'</div>' 
						}
					}).show();
					break;
				}
			},
			error: function(jqXHR,textStatus,errorThrown){
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				notify.notify({
						type: 'danger',
						message: { 
							html: '<div><i class="icon-exclamation-sign"></i> Nem sikerült elküldeni az adatokat!</div>' 
						}
				}).show();
			}
		 })
	 })
    });
		
});
