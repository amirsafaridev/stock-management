<?php
/**
 * --------------------------
 * Autoload Function And Hook
 * --------------------------
 */


function stock_manager_register_style_and_scripts()
{

    //js
    wp_register_script('arta_admin_js', plugin_dir_url(__DIR__) . 'assets/js/arta_admin.js', array('jquery'), time(), true);
    wp_register_script('sw_js', "https://unpkg.com/sweetalert/dist/sweetalert.min.js", array('jquery'), time(), true);

    //css
    wp_register_style('arta_admin_css', plugin_dir_url(__DIR__) . 'assets/css/arta_admin.css', false, time(), 'all');
}

add_action('init', 'stock_manager_register_style_and_scripts');


function add_admin_scripts_stock_manager($hook)
{
    //javascript
    wp_enqueue_script('arta_admin_js');
    wp_enqueue_script('sw_js');

    //css
    wp_enqueue_style('arta_admin_css');

    //localize
    wp_localize_script('arta_admin_js', 'arta_object',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        )
    );

}

add_action('admin_enqueue_scripts', 'add_admin_scripts_stock_manager');