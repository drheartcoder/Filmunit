( function( $ ) {
  "use strict";

    var api = wp.customize,
    doc = $(document);

    var SocialIcons = function() {
        var list = $( '.icons'),
        properties  = list.find('.item-properties'),
        hiddenInput = list.parent().find( 'input[type="hidden"]'),
        settinglink = hiddenInput.data('customize-setting-link');
        var columnIndex = function( li ) {
            var index = li.prevAll( '.item' ).length + 1;
            while( index > 5 ) index = index - 5;
            return index;
          };

      var updateOption = function() {
        var ordering = [],
          icons = {};

        list.find( '> li[data-id]' ).each( function() {
          ordering.push( $( this ).attr( 'data-id' ) );

          if ( $( this ).attr( 'data-link' ) !== undefined )
            icons[$( this ).attr( 'data-id' )] = $( this ).attr( 'data-link' );
        });

        hiddenInput.val( JSON.stringify( icons ) );

        if ( api(settinglink) )
          api.instance( settinglink ).set( JSON.stringify(icons ));
      };

      var toggleEdit = function() {
        var li = $( this ),
          liIndex = columnIndex( li ),
          liNextItems = li.nextAll( '.item' ),
          liIndexRemain = 5 - liIndex,
          rightItems = $.makeArray( liNextItems ).slice( 0, liIndexRemain );

        if ( li.hasClass( 'active' ) ) {
          confirmChange();
          return;
        }
        
        list.addClass( 'active-properties' );
        list.children().removeClass( 'active' );
        li.addClass( 'active' );

        if ( rightItems.length == 0 ) li.after( properties );
        else $( rightItems.pop() ).after( properties );

        properties.find( '.input-title' ).text( li.attr( 'data-title' ) );
        properties.find( '.input-field' ).val( li.attr( 'data-link' ) );
        properties.find( '.input-field' ).get( 0 ).focus();
      };

      var cancelEdit = function() {
        list.removeClass( 'active-properties' );
        list.children().removeClass( 'active' );
      };

      var confirmChange = function() {
        if ( properties.find( '.input-field' ).val().trim() != '' )
          list.find( 'li.active' ).attr( 'data-link', properties.find( '.input-field' ).val() );
        else
          list.find( 'li.active' ).removeAttr( 'data-link' );
        
        updateOption();
        cancelEdit();
      };
        properties.on( 'click', 'button.cancel', cancelEdit );
        properties.on( 'click', 'button.confirm', confirmChange );

        properties.on( 'keydown', 'input.input-field', function(e) {
        if ( e.keyCode == 13 ) {
          e.preventDefault();
          confirmChange();
        }

        if ( e.keyCode == 27 ) {
          e.preventDefault();
          cancelEdit();
          }
        } );

      list.on( 'click', 'li.item', toggleEdit );
  
    }

    var ImagePicker = (function(){
        function ImagePicker(element) {
            if($(element).length != 0) {
                var frame,
    metaBox = $(element), // Your meta box id here
    addImgLink = metaBox.find('a.browse-media'),
    delImgLinks = metaBox.find( 'a.remove'),
    delImgLink = metaBox.find('a.themesflat-remove-media'),
    imgContainer = metaBox.find( '.upload-preview'),
    imgIdInput = metaBox.find( '.image-value' );
    addImgLink.parent().show();
    var ids = [];

    function delimg($del_val,$array) {
        var returnedData = $.grep($array, function($value){
          return $value != $del_val;
        });
        return returnedData;
    }

    // ADD IMAGE LINK
    addImgLink.on( 'click', function( event ){
    
    event.preventDefault();
    
    // If the media frame already exists, reopen it.
    if ( frame ) {
      frame.open();
      return;
    }
    
    // Create a new media frame
    frame = wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: {
        text: 'Use this media'
      },
      multiple: true  // Set to true to allow multiple files to be selected
    });
    if (imgIdInput.val() != ''){
        ids = JSON.parse(imgIdInput.val());
    }

    // When an image is selected in the media frame...
    frame.on( 'select', function() {
      
    // Get media attachment details from the frame state
    var length = frame.state().get('selection').length;

    var images = frame.state().get("selection").models;

        for(var iii = 0; iii < length; iii++)
        {
            var image_url = images[iii].changed.sizes.thumbnail.url;
            imgContainer.append( '<li><img src="'+image_url+'" alt="" style="max-width:100%;"/><a href="#" id="'+images[iii].id+'" class="themesflat-remove-media" title="Remove"> <span class="dashicons dashicons-no-alt"></span> </a>' );
            var image_caption = images[iii].changed.caption;
            var image_title = images[iii].changed.title;
            ids.push(images[iii].id);
        }
     

    imgIdInput.val(JSON.stringify(ids) );

    // Unhide the remove image link
    delImgLink.show();
    });

    // Finally, open the modal on click
    frame.open();
  });

    // Add more image 
    metaBox.on( 'click', 'a.themesflat-remove-media',function( event ){
        event.preventDefault();
        ids = JSON.parse(imgIdInput.val());
    })
  
  // DELETE IMAGE LINK
  metaBox.on( 'click', 'a.themesflat-remove-media',function( event ){
    event.preventDefault();
    ids = JSON.parse(imgIdInput.val());
    $(this).parent().remove();
    ids = delimg($(this).attr('id'),ids);
    if ( ids.length != 0 ) {
        imgIdInput.val(JSON.stringify(ids) );
    }
    else {
        addImgLink.parent().show();
        imgIdInput.val('');
    }
  });
  delImgLinks.on( 'click', function( event ){

    event.preventDefault();

    // Clear out the preview image
    imgContainer.html( '' );

    // Un-hide the add image link
    addImgLink.parent().show();

    // Hide the delete image link
    delImgLink.hide();

    // Delete the image id from the hidden input
    imgIdInput.val( '' );

  });
            }
        }
        return ImagePicker;

    } )();

    var SingleImagePicker = (function(){
        function SingleImagePicker(element) {
            if($(element).length != 0) {
                var frame,
    metaBox = $(element), // Your meta box id here
    addImgLink = metaBox.find('a.browse-media'),
    delImgLinks = metaBox.find( 'a.remove'),
    delImgLink = metaBox.find('a.themesflat-remove-media'),
    imgContainer = metaBox.find( '.upload-preview'),
    imgIdInput = metaBox.find( '.image-value' );
    addImgLink.parent().show();
    var ids = [];

    // ADD IMAGE LINK
    addImgLink.on( 'click', function( event ){
    
    event.preventDefault();
    
    // If the media frame already exists, reopen it.
    if ( frame ) {
      frame.open();
      return;
    }
    
    // Create a new media frame
    frame = wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: {
        text: 'Use this media'
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });
    // When an image is selected in the media frame...
    frame.on( 'select', function() {
      
      // Get media attachment details from the frame state
    var length = frame.state().get('selection').length;

    var images = frame.state().get("selection").models;
    var image_url;
    for (var iii = 0; iii < length; iii++) {
        image_url = images[iii].changed.url;
        imgContainer.append( '<li><img src="'+image_url+'" alt="" style="max-width:100%;"/><a href="#" id="'+images[iii].id+'" class="themesflat-remove-media" title="Remove"> <span class="dashicons dashicons-no-alt"></span> </a>' );
        var image_caption = images[iii].changed.caption;
        var image_title = images[iii].changed.title;
    }

      // Hide the add image link
      addImgLink.parent().hide();

      imgIdInput.val(image_url);

      // Unhide the remove image link
      delImgLink.show();
    });

    // Finally, open the modal on click
    frame.open();
  });

 
  // DELETE IMAGE LINK
  metaBox.on( 'click', 'a.themesflat-remove-media',function( event ){
    event.preventDefault();
    ids = JSON.parse(imgIdInput.val());
    $(this).parent().remove();
    ids = delimg($(this).attr('id'),ids);
    if ( ids.length != 0 ) {
        imgIdInput.val(JSON.stringify(ids) );
    }
    else {
        addImgLink.parent().show();
        imgIdInput.val('');
    }
  });
  delImgLinks.on( 'click', function( event ){

    event.preventDefault();

    // Clear out the preview image
    imgContainer.html( '' );

    // Un-hide the add image link
    addImgLink.parent().show();

    // Hide the delete image link
    delImgLink.hide();

    // Delete the image id from the hidden input
    imgIdInput.val( '' );

  });
            }
        }
        return SingleImagePicker;

    } )();

  

    var Background = function() {
        var root = $('.options-control-background'),
            settingLink = root.find('#background-value').attr( 'data-customize-setting-link' );
        if ( api(settingLink) ) {
                root.on( 'change', 'input', function() {
                    api.instance( settingLink ).set( JSON.stringify({
                        color: root.find( '.background-color input' ).val(),
                        type: root.find( '.background-type input:checked' ).val(),
                        pattern: root.find( '.background-patterns input:checked' ).val(),
                        image: root.find( '.background-image input' ).val(),
                        repeat: root.find( '.background-repeat input:checked' ).val(),
                        position: root.find( '.background-position input:checked' ).val(),
                        style: root.find( '.background-style input:checked' ).val()
                    }) );
                } )
            }

        $('.background-type input[type="radio"]').on('change', function() {
                var id = $('.background-type input[type="radio"]:checked').val();
                var panel = $('.background-' + id);

                $('.background-patterns, .background-custom').removeClass('active');
                $('.background-' + id).addClass('active');
            });
    }

   var Typography = ( function() {
        function Typography( element, options ) {
        if ($(element).length != 0){
        if ($().choosen) {
        $(".select-choosen").chosen();
        }
            var root = $( element ),
                settingLink = root.find("#typography-value").attr('data-customize-setting-link');
            var data_variants = JSON.parse(root.find("#datas").attr('data_variants')),
                data_subsets = JSON.parse(root.find("#datas").attr('data_subsets'));
            root.find('.typography-font select').on('change', function() {
                var variants = $(this).find('option:selected').attr('data_variants'),
                    fontWeight = $(this).closest(element).find('.typography-style select'),
                    currentVariant = fontWeight.val();
                    fontWeight.empty();

                if (variants !== undefined) {
                    $.each(JSON.parse(variants), function(index, value) {
                        value = value.trim();
                        fontWeight.append($('<option />', { value: value }).text(
                            data_variants[value] !== undefined ? data_variants[value] : value
                        ));
                    });
                }

                fontWeight.val(currentVariant);

                var subsets = $(this).find('option:selected').attr('data_subsets'),
                    subset = $(this).closest(element).find('.typography-subsets .options-control-inputs');
                    
                    // currentVariant = subset.val();
                    subset.empty();
                    var switcher_subset;

                if (subsets !== undefined) {
                    $.each(JSON.parse(subsets), function(index, value) {
                        value = value.trim();
                        var _value = data_subsets[value] !== undefined ? data_subsets[value] : value;
                         switcher_subset = '\
                        <label class="_options-switcher-subsets">\
                            <span class="options-control-title">'+_value+'</span>\
                            <input type="checkbox" value="'+value+'" name="_options-control-typography-'+settingLink+'[subsets]">\
                            <span class="options-control-indicator">\
                                <span></span>\
                            </span>\
                        </label>';
                        subset.append(switcher_subset);
                    });
                }

            });

            var save_customize = function(a) {
                settingLink = a.find("#typography-value").attr('data-customize-setting-link');
                if (wp.customize && settingLink) {
                    var __subsets = [];
                    root.find('._options-switcher-subsets input[type="checkbox"]:checked').each(function(){
                        __subsets.push($(this).val());
                    });

                    wp.customize.instance(settingLink).set(JSON.stringify({
                        family: a.find('.typography-font select').val(),
                        size: a.find('.typography-size input').val(),
                        line_height: a.find('.typography-line_height input').val(),
                        style: a.find('.typography-style select').val(),
                        color: a.find('.typography-color .nah-color-picker').val(),
                        subsets: __subsets
                    }));
                }
            }

            root.on('change', 'select, input', function() {
                save_customize($(this).closest('.options-control-typography'));
            });

        };

        };

        return Typography;
    } )();

    var show_hide_controls = function() {
        $('.options-container-content input[type=checkbox]').each(function(){
            if ( $(this).attr('children').length > 2 ){
                if ($(this).is(':checked')) {
                    $($(this).attr('children')).show();
               }
               else {
                    $($(this).attr('children')).hide();
               }
           }
       });
        $(document).on('change','.options-container-content input[type=checkbox]',function(){
          if ($(this).is(':checked')) {
                $($(this).attr('children')).show();
           }
           else {
                $($(this).attr('children')).hide();
           }
            })
    }

    var switcher_click = function() {        

        $('.options-control-indicator').on('click', function() {            
            var class_parrent = $(this).closest('.options-control-switcher');
            if ( class_parrent.hasClass('active') ) {
                class_parrent.removeClass('active');
            } else {
                class_parrent.addClass('active');
            }
        })
    }

    var WidgetLayout = ( function() {
        function WidgetLayout( element, options ) {
        if ($(element).length != 0){

            var root = $( element ),
            settingLink = root.find('input[type="hidden"]').attr( 'data-customize-setting-link' );
            var initResize, doResize, getMaxWidth, getLayouts, serialize, parentWidth, stepWidth, increase, decrease, resizeHolder;

            initResize = function(event, ui) {
                resizeHolder = $(event.srcElement);
                parentWidth = ui.element.parent().width();
                stepWidth = Math.floor(parentWidth / 12);
            };

            getMaxWidth = function(element) {
                var prevWidth = 0;
                
                element.prevAll().each(function() {
                    prevWidth += parseInt($(this).attr('data-width'));
                });

                return (12 - prevWidth) - (element.nextAll().length * 2);
            };

            getLayouts = function() {
                var layout = [];

                $('.options-control-layouts > div', root).each(function() {
                    var columns = [];

                    $('.widgetslayout-column', this).each(function() {
                        columns.push($(this).attr('data-width'));
                    });

                    layout.push(columns);
                });

                return layout;
            };

            decrease = function(element, width, invert) {
                var currentWidth = parseInt(element.attr('data-width')),
                    isInvert = invert || false,
                    newWidth = currentWidth - width;

                if (newWidth < 2) {
                    decrease(isInvert ? element.prev() : element.next(), width, isInvert);
                }
                else {
                    element.attr('data-width', newWidth);
                    element.find('span').text(newWidth);
                }
            };

            increase = function(element, width) {
                var currentWidth = parseInt(element.attr('data-width')),
                    newWidth = currentWidth + width;

                element.attr('data-width', newWidth);
                element.find('span').text(newWidth);
            };

            doResize = function(event, ui) {
                var newWidth = Math.round(ui.size.width / stepWidth),
                    currentWidth = parseInt(ui.element.attr('data-width')),
                    maxWidth = getMaxWidth(ui.element);

                if (newWidth < 2) newWidth = 2;
                if (newWidth > maxWidth) newWidth = maxWidth;

                if (newWidth > currentWidth)
                    decrease(ui.element.next(), newWidth - currentWidth);
                else
                    increase(ui.element.next(), currentWidth - newWidth);

                ui.element.attr('data-width', newWidth);
                ui.element.removeAttr('style');
                ui.element.find('span').text(newWidth);
            };

            serialize = function() {
                var data = {
                    active: $('.options-control-inputs input[type="radio"]:checked', root).val(),
                    layout: getLayouts()
                };

                if (api(settingLink))
                    api.instance(settingLink).set(JSON.stringify(data));
            };

            $('.options-control-inputs input[type="radio"]', root).on('change', function() {
                var index = $('.options-control-inputs input[type="radio"]:checked', root).val();

                $('.options-control-layouts > div', root).removeClass('active')
                $('.options-control-layouts > div:eq(' + index + ')', root).addClass('active');

                serialize();
            });

            if ( $().resizable ) {
                $('.widgetslayout-column:not(:last-child)', root).resizable({
                    handles: 'e',
                    start: initResize,
                    resize: doResize,
                    stop: serialize
                });
            }

        };
}
        return WidgetLayout;
    } )();

  

    $(function() { 
        SocialIcons();
        Typography('.options-control-typography');
        Background();
        show_hide_controls();
        WidgetLayout('.options-control-widgets-layout');
        ImagePicker('.options-control-image-control');
        SingleImagePicker('.options-control-single-image-control');
        switcher_click();
    })

} )(jQuery );

 var functest = function() {
        alert('test');
    }