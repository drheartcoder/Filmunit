jQuery( function ( $ )
{
	checkboxToggle();
	themesflat_color_picker();
	togglePostFormatMetaBoxes();	
	Metaboxes();

	/**
	 * Show, hide a <div> based on a checkbox
	 *
	 * @return void
	 * @since 1.0
	 */
	function themesflat_color_picker(){
        if ( $().wpColorPicker ) {
            $('.flat-color-picker').wpColorPicker({
                change:function(event, ui) {
                $(this).parents(".options-control-inputs").find(".choose-color").trigger('change');
                }
            });
        }
    }

	function checkboxToggle()
	{
		$( 'body' ).on( 'change', '.checkbox-toggle input', function()
		{
			var $this = $( this ),
				$toggle = $this.closest( '.checkbox-toggle' ),
				action;
			if ( !$toggle.hasClass( 'reverse' ) )
				action = $this.is( ':checked' ) ? 'slideDown' : 'slideUp';
			else
				action = $this.is( ':checked' ) ? 'slideUp' : 'slideDown';

			$toggle.next()[action]();
		} );
		$( '.checkbox-toggle input' ).trigger( 'change' );
	}

	function Metaboxes() {
		$(".options-container-content > div").hide();
		$($(".ui-tabs-active a").attr('href')).show();
		$(".ui-tabs-nav a").on('click', function(e) {
	        e.preventDefault();
			$(".ui-tabs-nav li").removeClass('ui-tabs-active');
			$(".options-container-content > div").hide();
			$(this).parent().addClass('ui-tabs-active');
			$($(this).attr('href')).show();
		})
	}

	/**
	 * Show, hide post format meta boxes
	 *
	 * @return void
	 * @since 1.0
	 */
	function togglePostFormatMetaBoxes()
	{
		var $input = $( 'input[name=post_format]' ),
            $metaBoxes = $( '#blog-options [id^="options-control-"]' ).hide();

		// Don't show post format meta boxes for portfolio
		if ( $( '#post_type' ).val() == 'members' )
			return;

		if ( $( '#post_type' ).val() == 'food' )
			return;

		$input.change( function ()
		{
			$metaBoxes.hide();
            if ( $(this).val() == 'gallery' || $(this).val() == 'video' ) { 
                $( '[id*="options-control-' + $( this ).val()+ '"]').show();
            }
            else $('#options-control-blog_heading') .show();

		} );
		$input.filter( ':checked' ).trigger( 'change' );
	}	

	  var ImagePicker = ( function() {
        function ImagePicker( element ) {
            var self = this;

            this.root           = $( element );
            this.settingLink    = this.root.attr( 'data-customizer-link' );
            this.settingMetaLink = this.root.attr('data-meta-link');
            this.idInput        = $( 'input[data-property="id"]', this.root );
            this.thumbnailInput = $( 'input[data-property="thumbnail"]', this.root );
            this.preview        = $( '.upload-preview', this.root );
            this.selected       = {
                id: this.idInput.val(),
                thumbnail: this.thumbnailInput.val()
            };


            $( 'a.browse-media', this.root ).on( 'click', this.browse.bind( this ) );
            $( 'a.remove', this.root ).on('click', this.remove.bind( this ) );

            this.thumbnailInput.on( 'change', ( function() {
                this.preview.empty();

                if ( this.selected.thumbnail != '' ) {
                    this.root.addClass( 'has-image' );
                    this.preview.append( $( '<img />', { src: this.selected.thumbnail } ) );
                }
                else {
                    this.root.removeClass( 'has-image' );
                }

            } ).bind( this ) ).trigger( 'change' );
        };

        ImagePicker.prototype = {
            initUploader: function() {
                var self = this;
                var root = this.root;

                // Initialize the drag to upload plugin
                new wp.Uploader($.extend({
                    container: root,
                    browser:   root.find( '.upload' ),
                    dropzone:  root.find( '.upload-dropzone' ),
                    success:   function( attachment ) {
                        root.removeClass( 'has-error' );
                        self.select( attachment );
                    },
                    error: function( message ) {
                        root.addClass( 'has-error' );
                        root.find( '.options-control-message' ).remove();
                        root.find( '.options-control-inputs' ).append(
                            $( '<p />', { 'class': 'options-control-message options-control-error' } ).text( message )
                        );
                    },
                    progress: function( attachment ) {},
                    plupload:  {},
                    params:    {}
                }, {} ));
            },

            browse: function( e ) {
                var self = this, mediaManager;

                e.preventDefault();

                // Create media manager instance
                mediaManager = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: { text: 'Choose Image' },
                    multiple: true,
                    library: { type: 'image' }
                });

                // Register select event to update value
                mediaManager.on('select', function() {
                    var
                    attachment = mediaManager.state().get('selection').first();
                    self.select( attachment );
                });

                mediaManager.open();
            },

            select: function( attachment ) {
                var thumbnail = {};

                // Find the smallest thumbnail
                $.map( attachment.get( 'sizes' ), function( value ) {
                    if ( thumbnail.width === undefined || thumbnail.width > value.width )
                        thumbnail = value;
                } );

                this.selected = { id: attachment.get( 'id' ), thumbnail: thumbnail.url };
                this.idInput.val( this.selected.id ).trigger( 'change' );
                this.thumbnailInput.val( this.selected.thumbnail ).trigger( 'change' );
            },

            remove: function( e ) {
                e.preventDefault();

                this.selected = { id: '', thumbnail: '' };
                this.idInput.val( '' ).trigger( 'change' );
                this.thumbnailInput.val( '' ).trigger( 'change' );
            }
        };

        return ImagePicker;
    } )();
} );