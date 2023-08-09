<?php
add_action('admin_menu', 'stock_manager_admin_menu');
function stock_manager_admin_menu()
{
    add_menu_page(
        __('Stock Manager', 'textdomain'),
        'Stock Manager',
        'manage_options',
        'rubina_settings',
        'stock_manager_setting_callback',
        '',
        10
    );
}

function stock_manager_setting_callback()
{
    $query = new WC_Product_Query(array(
        'order' => 'DESC',
        'limit' => -1
        
    ));
    $products = $query->get_products();


    $taxonomy     = 'product_cat';
    $orderby      = 'name';
    $show_count   = 0;      // 1 for yes, 0 for no
    $pad_counts   = 0;      // 1 for yes, 0 for no
    $hierarchical = 1;      // 1 for yes, 0 for no
    $title        = '';
    $empty        = 0;

    $args = array(
        'taxonomy'     => $taxonomy,
        'orderby'      => $orderby,
        'show_count'   => $show_count,
        'pad_counts'   => $pad_counts,
        'hierarchical' => $hierarchical,
        'title_li'     => $title,
        'hide_empty'   => $empty
    );
    $all_categories = get_categories( $args );
    ?>
    <div class="wrap arta_woo_content">
        <h1>ویرایش محصولات ووکامرس</h1>
        <div class="arta_woo_table_nav" style="display: flex;align-items: center;">
            <div>
                <label for="arta_woo_table_nav_sku">sku :</label>
                <input type="text" id="arta_woo_table_nav_sku">
            </div>
            <div>
                <label for="arta_woo_table_nav_search">جستجو : </label>
                <input type="text" id="arta_woo_table_nav_search">
            </div>
            <div>
                <label for="arta_woo_table_nav_cat">دسته بندی : </label>
                <select name="arta_woo_table_nav_cat" id="arta_woo_table_nav_cat">
                    <option value="">همه</option>
                    <?php
                    foreach ($all_categories as $category){
                        echo "<option value='$category->term_id'>$category->name</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="arta_woo_table_nav_type">نوع : </label>
                <select name="arta_woo_table_nav_type" id="arta_woo_table_nav_type">
                    <option value="">همه</option>
                    <option value="simple">ساده</option>
                    <option value="variant">متغیر</option>
                </select>
            </div>
            <div>
                <label for="arta_woo_table_nav_stock">موجودی : </label>
                <select name="arta_woo_table_nav_stock" id="arta_woo_table_nav_stock">
                    <option value="">همه</option>
                    <option value="موجود">موجود</option>
                    <option value=" ">ناموجود</option>
                </select>
            </div>
        </div>
        <div style="overflow-x: auto">
            <table class="arta_woo_table striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Type</th>
                    <th>SKU</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Sale Price</th>
                    <th>Manage Stock</th>
                    <th>Stock Status</th>
                    <th>Stock</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($products as $product) {
                    $cat_str = "";
                    $cats = $product->category_ids;
                    foreach ($cats as $cat){
                        $cat_str .= $cat." ";
                    }
                    ?>
                    <tr class="arta_woo_table_main_row" id="arta_woo_table_main_row_<?php  echo $product->id?>" data-term-id="<?php echo $cat_str?>">
                        <td><?php echo $product->id ?></td>
                        <?php if ($product->is_type('variable') == false) {
                            echo "<td class='arta_product_type' data-type='simple'>محصول ساده</td>";
                        } else {
                            echo '<td class="arta_product_type" data-type="variant"><button id="parent_' . $product->id . '" type="button" class="button show_variants">محصول متغیر</button></td>';
                        } ?>
                        <td><input flag-id="change_flag_<?php echo $product->id?>" type="text" value="<?php echo $product->sku ?>" class="arta_product_sku"></td>
                        <td><input flag-id="change_flag_<?php echo $product->id?>" type="text" value="<?php echo $product->name ?>" class="arta_product_name"></td>
                        <td><input flag-id="change_flag_<?php echo $product->id?>" type="number" value="<?php echo $product->regular_price ?>" style="width: 100px" class="arta_product_price"></td>
                        <td><input flag-id="change_flag_<?php echo $product->id?>" type="number" value="<?php echo $product->sale_price ?>" style="width: 100px" class="arta_product_sale_price"></td>
                        <td><input flag-id="change_flag_<?php echo $product->id?>" type="checkbox" <?php if ($product->manage_stock == "yes") echo "checked"?> class="arta_product_manage_stock"></td>
                        <td class="arta_product_stock"><?php if ($product->manage_stock == "yes") echo "موجود"; else echo " "?></td>
                        <td><input flag-id="change_flag_<?php echo $product->id?>" style="width: 40px;padding-left: 0" type="number" value="<?php echo $product->stock_quantity?>" class="arta_product_stock_quantity"></td>
                        <input type="hidden" class="change_flag_hidden" id="change_flag_<?php echo $product->id?>" row-flag="arta_woo_table_main_row_<?php echo $product->id?>" product-id="<?php echo $product->id?>" value="off">
                    </tr>
                    <?php
                    $productId = $product->id;
                    $handle = new WC_Product_Variable($productId);
                    $variationData = $handle->get_children();
                    if (!empty($variationData)) {
                        foreach ($variationData as $variation) {

                            $single_variation = new WC_Product_Variation($variation);
                            wc_delete_product_transients($single_variation->get_id());
                            $variation_cat_str = "";
                            $variation_cats = $single_variation->get_category_ids();
                            foreach ($variation_cats as $cat){
                                $variation_cat_str .= $cat." ";
                            }
                            ?>
                            <tr class="variation_rows" id="arta_woo_table_variation_row_<?php  echo $single_variation->get_id()?>" parent-id="<?php echo 'parent_' . $single_variation->get_parent_id() ?>" style="display: none;" data-term-id="<?php echo $variation_cat_str?>">
                                <td><?php echo $single_variation->get_id() ?></td>
                                <td>variation</td>
                                <td><input flag-id="change_flag_<?php echo $single_variation->get_id()?>" type="text" value="<?php echo $single_variation->get_sku() ?>" class="arta_product_sku"></td>
                                <td><?php echo urldecode(implode(" / ", $single_variation->get_variation_attributes())); ?></td>
                                <td><input flag-id="change_flag_<?php echo $single_variation->get_id()?>" type="number" value="<?php echo $single_variation->get_regular_price() ?>" style="width: 100px" class="arta_product_price"></td>
                                <td><input flag-id="change_flag_<?php echo $single_variation->get_id()?>" type="number" value="<?php echo $single_variation->get_sale_price() ?>" style="width: 100px" class="arta_product_sale_price"></td>
                                <td><input flag-id="change_flag_<?php echo$single_variation->get_id()?>" type="checkbox" <?php if ($single_variation->get_manage_stock() == "yes") echo "checked"?> class="arta_product_manage_stock"></td>
                                <td><?php if ($single_variation->get_manage_stock() == "yes") echo "موجود"?></td>
                                <td><input flag-id="change_flag_<?php echo $single_variation->get_id()?>" style="width: 40px;padding-left: 0" type="number" value="<?php echo $single_variation->get_stock_quantity()?>" class="arta_product_stock_quantity"></td>
                                <input type="hidden"  class="change_flag_variation_hidden" id="change_flag_<?php echo $single_variation->get_id()?>" row-flag="arta_woo_table_variation_row_<?php echo $single_variation->get_id()?>" product-id="<?php echo $single_variation->get_id()?>" value="off">
                            </tr>
                            <?php
                        }
                    }
                }
                ?>

                </tbody>
            </table>

        </div>

    </div>
    <div class="clear"></div>
    <div class="submit_arta_woo" style="display: none">
        <button type="button" id="submit_arta_woo" class="submit_arta_woo_button" style="margin: 20px 0">ثبت تغییرات</button>
    </div>
    <?php
}