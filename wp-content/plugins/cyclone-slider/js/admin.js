(function($) {	
	$(function() {

		//hide wordpress admin stuff
		$('#minor-publishing-actions').hide();
		$('#misc-publishing-actions').hide();
		
		//make it sortable
		$('.cycloneslider-sortable').sortable({
			handle:'.cycloneslider-box-drag',
			placeholder: "cycloneslider-box-placeholder",
			forcePlaceholderSize:true,
			update: function(event, ui) {
				$('.cycloneslider-sortable .cycloneslider-box').each(function(i){
					$(this).find('.cycloneslider-slide-meta-id').attr('name', 'cycloneslider_metas['+(i)+'][id]');
					$(this).find('.cycloneslider-slide-meta-link').attr('name', 'cycloneslider_metas['+(i)+'][link]');
					$(this).find('.cycloneslider-slide-meta-title').attr('name', 'cycloneslider_metas['+(i)+'][title]');
					$(this).find('.cycloneslider-slide-meta-description').attr('name', 'cycloneslider_metas['+(i)+'][description]');
				});
			}
		});
		
		//accordion
		$( '.cycloneslider-accordion' ).accordion({
			collapsible: true,
			icons:false,
			active:false
		});
		
		//id
		$('.cycloneslider-upload-button').each(function(i){
			$(this).data('cycloneslider_id',i);
		});
		$('.cycloneslider-sortable .cycloneslider-box').each(function(i){
			$(this).data('cycloneslider_id',i);
		});
		
		
		// Add new slide box
		$('input[name="cycloneslider_add_slide"]').on('click', function(e){
			var id = $('.cycloneslider-sortable .cycloneslider-box').length;
			var html = $('.cycloneslider-box-template').html();
			html = html.replace(/{id}/g, id);//replace all occurences of {id} to real id
			
			$('.cycloneslider-sortable').append(html);
			$('.cycloneslider-sortable .cycloneslider-box:last').addClass('cycloneslider-fresh-slide').find('.cycloneslider-slide-thumb').hide();
			$('.cycloneslider-upload-button').each(function(i){
				$(this).data('cycloneslider_id',i);
			});
			$('.cycloneslider-sortable .cycloneslider-box').each(function(i){
				$(this).data('cycloneslider_id',i);
			});
			$( '.cycloneslider-accordion' ).accordion({
				collapsible: true,
				icons:false,
				active:false
			});
			e.preventDefault();
		});
		
		//toggle
		$('.cycloneslider-box-toggle').live('click',  function(e) {
			var box = $(this).parents('.cycloneslider-box');
			var body = box.find('.cycloneslider-box-body');
			
			if(body.is(':visible')){
				body.slideUp(100);
				if($.cookie!=undefined){
					$.cookie('cycloneslider_box_'+box.data('cycloneslider_id'), null);
				}
			} else {
				body.slideDown(100);
				if($.cookie!=undefined){
					$.cookie('cycloneslider_box_'+box.data('cycloneslider_id'), 'open', { expires: 7});//remember open section
				}
			}
			e.preventDefault();
		});
		
		//remove yellow borders
		$('.cycloneslider-sortable .cycloneslider-fresh-slide').live('mouseenter',  function(e) {
			$(this).removeClass('cycloneslider-fresh-slide');
			e.preventDefault();
		});
		
		//hide all thats hidden
		$('.cycloneslider-sortable .cycloneslider-box').each(function(){
			var body = $(this).find('.cycloneslider-box-body');
			var id = $(this).data('cycloneslider_id');
			if($.cookie!=undefined){
				if($.cookie('cycloneslider_box_'+id)!='open'){//do not close open section
					body.hide();
				}
			}
		});
		
		//delete
		$('.cycloneslider-box-delete').live('click',function(e) {
			//if(confirm("Are you sure? Press OK to continue.")){
			var box = $(this).parents('.cycloneslider-box');
			box.fadeOut('slow', function(){ box.remove()});
			//}
			e.preventDefault();
		});
	});
	
	$(function() {
		// WP media uploader
		var current_slide_box = false;//we use this var to determine if thickbox is being used in cycloneslider. also saves the field to be updated later.
		$('.cycloneslider-upload-button').live('click',function() {
			var box = $(this).parents('.cycloneslider-box');//get current box
			
			current_slide_box = box;
			tb_show('', 'media-upload.php?referer=cycloneslider&amp;type=image&amp;TB_iframe=true');//referer param needed to change button text
			return false;
		});
		
		window.original_send_to_editor = window.send_to_editor;// backup original for other parts of admin that uses thickbox to work
		window.send_to_editor = function(html) {
			//console.log(html);
			if (current_slide_box) {
				var slide_path_field = current_slide_box.find('.cycloneslider-slide-path');//find the input field
				var slide_thumb = current_slide_box.find('.cycloneslider-slide-thumb');//find the thumb
				var slide_attachment_id = current_slide_box.find('.cycloneslider-slide-meta-id');//find the hidden field that will hold the attachment id
				
				imgurl = jQuery('img',html).attr('src');
				attachment_id = jQuery('img',html).attr('data-id');
				
				slide_thumb.attr('src', imgurl).show();
				slide_path_field.val(imgurl);
				slide_attachment_id.val(attachment_id);
				tb_remove();
				current_slide_box = false;
			} else {
				window.original_send_to_editor(html);
			}
		}
	});
	
})(jQuery);