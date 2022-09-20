( function( $ ) {
	$.fn.blogPosts = function( options ) {
		var settings = $.extend( {
			api: '',
			action: 'blog_posts',
			loading_text: 'Loading',
			list_type: 'ul',
		}, options );

		if ( '' === settings.api ) {
			return this;
		}

		function generateList( data ) {
			var output = '';

			if ( 0 === data.length ) {
				return output;
			}

			data.forEach( function( item ) {
				output += '<li><a href="' + item.url + '" target="_blank">' + item.title + '</a></li>';
			} );

			return $( '<' + settings.list_type + '/>' ).append( output );
		}

		return this.each( function() {
			var $wrapper = $( this );

			$.ajax( {
				url: settings.api,
				type: 'GET',
				dataType: 'json',
				data: { action: settings.action },
				beforeSend: function() {
					$wrapper.html( settings.loading_text );
				},
				complete: function( jqXHR ) {
					var response = JSON.parse( jqXHR.responseText );

					$wrapper.html( '' );

					if ( true === response.success ) {
						var listMarkup = generateList( response.data );
						$wrapper.append( listMarkup );
					}
				},
			} );
		} );
	};

	$( '.ns-blog-list' ).blogPosts( {
		api: ajaxurl,
		action: 'obulma_nsbl_get_posts',
	} );
}( jQuery ) );

