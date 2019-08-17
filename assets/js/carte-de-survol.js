( function( bp, wp, $ ) {
	// Bail if not set
    if ( typeof carteDeSurvol === 'undefined' ) {
        return;
	}

	var pointers = [];

	if ( ! bp.template ) {
		_.extend( bp, _.pick( wp, 'template' ) );
	}

	var getUserName = function( selector ) {
		var userName = $( selector ).prop( 'href' ).replace( carteDeSurvol.pattern, '' );

		if ( ! userName ) {
			return '';
		}

		userName = userName.split( '/' ).filter( function( v ) {
			return v.length > 0;
		} );

		if ( userName.length !== 1 ) {
			return '';
		}

		return _.first( userName );
	};

	var showHoverCard = function( event ) {
		var userName = getUserName( event.currentTarget ), $userLink = $( event.currentTarget ),
		    template = bp.template( 'hovercard' );

		if ( ! userName ) {
			return event;
		}

		var hoverCardContent = template( { loader: carteDeSurvol.loader } );
		if ( pointers[ userName ] ) {
			hoverCardContent = template( pointers[ userName ] );
		}

		$userLink.pointer( {
			content: hoverCardContent,
			pointerClass: 'carte-de-survol',
			position: {
				edge:'top',
				offset: '-25 0',
			}
		} ).pointer( 'open' );

		if ( ! pointers[ userName ] ) {
			bp.apiRequest( {
				path: 'buddypress/v1/members',
				type: 'GET',
				data: {
				  context: 'view',
				  slug: userName
				}
			} ).done( function( data ) {
				pointers[ userName ] = _.first( data );

				$userLink.pointer( {
					content: template( pointers[ userName ] ),
				} ).pointer( 'update' );
			} ).fail( function( error ) {
				$userLink.pointer( {
					content: template( error.responseJSON ),
				} ).pointer( 'update' );
			} );
		}
	};

	var hideHoverCard = function( event ) {
		var userName = getUserName( event.currentTarget );

		if ( ! userName ) {
			return event;
		}

		setTimeout( function() {
			$( event.currentTarget ).pointer().delay( 4000 ).pointer( 'close' );
		 }, 3000 );
	};

	var setHoverCards = function() {
		$( 'a[href^="' + carteDeSurvol.pattern + '"]').hoverIntent( {
			over: showHoverCard,
			out: hideHoverCard,
		} );
	}

	$( document ).ready( setHoverCards );
	$( '[data-bp-list]' ).on( 'bp_ajax_request', setHoverCards );
} )( window.bp || {}, window.wp || {}, jQuery );
