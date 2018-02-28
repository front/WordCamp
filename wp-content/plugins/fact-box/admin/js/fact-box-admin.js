(function( $ ) {
	'use strict';

	$(document).ready(function() {
    var modal = $('#nfModal');
    var btn = $("#nf_fact_box");
    var span = $(".nf-modal-close");
    // Open Fact Box modal
    btn.on('click', function() {
      modal.css('display', 'block');
    });

    // Close Fact Box modal on click X button
    span.on('click', function() {
      modal.fadeOut(200);
    });

    // Close Fact Box modal on click away
    $(window).on('click', function(e) {
      if (e.target.className.indexOf('modal') > -1) {
        modal.fadeOut(200);
      }
    });

    // Close Fact Box modal on escape key press
    $(window).keyup(function(e) {
     if (e.keyCode == 27) {
       modal.fadeOut(200);
      }
    });

    $('input[name=search_fact]').on('keyup', function(data){
      var search_query = $(this).val();
      var queryString = { "action": "live-searh-function", "title" : search_query};
      $.ajax({
          url: ajaxurl,
          type: "POST",
          data: queryString,
          cache: false,
          dataType: "html",
          success: function(data){
            $('#fact_results').html(data);
            insertFact(".nf-fact-title");
          }
     });
    });

    insertFact(".nf-fact-title");


    $('#showNewFactForm').on('click', function(){
      $('#selectFacts').css('display', 'none');
      $('#newFactBox').css('display', 'inline');
			$('.insert-existing').toggleClass('hide-element');
			$('.insert-new').removeClass('hide-element');
    });

    $('#backToSearch').on('click', function(){
      $('#newFactBox').css('display', 'none');
      $('#selectFacts').css('display', 'inline');
			$('.insert-new').toggleClass('hide-element');
			$('.insert-existing').toggleClass('hide-element');
    });

    $('#addNewFactBox').on('click', function(){
      var fact_title = $('input[name=fact_title]').val();
      var fact_id = $('input[name=fact_id]').val();
      var fact_content = tinyMCE.get('factcontentid').getContent();
			if ( fact_title && fact_content ) {
				var queryString = { "action": "add-new-fact_box", "title" : fact_title, "content": fact_content, "fact_box_id": fact_id};
				$.ajax({
					url: ajaxurl,
					type: "POST",
					data: queryString,
					cache: false,
					dataType: "html",
					success: function(data){
						var fact_box_id = data;
						var fact_box = '[fact_box fact_box_id="' + fact_box_id + '"]';
						tinymce.get('content').execCommand('mceInsertContent', false, fact_box);
						modal.css('display', 'none');
					}
				});
			}
    });


    function insertFact(factSelector){
      $(factSelector).click(function() {
        var fact_box_id = $(this).attr('id');
        var fact_box = '[fact_box fact_box_id="' + fact_box_id + '"]';
        window.send_to_editor(fact_box);
        modal.css('display', 'none');
      });
    }

		// Show or hide custom styling editor
		var custom_style_toggle = $('#fact_box_fact_box_use_custom_style'),
				custom_style_editor = $('.tf-code').parent();

		if ( !custom_style_toggle.is(":checked") ) {
			custom_style_editor.hide();
		}

		custom_style_toggle.parent().on('click', function(){
			setTimeout(function(){
				if ( custom_style_toggle.is(":checked") ) {
					custom_style_editor.show();
				} else {
					custom_style_editor.hide();
				}
			}, 100);
		});

  });
})( jQuery );
