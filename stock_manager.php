<?php
/*
Plugin Name:Stock Manager
Description:  افزونه خواندن اطلاعات از ووکامرس و ویرایش محصولات
Version: 1.1
Author: Rubinadev
Author URI: http://artacode.net
License: A "Slug" license name e.g. GPL2
*/


if ( ! defined( 'STOCK_MANAGER_PLUGIN_DIR' ) ) {
    define( 'STOCK_MANAGER_PLUGIN_FILE', __FILE__ );
    define( 'STOCK_MANAGER_PLUGIN_DIR', untrailingslashit( dirname( STOCK_MANAGER_PLUGIN_FILE ) ) );
}

// Main Function And Hook
require __DIR__ . '/includes/main.php';