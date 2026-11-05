/* Enquiry form: submit without a page reload, prefill from pricing CTAs. The form also works without JS. */
( function () {
	'use strict';
	var form = document.querySelector( 'form[data-enquiry]' );
	if ( ! form ) return;
	var out = form.querySelector( '[data-enquiry-out]' );
	var btn = form.querySelector( '[type="submit"]' );

	function show( text, ok ) {
		out.hidden = false;
		out.textContent = text;
		out.className = 'note' + ( ok ? ' is-ok' : ' is-error' );
	}

	form.addEventListener( 'submit', function ( e ) {
		e.preventDefault();
		var d = new FormData( form );
		if ( ! String( d.get( 'name' ) || '' ).trim() || ! String( d.get( 'contact' ) || '' ).trim() ) {
			show( form.dataset.msgRequired, false );
			return;
		}
		btn.disabled = true;
		show( form.dataset.msgSending, true );
		fetch( form.action, { method: 'POST', body: d, headers: { Accept: 'application/json' }, credentials: 'same-origin' } )
			.then( function ( r ) { return r.json(); } )
			.then( function ( j ) {
				show( j.message, j.ok );
				if ( j.ok ) {
					/* GA4 / GTM hook (see themes/tresundios-in/docs/ANALYTICS.md). Only pushes to an existing dataLayer; loads nothing. */
					var svcField = form.querySelector( '[name="svc"]' );
					if ( window.dataLayer && window.dataLayer.push ) {
						window.dataLayer.push( { event: 'generate_lead', form_id: 'enquiry', service: svcField ? svcField.value : '' } );
					} else if ( typeof window.gtag === 'function' ) {
						window.gtag( 'event', 'generate_lead', { form_id: 'enquiry', service: svcField ? svcField.value : '' } );
					}
					form.reset();
				}
			} )
			.catch( function () { show( form.dataset.msgFailed, false ); } )
			.then( function () { btn.disabled = false; } );
	} );

	/* Pricing "Get a quote" links preselect the service and mention the plan. */
	document.addEventListener( 'click', function ( e ) {
		var a = e.target.closest( '[data-plan]' );
		if ( ! a ) return;
		e.preventDefault();
		form.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		var svc = form.querySelector( '[name="svc"]' ), msg = form.querySelector( '[name="msg"]' );
		if ( svc && a.dataset.service ) {
			var map = {};
			try { map = JSON.parse( form.dataset.svcAliases || '{}' ); } catch ( err ) {}
			svc.value = map[ a.dataset.service ] || a.dataset.service;
		}
		if ( msg && ( ! msg.value.trim() || msg.dataset.prefill === msg.value ) ) {
			msg.value = form.dataset.msgPlan.replace( '%s', a.dataset.plan );
			msg.dataset.prefill = msg.value;
		}
		form.querySelector( '[name="name"]' ).focus( { preventScroll: true } );
	} );
} )();
