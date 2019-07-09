var thlm_settings_product = (function($, window, document) {
   /*------------------------------------
    *---- ON-LOAD FUNCTIONS - SATRT -----
    *------------------------------------*/
    $(function() {

        $("form.cart").submit(function(e) {

            e.preventDefault();

            var product = $(document.activeElement).val();
            var $thisbutton = $(document.activeElement);

            var formData = new FormData(this);

            formData.append("action", "themehigh_ajax_add_to_cart");
            formData.append("product_id", product);

            var notice_wrapper = th_var.notice_wrapper;

            $(document.body).trigger('adding_to_cart', [$thisbutton, formData]);

            $.ajax({
                url: th_var.ajaxurl,
                //url: woocommerce_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                type: 'POST',
                data: formData,
                success: function (data) {
                    console.log(data);
                    var notice = data.notices;
                    var mini_cart = data.mini_cart;
                    if(mini_cart){
                        $(document.body).trigger('added_to_cart', [mini_cart.fragments, mini_cart.cart_hash, $thisbutton]);
                    }
                    if(notice){
                        $(notice_wrapper +":first").html(notice);
                        smooth_scroll('#th-wooajax-notice-pointer');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });


        function smooth_scroll(target){
            $('html, body').animate({
                scrollTop: $(target).offset().top-40
            }, 600);
        }

    });
   /*------------------------------------
    *---- ON-LOAD FUNCTIONS - END -----
    *------------------------------------*/

    // function show_if_override_product_license_settings(elm, loop){
    //    var override = elm.is(':checked');
    //
    //    if(override){
    //       $('#thlm_override_product_license_settings_panel_'+loop).show();
    //    }else{
    //       $('#thlm_override_product_license_settings_panel_'+loop).hide();
    //    }
    // }

    return {
        //show_if_override_product_license_settings : show_if_override_product_license_settings,
    };
}(window.jQuery, window, document));

function thlm_show_if_override_product_license_settings(elm, loop){
    //thlm_settings_product.show_if_override_product_license_settings($(elm), loop);
}
