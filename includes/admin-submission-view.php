<?php
if (!defined('ABSPATH')) exit;

function ucf7e_render_submission_view() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You are not allowed to access this page.', 'nahian-ultimate-cf7-elementor'));
    }

    // Get the submission ID from the URL
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if (!$id) {
        echo '<div class="notice notice-error"><p>' . esc_html__('Invalid submission ID.', 'nahian-ultimate-cf7-elementor') . '</p></div>';
        return;
    }

    // Load submissions
    $submissions = get_option('ucf7e_submissions', []);
    $submissions = is_array($submissions) ? $submissions : [];

    // Find the submission by ID
    $submission = null;
    foreach ($submissions as $s) {
        if (($s['id'] ?? 0) == $id) {
            $submission = $s;
            break;
        }
    }

    if (!$submission) {
        echo '<div class="notice notice-error"><p>' . esc_html__('Submission not found.', 'nahian-ultimate-cf7-elementor') . '</p></div>';
        return;
    }

    $form_title   = $submission['form_title'] ?? '';
    $data         = is_array($submission['data'] ?? null) ? $submission['data'] : [];
    $submitted_at = !empty($submission['submitted_at']) ? strtotime($submission['submitted_at']) : 0;
    $date         = $submitted_at ? date_i18n('j F Y', $submitted_at) : '';
    $time         = $submitted_at ? date_i18n('g:ia', $submitted_at) : '';

    // Format labels
    function ucf7e_format_label($text) {
        $text = str_replace(['-', '_'], ' ', $text);
        return ucwords(strtolower($text));
    }
    ?>
    <div class="wrap ucf7-wrap">
        <h1>
            <?php echo esc_html($form_title ?: __('Untitled Form', 'nahian-ultimate-cf7-elementor')); ?>
            - <?php esc_html_e('Submission Details', 'nahian-ultimate-cf7-elementor'); ?>
        </h1>

        <table class="widefat striped">
            <tbody>
                <tr>
                    <th><?php esc_html_e('Submission ID', 'nahian-ultimate-cf7-elementor'); ?></th>
                    <td><?php echo esc_html($id); ?></td>
                </tr>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $key => $value): ?>
                        <tr>
                            <th><?php echo esc_html(ucf7e_format_label($key)); ?></th>
                            <td><?php echo esc_html(is_array($value) ? implode(', ', $value) : $value); ?></td>
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
            <a href="<?php echo esc_url(admin_url('admin.php?page=ucf7e-submissions')); ?>" class="button">
                <?php esc_html_e('Back to Submissions', 'nahian-ultimate-cf7-elementor'); ?>
            </a>
        </p>
    </div>
<?php } ?>
