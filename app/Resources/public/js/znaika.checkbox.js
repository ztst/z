(function($){
    $.fn.setChecked = function(check) {
        return this.each(function() {
            if( jQuery(this).get(0) && jQuery(this).get(0).setChecked )
            {
                jQuery(this).get(0).setChecked(check)
            }
            else
            {
                jQuery(this).iCheck(check ? 'check' : 'uncheck');
            }
        });
    };

    $.fn.setDisabled = function(disable) {
        return this.each(function() {
            if( jQuery(this).get(0) && jQuery(this).get(0).setDisabled )
            {
                jQuery(this).get(0).setDisabled(disable);
            }
            else
            {
                jQuery(this).iCheck(disable ? 'disable' : 'enable');
            }
        });
    };

    $.fn.checkbox = function() {
        jQuery(this).iCheck({
          checkboxClass: 'icheckbox',
          radioClass: 'iradio'
        });

        jQuery(this).on('ifChecked', function() {
            jQuery(this).trigger('check');
        });

        jQuery(this).on('ifClicked', function() {
            jQuery(this).trigger('clicked');
        });

        jQuery(this).on('ifUnchecked', function() {
            jQuery(this).trigger('uncheck');
        });

        jQuery(this).on('ifChanged', function() {
            jQuery(this).trigger('changed');
        });
    }
})(jQuery);