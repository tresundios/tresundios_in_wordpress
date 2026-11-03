/* Case-study filters: the links work without JS (term archives); with JS they filter in place. */
( function () {
	'use strict';
	var nav = document.querySelector( '[data-work-filters]' ), grid = document.querySelector( '[data-work-grid]' );
	if ( ! nav || ! grid || document.body.classList.contains( 'tax-work_type' ) ) return;
	var links = [].slice.call( nav.querySelectorAll( '[data-filter]' ) );
	links.forEach( function ( a ) { a.setAttribute( 'role', 'button' ); a.setAttribute( 'aria-pressed', a.hasAttribute( 'aria-current' ) ); a.removeAttribute( 'aria-current' ); } );
	nav.addEventListener( 'click', function ( e ) {
		var a = e.target.closest( '[data-filter]' );
		if ( ! a ) return;
		e.preventDefault();
		var f = a.dataset.filter;
		links.forEach( function ( x ) { x.setAttribute( 'aria-pressed', x === a ); } );
		[].forEach.call( grid.children, function ( card ) {
			card.hidden = ! ( f === 'all' || ( ' ' + card.dataset.types + ' ' ).indexOf( ' ' + f + ' ' ) > -1 );
		} );
	} );
} )();
