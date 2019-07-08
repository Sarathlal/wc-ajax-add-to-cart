var thlm_settings_product = (function($, window, document) {
   /*------------------------------------
    *---- ON-LOAD FUNCTIONS - SATRT -----
    *------------------------------------*/
    $(function() {



        $("form.cart").submit(function(e) {

            e.preventDefault();

            var product = $(document.activeElement).val();
            //console.log($(document.activeElement).val());

            var formData = new FormData(this);

            //console.log(formData);

            formData.append("action", "themehigh_ajax_add_to_cart");
            formData.append("product", product);

            $.ajax({
                url: window.location.pathname,
                type: 'POST',
                data: formData,
                success: function (data) {
                    alert(data)
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });






        $("button[name='add-to-cart-ppppp']").click(function(event){
            event.preventDefault();
            var form = $(this).closest("form");

            $(form).submit(function(e) {

            });

            //event.preventDefault();
            //alert("The paragraph was clicked.");
            var product = $(this).val();
            var form = $(this).closest("form");

            //var formData = $(form).serializeArray();
            var formData = new FormData(form);

            //formData.push({name: 'action', value: 'themehigh_ajax_add_to_cart'});
            //formData.push({name: 'product', value: product });

            console.log(formData);


        });


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
