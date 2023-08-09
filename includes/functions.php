<?php
add_action('wp_ajax_arta_change_products_data', 'arta_change_products_data');
add_action('wp_ajax_nopriv_arta_change_products_data', 'arta_change_products_data');

/**
 * @throws WC_Data_Exception
 */
function arta_change_products_data()
{
    $data = $_POST['data'];

    if (!empty($data)) {
        foreach ($data as $value => $pr_item) {

            // var_dump($pr_item);
            $product_id = $pr_item['product_id'];
        //var_dump($product_id);
            $is_variable = $pr_item['is_variable'];
            $main_sku = $pr_item['main_sku'];
            $main_name = $pr_item['main_name'] ?? "";
            $main_price = $pr_item['main_price'];
            $main_sale_price = $pr_item['main_sale_price'];
            $main_manage_stock = $pr_item['main_manage_stock'];
            $main_stock_quantity = $pr_item['main_stock_quantity'];
            $product = wc_get_product($product_id);
            if (!empty($main_name)) {
                $product->set_name($main_name);
            }
            $product->set_sku($main_sku);
            $product->set_manage_stock($main_manage_stock);
            $product->set_stock_quantity($main_stock_quantity);
            if ($is_variable != true) {
                $product->set_regular_price($main_price);
                $product->set_sale_price($main_sale_price);
            } else {

                update_post_meta($product_id, '_regular_price', $main_price);
                update_post_meta($product_id, '_sale_price',$main_sale_price);
                wc_delete_product_transients($product_id);
            }

            $product->save();

        }
    }
    exit();
}