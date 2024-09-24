  var flat_color_picker = function(){
        $('.flat-color-picker').wpColorPicker({
            change:function(event, ui) {
            $(this).parents(".options-control-inputs").find(".choose-color").trigger('change');
            }
        });
    }