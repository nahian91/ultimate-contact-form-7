<?php 

/* =====================
   Enqueue Assets
===================== */
function ucf7e_enqueue_assets() {
    wp_enqueue_style(
        'ucf7e-styles',
        plugin_dir_url(__FILE__).'assets/css/ucf7e-style.css',
        [],
        '1.0.0'
    );

    wp_enqueue_script(
        'ucf7e-scripts',
        plugin_dir_url(__FILE__).'assets/js/ucf7e-script.js',
        ['jquery'],
        '1.0.0',
        true
    );
}
add_action('admin_enqueue_scripts','ucf7e_enqueue_assets');