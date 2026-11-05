/* Tresundios .in: "3D & immersive" layer. Pointer parallax, draggable cube, card tilt, scroll depth reveal.
   No dependencies. Does nothing under prefers-reduced-motion; tilt/parallax only for fine pointers. */
( function () {
	'use strict';
	if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) return;
	var fine = window.matchMedia( '(hover: hover) and (pointer: fine)' ).matches;
	var root = document.documentElement;
	root.classList.add( 'js-3d' );
	var $$ = function ( s, c ) { return [].slice.call( ( c || document ).querySelectorAll( s ) ); };

	/* 1. Hero scene: layered parallax that follows the pointer. */
	var scene = document.querySelector( '[data-scene3d]' );
	if ( scene && fine ) {
		var hero = scene.closest( '.hero' ) || scene;
		hero.addEventListener( 'pointermove', function ( e ) {
			var r = hero.getBoundingClientRect();
			scene.style.setProperty( '--px', ( ( e.clientX - r.left ) / r.width - 0.5 ) * 18 + 'px' );
			scene.style.setProperty( '--py', ( ( e.clientY - r.top ) / r.height - 0.5 ) * 14 + 'px' );
		}, { passive: true } );
		hero.addEventListener( 'pointerleave', function () { scene.style.setProperty( '--px', '0px' ); scene.style.setProperty( '--py', '0px' ); } );
	}

	/* 2. Cube: drag (mouse or touch) to spin it 360°, with a little inertia. */
	var cube = document.querySelector( '[data-cube]' );
	if ( cube ) {
		var wrap = cube.parentNode, rx = -18, ry = 28, vx = 0, vy = 0, lx = 0, ly = 0, drag = false, raf;
		var paint = function () { cube.style.transform = 'rotateX(' + rx + 'deg) rotateY(' + ry + 'deg)'; };
		var glide = function () {
			vx *= 0.94; vy *= 0.94; ry += vx; rx = Math.max( -60, Math.min( 60, rx - vy ) ); paint();
			if ( Math.abs( vx ) + Math.abs( vy ) > 0.05 ) raf = requestAnimationFrame( glide );
		};
		wrap.addEventListener( 'pointerdown', function ( e ) {
			var m = getComputedStyle( cube ).transform; // freeze the auto-spin where it is
			if ( m && m !== 'none' && ! cube.classList.contains( 'is-manual' ) ) {
				var v = new DOMMatrix( m ); ry = Math.atan2( -v.m31, v.m33 ) * 180 / Math.PI; rx = -18;
			}
			cube.classList.add( 'is-dragging', 'is-manual' ); paint();
			drag = true; lx = e.clientX; ly = e.clientY; vx = vy = 0; cancelAnimationFrame( raf );
			wrap.setPointerCapture( e.pointerId );
		} );
		wrap.addEventListener( 'pointermove', function ( e ) {
			if ( ! drag ) return;
			vx = ( e.clientX - lx ) * 0.6; vy = ( e.clientY - ly ) * 0.6; lx = e.clientX; ly = e.clientY;
			ry += vx; rx = Math.max( -60, Math.min( 60, rx - vy ) ); paint();
		} );
		var end = function () { if ( ! drag ) return; drag = false; cube.classList.remove( 'is-dragging' ); raf = requestAnimationFrame( glide ); };
		wrap.addEventListener( 'pointerup', end );
		wrap.addEventListener( 'pointercancel', end );
	}

	/* 3. Card tilt with a moving highlight (desktop pointers only). */
	if ( fine ) {
		$$( '.svc, .price-card, .growth-grid li, .cred, .proj, .svc-detail, .float' ).forEach( function ( el ) {
			if ( el.classList.contains( 'float' ) ) return; // floats already move with the scene
			el.setAttribute( 'data-tilt', '' );
			var max = el.classList.contains( 'svc-detail' ) ? 3 : 8;
			el.addEventListener( 'pointermove', function ( e ) {
				var r = el.getBoundingClientRect(), x = ( e.clientX - r.left ) / r.width, y = ( e.clientY - r.top ) / r.height;
				el.classList.add( 'is-tilting' );
				el.style.setProperty( '--ry', ( x - 0.5 ) * max * 2 + 'deg' );
				el.style.setProperty( '--rx', ( 0.5 - y ) * max * 2 + 'deg' );
				el.style.setProperty( '--gx', x * 100 + '%' );
				el.style.setProperty( '--gy', y * 100 + '%' );
			}, { passive: true } );
			el.addEventListener( 'pointerleave', function () {
				el.classList.remove( 'is-tilting' ); el.style.setProperty( '--rx', '0deg' ); el.style.setProperty( '--ry', '0deg' );
			} );
		} );
	}

	/* 4. Scroll-triggered depth reveal for section contents. */
	if ( ! ( 'IntersectionObserver' in window ) ) return;
	var targets = $$( '.tr-sec .sec-head, .tr-sec .row > .col, .growth-grid li, .pkg-grid > *, .svc-detail, .stats, .case-block, .result' );
	var io = new IntersectionObserver( function ( entries ) {
		entries.forEach( function ( en ) {
			if ( ! en.isIntersecting ) return;
			en.target.classList.add( 'is-in' ); io.unobserve( en.target );
		} );
	}, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 } );
	targets.forEach( function ( el, i ) {
		if ( el.getBoundingClientRect().top < window.innerHeight * 0.9 ) { el.classList.add( 'is-in' ); return; } // already visible: never hide
		if ( ! el.classList.contains( 'stats' ) ) el.classList.add( 'depth-in' );
		el.style.transitionDelay = ( i % 4 ) * 70 + 'ms';
		io.observe( el );
	} );
} )();
