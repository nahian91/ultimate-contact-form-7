<?php
/**
 * Plugin Name: Ultimate CF7 Integration with Analytics, Reports & Export
 * Plugin URI:  https://wordpress.org/plugins/nahian-ultimate-integration-for-contact-form-7-and-elementor/
 * Description: Add Contact Form 7 forms inside Elementor with a dedicated widget. Select your CF7 form from a dropdown and place it without shortcodes.
 * Version:     1.2
 * Author:      Abdullah Nahian
 * Author URI:  https://profiles.wordpress.org/nahian91/
 * Text Domain: nahian-ultimate-cf7-elementor
 * Domain Path: /languages
 * License:     GPLv2 or later
 * Requires Plugins: elementor, contact-form-7
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'UCF7E_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'UCF7E_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/* =====================
   Load Text Domain
===================== */
add_action( 'init', function() {
    load_plugin_textdomain(
        'nahian-ultimate-cf7-elementor',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
});

/* =====================
   Admin Menu
===================== */
add_action( 'admin_menu', function() {

    add_menu_page(
        esc_html__( 'Ultimate CF7', 'nahian-ultimate-cf7-elementor' ),
        esc_html__( 'Ultimate CF7', 'nahian-ultimate-cf7-elementor' ),
        'manage_options',
        'ucf7e-general',
        'ucf7e_render_general_page',
        'dashicons-feedback',
        55
    );

    $submenus = [
        'General'     => ['slug' => 'ucf7e-general',      'callback' => 'ucf7e_render_general_page'],
        'Analytics'   => ['slug' => 'ucf7e-analytics',    'callback' => 'ucf7e_render_analytics_page'],
        'Submissions' => ['slug' => 'ucf7e-submissions',  'callback' => 'ucf7e_render_submissions_page'],
        'Reports'     => ['slug' => 'ucf7e-reports',      'callback' => 'ucf7e_render_reports_page'],
    ];

    foreach ( $submenus as $title => $info ) {
        add_submenu_page(
            'ucf7e-general',
            esc_html__( $title, 'nahian-ultimate-cf7-elementor' ),
            esc_html__( $title, 'nahian-ultimate-cf7-elementor' ),
            'manage_options',
            $info['slug'],
            $info['callback']
        );
    }

    // Hidden single submission view
    add_submenu_page(
        null,
        esc_html__( 'Submission Details', 'nahian-ultimate-cf7-elementor' ),
        esc_html__( 'Submission Details', 'nahian-ultimate-cf7-elementor' ),
        'manage_options',
        'ucf7e-submission-view',
        'ucf7e_render_submission_view'
    );
});

/* =====================
   Include Admin Pages
===================== */
require_once UCF7E_PLUGIN_DIR . 'includes/admin-assets.php';
require_once UCF7E_PLUGIN_DIR . 'includes/admin-general.php';
require_once UCF7E_PLUGIN_DIR . 'includes/admin-analytics.php';
require_once UCF7E_PLUGIN_DIR . 'includes/admin-submissions.php';
require_once UCF7E_PLUGIN_DIR . 'includes/admin-submission-view.php';
require_once UCF7E_PLUGIN_DIR . 'includes/admin-reports.php';

/* =====================
   Load Elementor Widget
===================== */
add_action( 'elementor/init', function() {
    if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) && class_exists( 'WPCF7' ) ) {

        add_action( 'elementor/widgets/register', function( $widgets_manager ) {
            require_once UCF7E_PLUGIN_DIR . 'includes/widget-contact-form-7.php';
            if ( class_exists( '\Ultimate_CF7_Widget' ) ) {
                $widgets_manager->register( new \Ultimate_CF7_Widget() );
            }
        });

    } else {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-warning"><p>';
            echo esc_html__( 'Ultimate CF7 Elementor Widget requires both Elementor and Contact Form 7 plugins to be active.', 'nahian-ultimate-cf7-elementor' );
            echo '</p></div>';
        });
    }
});

/* =====================
   Capture CF7 Submissions
===================== */
add_action( 'wpcf7_mail_sent', function( $contact_form ) {
    $submission = WPCF7_Submission::get_instance();
    if ( $submission ) {
        $data         = $submission->get_posted_data();
        $form_id      = $contact_form->id();
        $form_title   = $contact_form->title();
        $submitted_at = current_time( 'mysql' );

        $new_submission = [
            'form_id'      => $form_id,
            'form_title'   => $form_title,
            'data'         => $data,
            'submitted_at' => $submitted_at,
        ];

        $all_submissions = get_option( 'ucf7e_submissions', [] );
        $all_submissions = is_array( $all_submissions ) ? $all_submissions : [];

        $hash_new = md5( $form_id . serialize( $data ) . $submitted_at );
        $is_duplicate = false;

        foreach ( $all_submissions as $s ) {
            $hash_existing = md5( ( $s['form_id'] ?? '' ) . serialize( $s['data'] ?? [] ) . ( $s['submitted_at'] ?? '' ) );
            if ( $hash_existing === $hash_new ) {
                $is_duplicate = true;
                break;
            }
        }

        if ( ! $is_duplicate ) {
            $all_submissions[] = $new_submission;
            update_option( 'ucf7e_submissions', $all_submissions );
        }
    }
});
