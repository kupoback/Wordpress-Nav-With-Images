/*global
alert, confirm, console, prompt
*/
( function ($) {
	"use strict";
	
	
	var mediaUploader;
	
	$( "[data-name=\"hidden-media\"]" ).each( function () {
		if ( this.value ) {
			var thisContainer = $(this).parents( ".field-submenu-image" );
			thisContainer.find('.image').removeClass('hidden');
			thisContainer.find('[data-name="add"]').addClass('hidden');
			thisContainer.find('[data-name="edit"]').removeClass('hidden');
			thisContainer.find('[data-name="remove"]').removeClass('hidden');
		}
	} );
	
	$( "[data-name='add'], [data-name=\"edit\"], #csnw-media-upload" ).on( "click", function (e) {
		var $this = $( this );
		e.preventDefault();
		if ( mediaUploader ) {
			mediaUploader.open();
			return;
		}
		
		mediaUploader = wp.media.frames.file_frame = wp.media( {
			title:    "Choose Media",
			button:   {
				text: "Choose Media"
			},
			multiple: false,
		} );
		
		mediaUploader.on( "select", function () {
			var attachment    = mediaUploader.state().get( "selection" ).first().toJSON(),
			    thisContainer = $this.parents( ".field-submenu-image" ),
			    dataMedia     = $( thisContainer ).find( "[data-name=\"media\"]" ),
			    dataHidden    = $( thisContainer ).find( "[data-name=\"hidden-media\"]" );
			
			dataHidden.attr( "value", attachment.id );
			
			if ( thisContainer.find( ".image" ).length !== 0 ) {
				thisContainer.find('.image').removeClass('hidden');
				thisContainer.find('[data-name="add"]').addClass('hidden');
				thisContainer.find('[data-name="edit"]').removeClass('hidden');
				thisContainer.find('[data-name="remove"]').removeClass('hidden');
				dataMedia.attr( "src", attachment.sizes.thumbnail.url );
				dataMedia.attr( "data-src", attachment.sizes.thumbnail.url );
			}
			
			// Toggle the divs
			thisContainer.children( ".no-value" ).addClass( "hide" );
			thisContainer.children( ".has-value" ).removeClass( "hide" );
		} );
		mediaUploader.open();
	} );
	
	$( "[data-name=\"remove\"]" ).on( "click", function (e) {
		var $this         = $( this ),
		    thisContainer = $this.parents( ".field-submenu-image" ),
		    dataMedia     = $( thisContainer ).find( "[data-name=\"media\"]" ),
		    dataHidden    = $( thisContainer ).find( "[data-name=\"hidden-media\"]" );
		
		e.preventDefault();
		
		if ( thisContainer.find( ".image" ).length !== 0 ) {
			dataMedia.attr( "src", "" );
			dataMedia.attr( "data-src", "" );
		}
		
		if ( thisContainer.find( ".file-name" ).length !== 0 ) {
			dataMedia.find( "span" ).text( "" );
		}
		
		dataHidden.val( "" );
		
		// Toggle the divs
		thisContainer.children( ".no-value" ).removeClass( "hide" );
		thisContainer.children( ".has-value" ).addClass( "hide" );
		
	} );
	
	
} )( jQuery );