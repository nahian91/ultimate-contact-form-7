<?php 

/* =====================
   Admin Menu
===================== */
function ucf7e_register_admin_menu() {

    // Top-level menu (custom name)
    add_menu_page(
        __( 'Ultimate CF7', 'nahian-ultimate-cf7-elementor' ), // Top-level menu label
        __( 'Ultimate CF7', 'nahian-ultimate-cf7-elementor' ), // Menu title
        'manage_options',
        'ucf7e-general', // slug of the first page (General)
        'ucf7e_render_general_page', // first page callback
        'dashicons-feedback',
        26
    );

    // Submenus under top-level menu
    add_submenu_page(
        'ucf7e-general', // parent slug is the top-level menu slug
        __( 'General', 'nahian-ultimate-cf7-elementor' ),
        __( 'General', 'nahian-ultimate-cf7-elementor' ),
        'manage_options',
        'ucf7e-general',
        'ucf7e_render_general_page'
    );

    add_submenu_page(
        'ucf7e-general',
        __( 'Analytics', 'nahian-ultimate-cf7-elementor' ),
        __( 'Analytics', 'nahian-ultimate-cf7-elementor' ),
        'manage_options',
        'ucf7e-analytics',
        'ucf7e_render_analytics_page'
    );

    add_submenu_page(
        'ucf7e-general',
        __( 'Submissions', 'nahian-ultimate-cf7-elementor' ),
        __( 'Submissions', 'nahian-ultimate-cf7-elementor' ),
        'manage_options',
        'ucf7e-submissions',
        'ucf7e_render_submissions_page'
    );

    // Hidden single submission view
    add_submenu_page(
        null,
        __( 'Submission Details', 'nahian-ultimate-cf7-elementor' ),
        __( 'Submission Details', 'nahian-ultimate-cf7-elementor' ),
        'manage_options',
        'ucf7e-submission-view',
        'ucf7e_render_submission_view'
    );

    // Add Reports tab
    add_submenu_page(
        'ucf7e-general', // parent slug (your main menu)
        __('Reports', 'ultimate-cf7-elementor'), // page title
        __('Reports', 'ultimate-cf7-elementor'), // menu title
        'manage_options', // capability
        'ucf7e-reports', // slug
        'ucf7e_render_reports_page' // callback function
    );

}
add_action( 'admin_menu', 'ucf7e_register_admin_menu', 20 );
