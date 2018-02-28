// Render Fact-box inside post editor the wordpress way
( function( window, views, media, $ ) {
	var base, fact_box;

	fact_box = _.extend( {}, base, {
		state: [ 'fact_box' ],
		template: media.template( 'fact-box' ),

		initialize: function() {
				attrs = this.shortcode.attrs.named,
				self = this;

				// Calling ajax function that is responsible for retriving fact-box data and then, render fact-box
				var fact_box_obj = htmlPromise(attrs.fact_box_id).then(function(response) {
					fact_box_data = JSON.parse(response);

					self.render( self.template( {
						fact_box: fact_box_data
					} ) );

				});

		}
	} );

	// Ajax function to retrive fact-box data from database
	function htmlPromise(fact_box_id) {
		var factBoxPost = 'title';
		var factData = { 'action' : 'get-single-fact_box', 'postid' : fact_box_id };
		return new Promise(function(resolve, reject) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				cache: false,
				data: factData,
	      success: function(data){
					resolve(data);
	      }
			})
		});
	}

	views.register( 'fact_box', _.extend( {}, fact_box ) );

} )( window, window.wp.mce.views, window.wp.media, window.jQuery );
