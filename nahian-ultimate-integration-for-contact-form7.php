<?php
/**
 * Plugin Name: Ultimate Integration for Contact Form 7 – Elementor Widget
 * Plugin URI:  https://wordpress.org/plugins/nahian-ultimate-integration-for-contact-form-7-and-elementor/
 * Description: Add Contact Form 7 forms inside Elementor with a dedicated widget. Select your CF7 form from a dropdown and place it without shortcodes.
 * Version:     1.0.0
 * Author:      Abdullah Nahian
 * Author URI:  https://profiles.wordpress.org/nahian91/
 * Text Domain: nahian-ultimate-cf7-elementor
 * Domain Path: /languages
 * License:     GPLv2 or later
 * Requires Plugins: elementor, contact-form-7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =====================
   Load Text Domain
===================== */
add_action('init', function() {
    load_plugin_textdomain(
        'nahian-ultimate-cf7-elementor',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
});

/* =====================
   Admin Menu
===================== */
add_action('admin_menu', function() {

    // Top-level menu
    add_menu_page(
        __('Ultimate CF7','nahian-ultimate-cf7-elementor'),
        __('Ultimate CF7','nahian-ultimate-cf7-elementor'),
        'manage_options',
        'ucf7e-general',
        'ucf7e_render_general_page',
        'dashicons-feedback',
        55
    );

    // Submenus
    $submenus = [
        'General'       => ['slug' => 'ucf7e-general',        'callback' => 'ucf7e_render_general_page'],
        'Analytics'     => ['slug' => 'ucf7e-analytics',      'callback' => 'ucf7e_render_analytics_page'],
        'Submissions'   => ['slug' => 'ucf7e-submissions',    'callback' => 'ucf7e_render_submissions_page'],
        'Reports'       => ['slug' => 'ucf7e-reports',        'callback' => 'ucf7e_render_reports_page'],
    ];

    foreach ($submenus as $title => $info) {
        add_submenu_page(
            'ucf7e-general',
            __( $title, 'nahian-ultimate-cf7-elementor' ),
            __( $title, 'nahian-ultimate-cf7-elementor' ),
            'manage_options',
            $info['slug'],
            $info['callback']
        );
    }

    // Hidden single submission view
    add_submenu_page(
        null,
        __('Submission Details','nahian-ultimate-cf7-elementor'),
        __('Submission Details','nahian-ultimate-cf7-elementor'),
        'manage_options',
        'ucf7e-submission-view',
        'ucf7e_render_submission_view'
    );
});

/* =====================
   Include Admin Pages
===================== */
require_once __DIR__ . '/includes/admin-general.php';
require_once __DIR__ . '/includes/admin-analytics.php';
require_once __DIR__ . '/includes/admin-submissions.php';
require_once __DIR__ . '/includes/admin-submission-view.php';
require_once __DIR__ . '/includes/admin-reports.php';


// Load Elementor widget safely
add_action('elementor/init', function() {

    // Only load if Elementor and CF7 are active
    if (defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base') && class_exists('WPCF7')) {

        // Register Elementor widget
        add_action('elementor/widgets/register', function($widgets_manager) {

            // Include the widget file
            require_once plugin_dir_path(__FILE__) . 'includes/widget-contact-form-7.php';

            // Register your widget class
            if (class_exists('\Ultimate_CF7_Widget')) {
                $widgets_manager->register(new \Ultimate_CF7_Widget());
            }

        });

    } else {
        // Show admin notice if Elementor or CF7 is missing
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning"><p>';
            echo 'Ultimate CF7 Elementor Widget requires both Elementor and Contact Form 7 plugins to be active.';
            echo '</p></div>';
        });
    }

});

/* =====================
   Dummy Submissions
===================== */
function ucf7e_get_dummy_submissions() {
    $submissions = get_transient('ucf7e_dummy_submissions');
    if ($submissions !== false && is_array($submissions)) return $submissions;

    $forms = get_posts(['post_type'=>'wpcf7_contact_form','numberposts'=>-1]);
    if (empty($forms)) return [];

    $submissions = [];
    foreach ($forms as $form) {
        for ($i=1; $i<=10; $i++){
            $submissions[] = [
                'form_id'      => $form->ID,
                'form_title'   => $form->post_title,
                'submitted_at' => date('Y-m-d H:i:s', strtotime("-$i days")),
                'data'         => [
                    'name'=>"User $i",
                    'email'=>"user$i@example.com",
                    'msg'=>"This is a dummy message."
                ]
            ];
        }
    }
    set_transient('ucf7e_dummy_submissions',$submissions,12*HOUR_IN_SECONDS);
    return $submissions;
}
