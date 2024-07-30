'use strict';

document.addEventListener( 'DOMContentLoaded', function () {
	const generateList = ( data, settings ) => {
		let output = '';

		if ( 0 === data.length ) {
			return output;
		}

		data.forEach( function ( item ) {
			output += `<li><a href="${ item.url }" target="_blank">${ item.title }</a></li>`;
		} );

		return `<${ settings.list_type }>${ output }</${ settings.list_type }>`;
	};

	const nscwBlogPosts = ( args ) => {
		const defaultArgs = {
			api: '',
			selector: '#blog-list',
			action: 'blog_posts',
			loading_text: 'Loading...',
			list_type: 'ul',
		};

		const settings = { ...defaultArgs, ...args };

		if ( ! settings.api ) {
			return;
		}

		const targetSelector = document.querySelector( settings.selector );

		if ( ! targetSelector ) {
			return;
		}

		const formData = new FormData();

		formData.append( 'action', settings.action );

		targetSelector.innerHTML = settings.loading_text;

		fetch( settings.api, {
			method: 'POST',
			body: formData,
		} )
			.then( ( res ) => res.json() )
			.then( ( rawData ) => {
				targetSelector.innerHTML = generateList( rawData.data, settings );
			} )
			.catch( ( err ) => {
				console.log( err );
				targetSelector.innerHTML = '';
			} );
	};

	nscwBlogPosts( {
		api: ajaxurl,
		selector: '.ns-blog-list',
		action: 'obulma_nsbl_get_posts',
	} );
} );
