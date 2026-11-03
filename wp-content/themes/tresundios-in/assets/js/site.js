/* Tresundios .in: site-wide behaviour (no dependencies). */
( function () {
	'use strict';
	var $ = function ( s, c ) { return ( c || document ).querySelector( s ); };
	var $$ = function ( s, c ) { return [].slice.call( ( c || document ).querySelectorAll( s ) ); };
	function esc( t ) { var d = document.createElement( 'div' ); d.textContent = t; return d.innerHTML; }

	/* Mobile menu */
	var nav = $( '.site-nav' ), toggler = $( '.navbar-toggler' );
	if ( toggler ) {
		toggler.addEventListener( 'click', function () { toggler.setAttribute( 'aria-expanded', nav.classList.toggle( 'is-open' ) ); } );
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && nav.classList.contains( 'is-open' ) ) { nav.classList.remove( 'is-open' ); toggler.setAttribute( 'aria-expanded', 'false' ); toggler.focus(); }
		} );
	}

	/* Spotlight position on cards */
	document.addEventListener( 'pointermove', function ( e ) {
		var el = e.target.closest && e.target.closest( '.spot' );
		if ( ! el ) return;
		var r = el.getBoundingClientRect();
		el.style.setProperty( '--mx', ( e.clientX - r.left ) + 'px' );
		el.style.setProperty( '--my', ( e.clientY - r.top ) + 'px' );
	}, { passive: true } );

	/* Accessible tabs (pricing) */
	$$( '[role="tablist"]' ).forEach( function ( list ) {
		var tabs = $$( '[role="tab"]', list );
		function show( tab, focus ) {
			tabs.forEach( function ( t ) {
				var on = t === tab;
				t.setAttribute( 'aria-selected', on );
				t.tabIndex = on ? 0 : -1;
				document.getElementById( t.getAttribute( 'aria-controls' ) ).hidden = ! on;
			} );
			if ( focus ) tab.focus();
		}
		tabs.forEach( function ( t, i ) {
			t.addEventListener( 'click', function () { show( t ); } );
			t.addEventListener( 'keydown', function ( e ) {
				var j = { ArrowRight: i + 1, ArrowLeft: i - 1, Home: 0, End: tabs.length - 1 }[ e.key ];
				if ( j === undefined ) return;
				e.preventDefault();
				show( tabs[ ( j + tabs.length ) % tabs.length ], true );
			} );
		} );
	} );

	/* Copy buttons */
	$$( '.copy[data-copy]' ).forEach( function ( b ) {
		b.addEventListener( 'click', function () {
			var label = b.innerHTML;
			var done = function () { b.textContent = 'Copied'; setTimeout( function () { b.innerHTML = label; }, 1500 ); };
			try { navigator.clipboard.writeText( b.dataset.copy ).then( done, function () { b.textContent = 'Select & copy'; } ); } catch ( err ) { b.textContent = 'Select & copy'; }
		} );
	} );

	/* Book-a-call dialog (the link opens Google Calendar directly without JS) */
	var dialog;
	document.addEventListener( 'click', function ( e ) {
		var a = e.target.closest( 'a[data-book-call]' );
		if ( ! a || ! window.HTMLDialogElement || e.metaKey || e.ctrlKey || e.shiftKey ) return;
		e.preventDefault();
		if ( ! dialog ) {
			dialog = document.createElement( 'dialog' );
			dialog.className = 'book-dialog';
			dialog.setAttribute( 'aria-labelledby', 'bookTitle' );
			dialog.innerHTML = '<header><h2 id="bookTitle">Book a free call</h2><button class="icon-btn" type="button" aria-label="Close" data-close>✕</button></header>' +
				'<div class="body"><iframe title="Choose an appointment time with Tresundios" src="' + esc( a.href ) + '"></iframe></div>';
			dialog.addEventListener( 'click', function ( ev ) { if ( ev.target === dialog || ev.target.closest( '[data-close]' ) ) dialog.close(); } );
			document.body.appendChild( dialog );
		}
		if ( nav ) { nav.classList.remove( 'is-open' ); toggler && toggler.setAttribute( 'aria-expanded', 'false' ); }
		dialog.showModal();
	} );
} )();
