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
        UCF7E_PLUGIN_URL . 'assets/css/admin-style.css',
        [],
        '1.0.0'
    );

    // Admin Chart JS
    wp_enqueue_script(
        'ucf7e-admin-chart',
        UCF7E_PLUGIN_URL . 'assets/js/admin-chart.js',
        ['jquery'],
        '1.0.0',
        true
    );

    // Admin CSS
    wp_enqueue_style(
        'ucf7e-admin-datatables-css',
        UCF7E_PLUGIN_URL . 'assets/css/dataTables.min.css',
        [],
        '1.0.0'
    );

     // Admin JS
    wp_enqueue_script(
        'ucf7e-admin-datatables-js',
        UCF7E_PLUGIN_URL . 'assets/js/dataTables.min.js',
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
        UCF7E_PLUGIN_URL . 'assets/css/frontned-style.css',
        [],
        '1.0.0'
    );

    // Frontend JS
    wp_enqueue_script(
        'ucf7e-frontend-script',
        UCF7E_PLUGIN_URL . 'assets/js/frontend-script.js',
        ['jquery'],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'ucf7e_enqueue_frontend_assets');
