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

    // Handle CSV export
    if (isset($_GET['ucf7_export_csv']) && $_GET['ucf7_export_csv'] == '1') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="cf7-submissions-report.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Form','Name','Email','Message','Submitted At']);
        foreach ($filtered_submissions as $s) {
            fputcsv($output, [
                $s['form_title'] ?? '',
                $s['data']['name'] ?? '',
                $s['data']['email'] ?? '',
                $s['data']['msg'] ?? '',
                $s['submitted_at'] ?? ''
            ]);
        }
        fclose($output);
        exit;
    }

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
            <button type="submit" name="ucf7_export_csv" value="1" class="button button-secondary"><?php esc_html_e('Export CSV','nahian-ultimate-cf7-elementor'); ?></button>
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
                    <?php else: foreach($filtered_submissions as $s): ?>
                    <tr>
                        <td><?php echo esc_html($s['form_title'] ?? ''); ?></td>
                        <td><?php echo esc_html($s['data']['name'] ?? ''); ?></td>
                        <td><?php echo esc_html($s['data']['email'] ?? ''); ?></td>
                        <td><?php echo esc_html($s['data']['msg'] ?? ''); ?></td>
                        <td><?php echo esc_html($s['submitted_at'] ?? ''); ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
