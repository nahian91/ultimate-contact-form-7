<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* =====================
   Enqueue Admin Assets
===================== */
function ucf7e_enqueue_admin_assets($hook) {

    // Only load on plugin admin pages
    if( strpos($hook, 'ucf7e') === false ) return;

    // Admin CSS
    wp_enqueue_style(
        'ucf7e-admin-style',
        plugin_dir_url(__FILE__) . 'assets/css/admin-style.css',
        [],
        '1.0.0'
    );

    // Admin JS
    wp_enqueue_script(
        'ucf7e-admin-script',
        plugin_dir_url(__FILE__) . 'assets/js/admin-script.js',
        ['jquery'],
        '1.0.0',
        true
    );

    // Admin JS
    wp_enqueue_script(
        'ucf7e-admin-chart',
        plugin_dir_url(__FILE__) . 'assets/js/admin-chart.js',
        ['jquery'],
        '1.0.0',
        true
    );

}
add_action('admin_enqueue_scripts', 'ucf7e_enqueue_admin_assets');


/* =====================
   Enqueue Frontend Assets
===================== */
function ucf7e_enqueue_frontend_assets() {

    // Frontend CSS
    wp_enqueue_style(
        'ucf7e-frontend-style',
        plugin_dir_url(__FILE__) . 'assets/css/frontned-style.css',
        [],
        '1.0.0'
    );

    // Frontend JS
    wp_enqueue_script(
        'ucf7e-frontend-script',
        plugin_dir_url(__FILE__) . 'assets/js/frontned-script.js',
        ['jquery'],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'ucf7e_enqueue_frontend_assets');
