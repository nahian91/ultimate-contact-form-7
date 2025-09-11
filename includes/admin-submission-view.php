<?php
if (!defined('ABSPATH')) exit;

function ucf7e_format_label($text) {
    $text = str_replace(['-', '_'], ' ', $text);
    return ucwords(strtolower($text));
}

function ucf7e_render_submission_view() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You are not allowed to access this page.', 'nahian-ultimate-cf7-elementor'));
    }

    $submissions = get_option('ucf7e_submissions', []);
    $index = isset($_GET['submission_index']) ? intval($_GET['submission_index']) : -1;

    if (!isset($submissions[$index])) {
        echo '<div class="notice notice-error"><p>' . esc_html__('Submission not found.', 'nahian-ultimate-cf7-elementor') . '</p></div>';
        return;
    }

    $s = $submissions[$index];
    $form_title   = $s['form_title'] ?? '';
    $data         = $s['data'] ?? [];
    $submitted_at = !empty($s['submitted_at']) ? strtotime($s['submitted_at']) : 0;
    $date         = $submitted_at ? date_i18n('j F Y', $submitted_at) : '';
    $time         = $submitted_at ? date_i18n('g:ia', $submitted_at) : '';
    $user_type    = !empty($s['user_id']) ? __('Registered User', 'nahian-ultimate-cf7-elementor') : __('Guest', 'nahian-ultimate-cf7-elementor');
    ?>

    <div class="wrap ucf7-wrap">
        <h1><?php echo esc_html($form_title ?: __('Untitled Form', 'nahian-ultimate-cf7-elementor')); ?> - <?php esc_html_e('Submission Details', 'nahian-ultimate-cf7-elementor'); ?></h1>

        <table class="widefat striped">
            <tbody>
                <tr>
                    <th><?php esc_html_e('User Type', 'nahian-ultimate-cf7-elementor'); ?></th>
                    <td><?php echo esc_html($user_type); ?></td>
                </tr>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $key => $value): ?>
                        <?php $label = ucf7e_format_label($key); ?>
                        <tr>
                            <th><?php echo esc_html($label); ?></th>
                            <td>
                                <?php 
                                if (is_array($value)) {
                                    // Check for uploaded files
                                    foreach ($value as $v) {
                                        echo esc_html($v) . '<br>';
                                    }
                                } else {
                                    echo esc_html($value);
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2"><?php esc_html_e('No submission data available.', 'nahian-ultimate-cf7-elementor'); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th><?php esc_html_e('Submitted At', 'nahian-ultimate-cf7-elementor'); ?></th>
                    <td><?php echo esc_html($date . ' ' . $time); ?></td>
                </tr>
            </tbody>
        </table>

        <p>
            <a href="<?php echo esc_url(add_query_arg(['page' => 'ucf7e-submissions'], admin_url('admin.php'))); ?>" class="button">
                <?php esc_html_e('Back to Submissions', 'nahian-ultimate-cf7-elementor'); ?>
            </a>
        </p>
    </div>
<?php } ?>
