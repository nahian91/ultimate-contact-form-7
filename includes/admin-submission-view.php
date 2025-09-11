<?php
if (!defined('ABSPATH')) exit;

function ucf7e_render_submission_view() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You are not allowed to access this page.','nahian-ultimate-cf7-elementor'));
    }

    // Load submissions
    $submissions = get_option('ucf7e_submissions', []);
    $index = isset($_GET['submission_index']) ? intval($_GET['submission_index']) : -1;

    if (!isset($submissions[$index])) {
        echo '<div class="notice notice-error"><p>'.esc_html__('Submission not found.','nahian-ultimate-cf7-elementor').'</p></div>';
        return;
    }

    $s = $submissions[$index];

    $form_title   = isset($s['form_title']) ? $s['form_title'] : __('Untitled Form','nahian-ultimate-cf7-elementor');
    $form_id      = isset($s['form_id']) ? intval($s['form_id']) : 0;
    $data         = isset($s['data']) && is_array($s['data']) ? $s['data'] : [];
    $submitted_at = !empty($s['submitted_at']) ? strtotime($s['submitted_at']) : 0;
    $date         = $submitted_at ? date_i18n('j F Y', $submitted_at) : '';
    $time         = $submitted_at ? date_i18n('g:ia', $submitted_at) : '';

    // 🔹 Build field-label map from CF7 form
    $field_labels = [];
    if ($form_id) {
        $form = WPCF7_ContactForm::get_instance($form_id);
        if ($form) {
            $form_markup = $form->prop('form');
            // Match <label> Label text [shortcode ...] </label>
            if (preg_match_all('/<label[^>]*>(.*?)\[(.*?)\](.*?)<\/label>/s', $form_markup, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $m) {
                    $label_text = trim(strip_tags($m[1])); // before shortcode
                    $shortcode  = $m[2];
                    // Extract field name from shortcode
                    if (preg_match('/\s([\w-]+)(\s|$)/', $shortcode, $n)) {
                        $field_name = $n[1];
                        $field_labels[$field_name] = $label_text;
                    }
                }
            }
        }
    }
    ?>

    <div class="wrap ucf7-wrap">
        <h1><?php echo esc_html($form_title); ?> - <?php esc_html_e('Submission Details','nahian-ultimate-cf7-elementor'); ?></h1>
        <table class="widefat striped">
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach($data as $key => $value): ?>
                        <tr>
                            <th>
                                <?php echo esc_html($field_labels[$key] ?? ucfirst(str_replace('_',' ',$key))); ?>
                            </th>
                            <td><?php echo esc_html(is_array($value) ? implode(', ', $value) : $value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2"><?php esc_html_e('No submission data available.','nahian-ultimate-cf7-elementor'); ?></td></tr>
                <?php endif; ?>
                <tr>
                    <th><?php esc_html_e('Submitted At','nahian-ultimate-cf7-elementor'); ?></th>
                    <td><?php echo esc_html($date . ' ' . $time); ?></td>
                </tr>
            </tbody>
        </table>
        <p>
            <a href="<?php echo esc_url(add_query_arg(['page'=>'ucf7e-submissions'], admin_url('admin.php'))); ?>" class="button">
                <?php esc_html_e('Back to Submissions','nahian-ultimate-cf7-elementor'); ?>
            </a>
        </p>
    </div>
<?php }
