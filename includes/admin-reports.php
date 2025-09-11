<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function ucf7e_render_reports_page() {
    // Admin check
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You must be an admin to access this page.', 'nahian-ultimate-cf7-elementor' ) );
    }

    // Filters
    $filter_form_id = isset($_GET['ucf7_form_id']) ? intval($_GET['ucf7_form_id']) : 0;
    $filter_start   = isset($_GET['ucf7_start_date']) ? sanitize_text_field($_GET['ucf7_start_date']) : '';
    $filter_end     = isset($_GET['ucf7_end_date']) ? sanitize_text_field($_GET['ucf7_end_date']) : '';

    // Get all forms
    $forms = get_posts(['post_type'=>'wpcf7_contact_form','numberposts'=>-1]);

    // Get submissions from database
    $submissions = get_option('ucf7e_submissions', []);
    $submissions = is_array($submissions) ? $submissions : [];

    // Apply filters
    $filtered_submissions = $submissions;

    if ($filter_form_id) {
        $filtered_submissions = array_filter($filtered_submissions, fn($s) => ($s['form_id'] ?? 0) === $filter_form_id);
    }
    if ($filter_start) {
        $filtered_submissions = array_filter($filtered_submissions, fn($s) => !empty($s['submitted_at']) && date('Y-m-d', strtotime($s['submitted_at'])) >= $filter_start);
    }
    if ($filter_end) {
        $filtered_submissions = array_filter($filtered_submissions, fn($s) => !empty($s['submitted_at']) && date('Y-m-d', strtotime($s['submitted_at'])) <= $filter_end);
    }

    // Quick stats
    $total = count($filtered_submissions);
    ?>
    <div class="wrap ucf7-wrap">
        <h1><?php esc_html_e('CF7 Reports','nahian-ultimate-cf7-elementor'); ?></h1>

        <!-- Filter Form -->
        <form method="get" style="margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap;">
            <input type="hidden" name="page" value="ucf7e-reports">
            <select name="ucf7_form_id">
                <option value="0"><?php esc_html_e('All Forms','nahian-ultimate-cf7-elementor'); ?></option>
                <?php foreach($forms as $form): ?>
                    <option value="<?php echo esc_attr($form->ID); ?>" <?php selected($filter_form_id,$form->ID); ?>>
                        <?php echo esc_html($form->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="ucf7_start_date" value="<?php echo esc_attr($filter_start); ?>" placeholder="<?php esc_attr_e('Start Date','nahian-ultimate-cf7-elementor'); ?>">
            <input type="date" name="ucf7_end_date" value="<?php echo esc_attr($filter_end); ?>" placeholder="<?php esc_attr_e('End Date','nahian-ultimate-cf7-elementor'); ?>">
            <button type="submit" class="button button-primary"><?php esc_html_e('Filter','nahian-ultimate-cf7-elementor'); ?></button>
            <a href="<?php echo esc_url(admin_url('admin.php?page=ucf7e-reports')); ?>" class="button"><?php esc_html_e('Reset','nahian-ultimate-cf7-elementor'); ?></a>
        </form>

        <!-- Quick Stats -->
        <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:30px;">
            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;border-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="color:#2271b1;"><?php esc_html_e('Total Submissions','nahian-ultimate-cf7-elementor'); ?></h3>
                <p style="font-size:1.6em;"><?php echo esc_html($total); ?></p>
            </div>
        </div>

        <!-- Submissions Table -->
        <div style="background:#fff;padding:20px;border-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <h2><?php esc_html_e('Filtered Submissions','nahian-ultimate-cf7-elementor'); ?></h2>
            <table class="widefat striped" id="ucf7e-reports-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Form','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Name','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Email','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Message','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Submitted At','nahian-ultimate-cf7-elementor'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($filtered_submissions)): ?>
                        <tr><td colspan="5"><?php esc_html_e('No submissions found.','nahian-ultimate-cf7-elementor'); ?></td></tr>
                    <?php else: foreach($filtered_submissions as $s): 
                        // Name
                        $name = $s['data']['your-name'] ?? $s['data']['name'] ?? $s['data']['fullname'] ?? '';
                        if(empty($name) && !empty($s['data'])){
                            foreach($s['data'] as $k=>$v){
                                if(stripos($k,'name')!==false){ $name = is_array($v)? implode(', ', $v) : $v; break; }
                            }
                        }

                        // Email
                        $email = $s['data']['your-email'] ?? $s['data']['email'] ?? '';
                        if(empty($email) && !empty($s['data'])){
                            foreach($s['data'] as $k=>$v){
                                if(stripos($k,'mail')!==false){ $email = is_array($v)? implode(', ', $v) : $v; break; }
                            }
                        }

                        // Message
                        $message = $s['data']['your-message'] ?? $s['data']['message'] ?? $s['data']['msg'] ?? '';
                        if(empty($message) && !empty($s['data'])){
                            foreach($s['data'] as $k=>$v){
                                if(stripos($k,'msg')!==false || stripos($k,'message')!==false){ $message = is_array($v)? implode(', ', $v) : $v; break; }
                            }
                        }

                        $submitted = !empty($s['submitted_at']) ? $s['submitted_at'] : '';
                    ?>
                    <tr>
                        <td><?php echo esc_html($s['form_title'] ?? ''); ?></td>
                        <td><?php echo esc_html($name); ?></td>
                        <td><?php echo esc_html($email); ?></td>
                        <td><?php echo esc_html($message); ?></td>
                        <td><?php echo esc_html($submitted); ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
    jQuery(document).ready(function($){
        $('#ucf7e-reports-table').DataTable({
            pageLength:10,
            order:[[4,'desc']]
        });
    });
    </script>
<?php
}
