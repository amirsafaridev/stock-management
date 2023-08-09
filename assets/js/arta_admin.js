jQuery(document).ready(function () {
    jQuery('.show_variants').click(function () {
        var id = jQuery(this).attr('id');
        if (jQuery('.arta_woo_table .variation_rows[parent-id=' + id + ']').css('display') == "none") {
            jQuery('.arta_woo_table .variation_rows[parent-id=' + id + ']').show();
        } else {
            jQuery('.arta_woo_table .variation_rows[parent-id=' + id + ']').hide();
        }
    });

    jQuery('.arta_woo_table_main_row input').on('input paste change', function () {
        jQuery('.submit_arta_woo').show()
        var input_flag_id = jQuery(this).attr('flag-id');
        jQuery('#' + input_flag_id).val('on');
    });

    jQuery('.variation_rows input').on('input paste change', function () {
        jQuery('.submit_arta_woo').show()
        var input_flag_id = jQuery(this).attr('flag-id');
        jQuery('#' + input_flag_id).val('on');
    });

    jQuery('#arta_woo_table_nav_search').on('input paste', function () {
        var search = jQuery("#arta_woo_table_nav_search").val().toLowerCase();
        jQuery('.arta_woo_table tbody tr').hide();
        jQuery('.arta_woo_table tbody tr').each(function () {
           var title = jQuery(this).find('td input.arta_product_name').val();
           if (jQuery(this).find('td input.arta_product_name').length > 0){
               if (title.search(search) > -1) {
                   jQuery(this).show()
               }
           }
        });
    });

    jQuery('#arta_woo_table_nav_sku').on('input paste', function () {
        var search = jQuery("#arta_woo_table_nav_sku").val();
        jQuery('.arta_woo_table tbody tr').hide();
        jQuery('.arta_woo_table tbody tr').each(function () {
            var title = jQuery(this).find('td input.arta_product_sku').val();
            if (jQuery(this).find('td input.arta_product_sku').length > 0){
                if (title.search(search) > -1) {
                    jQuery(this).show()
                }
            }
        });
    });

    jQuery('#arta_woo_table_nav_stock').on('change', function () {
        var search = jQuery("#arta_woo_table_nav_stock").val();
        jQuery('.arta_woo_table tbody tr').hide();
        jQuery('.arta_woo_table tbody tr').each(function () {
            var title = jQuery(this).find('td.arta_product_stock').text();
            if (jQuery(this).find('td.arta_product_stock').length > 0){
                if (title.search(search) > -1) {
                    jQuery(this).show()
                }
            }
        });
    });

    jQuery('#arta_woo_table_nav_type').on('change', function () {
        var search = jQuery("#arta_woo_table_nav_type").val();
        jQuery('.arta_woo_table tbody tr').hide();
        jQuery('.arta_woo_table tbody tr').each(function () {
            var title = jQuery(this).find('td.arta_product_type').attr('data-type');
            if (jQuery(this).find('td.arta_product_type').length > 0){
                if (title.search(search) > -1) {
                    jQuery(this).show()
                }
            }
        });
    });

    jQuery('#arta_woo_table_nav_cat').on('change', function () {
        var search = jQuery("#arta_woo_table_nav_cat").val();
        jQuery('.arta_woo_table tbody tr').hide();
        jQuery('.arta_woo_table tbody tr').each(function () {
            var title = jQuery(this).attr('data-term-id');
            if (jQuery(this).length > 0){
                if (title.search(search) > -1) {
                    jQuery(this).show()
                }
            }
        });
    });

    jQuery('#submit_arta_woo').click(function () {
        var data = [];
        jQuery('.change_flag_hidden').each(function () {
            if (jQuery(this).val() == "on") {
                var row_changed_id = jQuery(this).attr('row-flag');
                var product_id = jQuery(this).attr('product-id');
                var main_sku = jQuery('#' + row_changed_id + " .arta_product_sku").val();
                var main_name = jQuery('#' + row_changed_id + " .arta_product_name").val();
                var main_price = jQuery('#' + row_changed_id + " .arta_product_price").val();
                var main_sale_price = jQuery('#' + row_changed_id + " .arta_product_sale_price").val();
                var main_manage_stock = jQuery('#' + row_changed_id + " .arta_product_manage_stock").is(':checked');
                var main_stock_quantity = jQuery('#' + row_changed_id + " .arta_product_stock_quantity").val();
                var single_data = {
                    'product_id': product_id,
                    'is_variable': false,
                    'main_sku': main_sku,
                    'main_name': main_name,
                    'main_price': main_price,
                    'main_sale_price': main_sale_price,
                    'main_manage_stock': main_manage_stock,
                    'main_stock_quantity': main_stock_quantity,
                };
                data.push(single_data);
            }
        });
        jQuery('.change_flag_variation_hidden').each(function () {
            if (jQuery(this).val() == "on") {
                var row_changed_id = jQuery(this).attr('row-flag');
                var product_id = jQuery(this).attr('product-id');
                var variant_sku = jQuery('#' + row_changed_id + " .arta_product_sku").val();
                var variant_price = jQuery('#' + row_changed_id + " .arta_product_price").val();
                var variant_sale_price = jQuery('#' + row_changed_id + " .arta_product_sale_price").val();
                var variant_manage_stock = jQuery('#' + row_changed_id + " .arta_product_manage_stock").is(':checked');
                var variant_stock_quantity = jQuery('#' + row_changed_id + " .arta_product_stock_quantity").val();
                var variant_data = {
                    'product_id': product_id,
                    'is_variable': true,
                    'main_sku': variant_sku,
                    'main_price': variant_price,
                    'main_sale_price': variant_sale_price,
                    'main_manage_stock': variant_manage_stock,
                    'main_stock_quantity': variant_stock_quantity,
                };
                data.push(variant_data);
            }
        });
        console.log(data)
        if (data.length > 0) {
            jQuery.ajax({
                type: 'post',
                url: arta_object.ajaxurl,
                data: {
                    "action": 'arta_change_products_data',
                    "data": data,
                },
                beforeSend: function () {
                    jQuery("#submit_arta_woo").prop('disabled', true);
                    jQuery("#submit_arta_woo").text('در حال ذخیره سازی');
                    jQuery(".arta_woo_content").css('opacity','0.4');
                    jQuery("#submit_arta_woo").css('background','#838383');
                },
                success: function (res) {
                
                //    swal("بسیار عالی!", "محصولات به روز رسانی شد!", "success");
                    jQuery('.change_flag_variation_hidden').each(function (){
                        jQuery(this).val('off')
                    });
                    jQuery('.change_flag_hidden').each(function (){
                        jQuery(this).val('off')
                    });

                },
                complete: function () {
                    jQuery("#submit_arta_woo").prop('disabled', false);
                    jQuery("#submit_arta_woo").text('ثبت تغییرات');
                    jQuery(".arta_woo_content").css('opacity','1');
                    jQuery("#submit_arta_woo").css('background','#ff8c00');
                }  
            });
        }else {
            swal("تغییری انجام نشد!", "", "warning");
        }

    });


});