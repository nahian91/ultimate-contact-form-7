<?php
/**
 * Plugin Name: Ultimate CF7 Integration – Elementor & Gutenberg with Analytics, Reports & Export
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

define( 'UCF7E_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

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
require_once __DIR__ . '/includes/admin-assets.php';
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

// Capture CF7 submissions
add_action('wpcf7_mail_sent', function($contact_form){
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        $data = $submission->get_posted_data(); // All form fields
        $form_id = $contact_form->id();
        $form_title = $contact_form->title();

        // Save in a custom option
        $all_submissions = get_option('ucf7e_submissions', []);
        $all_submissions[] = [
            'form_id'      => $form_id,
            'form_title'   => $form_title,
            'data'         => $data,
            'submitted_at' => current_time('mysql'),
        ];
        update_option('ucf7e_submissions', $all_submissions);
    }
});