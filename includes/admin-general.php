<?php
if (!defined('ABSPATH')) exit;

/**
 * Hook into CF7 submission to save it in plugin option
 */
add_action('wpcf7_mail_sent', function($contact_form) {
    $submissions = get_option('ucf7e_submissions', []);
    $submission_data = WPCF7_Submission::get_instance();
    
    $submission = [
        'form_id'      => $contact_form->id(),
        'form_title'   => $contact_form->title(),
        'submitted_at' => current_time('mysql'),
        'user_id'      => get_current_user_id(),
        'data'         => $submission_data ? $submission_data->get_posted_data() : [],
    ];

    $submissions[] = $submission;
    update_option('ucf7e_submissions', $submissions);
});

/**
 * Render General Page with statistics
 */
function ucf7e_render_general_page() {

    // Handle deletion if form submitted
    if (isset($_POST['ucf7e_delete_form']) && check_admin_referer('ucf7e_delete_form_action', 'ucf7e_delete_form_nonce')) {
        $delete_id = intval($_POST['ucf7e_delete_form']);
        if (get_post_type($delete_id) === 'wpcf7_contact_form') {
            wp_delete_post($delete_id, true);
            echo '<div class="notice notice-success"><p>'.esc_html__('Form deleted successfully','ultimate-cf7-elementor').'</p></div>';
        }
    }

    // Get all CF7 forms
    $forms = get_posts([
        'post_type' => 'wpcf7_contact_form',
        'numberposts' => -1,
    ]);

    // Get all submissions
    $all_submissions = get_option('ucf7e_submissions', []);

    ?>
    <div class="wrap ucf7e-wrap">
        <h4><?php esc_html_e('General Information', 'ultimate-cf7-elementor'); ?></h4>

        <div class="ucf7e-general">
            <div class="ucf7e-general-content">
                <div class="ucf7e-banner"></div>

                <p><?php esc_html_e('Welcome to the Ultimate CF7 – Elementor plugin! Easily embed Contact Form 7 forms inside Elementor with a dedicated widget.', 'ultimate-cf7-elementor'); ?></p>
                <p><?php esc_html_e('Use this page to view your forms, manage general settings, and track statistics for each form.', 'ultimate-cf7-elementor'); ?></p>
                <p><?php esc_html_e('For any support or documentation, please contact us directly.', 'ultimate-cf7-elementor'); ?></p>
                <p><?php esc_html_e('Thank you for choosing Ultimate CF7 – Elementor!', 'ultimate-cf7-elementor'); ?></p>

                <!-- Review Button -->
                <a href="https://wordpress.org/support/view/plugin-reviews/ultimate-cf7-elementor" class="button button-primary" target="_blank">
                    <?php esc_html_e('Leave a Review', 'ultimate-cf7-elementor'); ?>
                </a>
            </div>

            <div class="ucf7e-general-support">
                <div class="form-statistics">
                    <h4><?php esc_html_e('Form Statistics', 'ultimate-cf7-elementor'); ?></h4>
                    <table class="form-table widefat striped">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Form Name', 'ultimate-cf7-elementor'); ?></th>
                                <th><?php esc_html_e('Total Submissions', 'ultimate-cf7-elementor'); ?></th>
                                <th><?php esc_html_e('Last Submission', 'ultimate-cf7-elementor'); ?></th>
                                <th><?php esc_html_e('Action', 'ultimate-cf7-elementor'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($forms as $form): 
                                $form_submissions = array_filter($all_submissions, fn($s) => ($s['form_id'] ?? 0) === $form->ID);
                                $count = count($form_submissions);
                                $last_submission = !empty($form_submissions) ? max(array_column($form_submissions, 'submitted_at')) : '-';
                                $last_submission_formatted = $last_submission !== '-' ? date('j F Y, g:i a', strtotime($last_submission)) : '-';
                                $edit_link = admin_url('admin.php?page=wpcf7&post=' . $form->ID . '&action=edit');
                            ?>
                                <tr>
                                    <td><?php echo esc_html($form->post_title); ?></td>
                                    <td><?php echo esc_html($count); ?></td>
                                    <td><?php echo esc_html($last_submission_formatted); ?></td>
                                    <td>
                                        <a href="<?php echo esc_url($edit_link); ?>" target="_blank" class="button button-primary"><?php esc_html_e('Edit', 'ultimate-cf7-elementor'); ?></a>
                                        <form method="post" style="display:inline;">
                                            <?php wp_nonce_field('ucf7e_delete_form_action', 'ucf7e_delete_form_nonce'); ?>
                                            <input type="hidden" name="ucf7e_delete_form" value="<?php echo esc_attr($form->ID); ?>">
                                            <button type="submit" class="button button-secondary" onclick="return confirm('<?php esc_html_e('Are you sure you want to delete this form?', 'ultimate-cf7-elementor'); ?>')">
                                                <?php esc_html_e('Delete', 'ultimate-cf7-elementor'); ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <br>

                <!-- Support Info -->
                <div class="single-ucf7e-support">
                    <ul>
                        <li>
                            <span class="dashicons dashicons-email das-icons"></span>
                            <div id="email-text">support@yourdomain.com</div>
                            <button class="copy-btn" onclick="copyToClipboard('#email-text')"><span class="dashicons dashicons-admin-page"></span></button>
                        </li>
                        <li>
                            <span class="dashicons dashicons-admin-site das-icons"></span>
                            <div id="website-text">www.yourdomain.com</div>
                            <button class="copy-btn" onclick="copyToClipboard('#website-text')"><span class="dashicons dashicons-admin-page"></span></button>
                        </li>
                        <li>
                            <span class="dashicons dashicons-phone das-icons"></span>
                            <div id="phone-text">+880123456789</div>
                            <button class="copy-btn" onclick="copyToClipboard('#phone-text')"><span class="dashicons dashicons-admin-page"></span></button>
                        </li>
                    </ul>

                    <script>
                        function copyToClipboard(elementId) {
                            var text = document.querySelector(elementId).textContent;
                            var tempInput = document.createElement('input');
                            document.body.appendChild(tempInput);
                            tempInput.value = text;
                            tempInput.select();
                            document.execCommand('copy');
                            document.body.removeChild(tempInput);
                            alert('Copied: ' + text);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>

    <style>
        .ucf7e-wrap { display: flex; flex-direction: column; gap: 20px; }
        .ucf7e-general { display: flex; gap: 30px; flex-wrap: wrap; }
        .ucf7e-general-content { flex: 1 1 45%; }
        .ucf7e-general-support { flex: 1 1 50%; }
        .form-statistics table { width: 100%; }
        .single-ucf7e-support ul { list-style: none; padding: 0; }
        .single-ucf7e-support li { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
        .copy-btn { cursor: pointer; background: #2271b1; color: #fff; border: none; padding: 2px 8px; border-radius: 3px; }
    </style>
<?php
}
