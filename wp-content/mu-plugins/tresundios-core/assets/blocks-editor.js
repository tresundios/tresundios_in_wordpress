/* Editor registration for the Tresundios dynamic blocks: server-rendered preview, no build step. */
( function ( wp, defs ) {
	'use strict';
	var el = wp.element.createElement;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var ServerSideRender = wp.serverSideRender;

	Object.keys( defs || {} ).forEach( function ( name ) {
		var def = defs[ name ];
		wp.blocks.registerBlockType( 'tresundios/' + name, {
			apiVersion: 3,
			title: def.title,
			description: def.description,
			category: 'widgets',
			icon: 'screenoptions',
			attributes: def.attributes,
			supports: { html: false, anchor: true },
			edit: function ( props ) {
				return el( 'div', useBlockProps(), el( ServerSideRender, { block: 'tresundios/' + name, attributes: props.attributes } ) );
			},
			save: function () { return null; }
		} );
	} );
} )( window.wp, window.tresundiosBlocks );
