<?php
if (!defined('ABSPATH')) exit;

function ucf7e_render_submissions_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You are not allowed to access this page.','nahian-ultimate-cf7-elementor'));
    }

    // Load real submissions
    $submissions = get_option('ucf7e_submissions', []);
    $submissions = is_array($submissions) ? $submissions : [];

    // Handle Bulk Delete
    if (isset($_POST['ucf7e_bulk_action']) && $_POST['ucf7e_bulk_action'] === 'delete') {
        if (!empty($_POST['submission_ids'])) {
            foreach ($_POST['submission_ids'] as $idx) {
                unset($submissions[intval($idx)]);
            }
            $submissions = array_values($submissions); // reindex
            update_option('ucf7e_submissions', $submissions);
            echo '<div class="notice notice-success"><p>'.esc_html__('Selected submissions deleted','nahian-ultimate-cf7-elementor').'</p></div>';
        }
    }

    // Filters
    $form_filter = $_GET['form_filter'] ?? '';
    $month_filter = $_GET['month_filter'] ?? '';
    $user_type_filter = $_GET['user_type_filter'] ?? '';
    $email_search = $_GET['email_search'] ?? '';

    $filtered_submissions = array_filter($submissions, function($s) use ($form_filter,$month_filter,$user_type_filter,$email_search){
        $pass = true;
        if ($form_filter && isset($s['form_id'])) $pass = $pass && ($s['form_id'] == $form_filter);
        if ($month_filter && !empty($s['submitted_at'])) {
            $month = date('Y-m', strtotime($s['submitted_at']));
            $pass = $pass && ($month == $month_filter);
        }
        if ($user_type_filter === 'guest') $pass = $pass && (($s['user_id'] ?? 0) == 0);
        if ($user_type_filter === 'user') $pass = $pass && (($s['user_id'] ?? 0) != 0);
        if ($email_search) $pass = $pass && (strpos(strtolower($s['data']['email'] ?? ''), strtolower($email_search)) !== false);
        return $pass;
    });

    // Generate Month Options
    $months = [];
    foreach ($submissions as $s) {
        if (!empty($s['submitted_at'])) {
            $m = date('Y-m', strtotime($s['submitted_at']));
            $months[$m] = date_i18n('F Y', strtotime($s['submitted_at']));
        }
    }

    // Form Options
    $forms = [];
    foreach ($submissions as $s) {
        if (!empty($s['form_id'])) $forms[$s['form_id']] = $s['form_title'] ?? '';
    }
    ?>
    <div class="wrap ucf7-wrap">
        <h1><?php esc_html_e('CF7 Submissions','nahian-ultimate-cf7-elementor'); ?></h1>

        <!-- Filters -->
        <form method="get" style="margin-bottom:20px;">
            <input type="hidden" name="page" value="ucf7e-submissions">
            <select name="form_filter">
                <option value=""><?php esc_html_e('All Forms','nahian-ultimate-cf7-elementor'); ?></option>
                <?php foreach($forms as $fid => $ftitle): ?>
                    <option value="<?php echo esc_attr($fid); ?>" <?php selected($form_filter,$fid); ?>><?php echo esc_html($ftitle); ?></option>
                <?php endforeach; ?>
            </select>

            <select name="month_filter">
                <option value=""><?php esc_html_e('All Months','nahian-ultimate-cf7-elementor'); ?></option>
                <?php foreach($months as $mval => $mname): ?>
                    <option value="<?php echo esc_attr($mval); ?>" <?php selected($month_filter,$mval); ?>><?php echo esc_html($mname); ?></option>
                <?php endforeach; ?>
            </select>

            <select name="user_type_filter">
                <option value=""><?php esc_html_e('All Users','nahian-ultimate-cf7-elementor'); ?></option>
                <option value="guest" <?php selected($user_type_filter,'guest'); ?>><?php esc_html_e('Guest','nahian-ultimate-cf7-elementor'); ?></option>
                <option value="user" <?php selected($user_type_filter,'user'); ?>><?php esc_html_e('Registered','nahian-ultimate-cf7-elementor'); ?></option>
            </select>

            <input type="text" name="email_search" value="<?php echo esc_attr($email_search); ?>" placeholder="<?php esc_attr_e('Search Email','nahian-ultimate-cf7-elementor'); ?>">
            <button type="submit" class="button"><?php esc_html_e('Filter','nahian-ultimate-cf7-elementor'); ?></button>
        </form>

        <form method="post">
            <input type="hidden" name="ucf7e_bulk_action" value="delete">
            <table class="widefat striped" id="ucf7e-submissions-datatable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="ucf7e_select_all"></th>
                        <th><?php esc_html_e('S/N','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Form','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Name','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Email','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Date','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Time','nahian-ultimate-cf7-elementor'); ?></th>
                        <th><?php esc_html_e('Actions','nahian-ultimate-cf7-elementor'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($filtered_submissions)): ?>
                        <tr>
                            <td colspan="8"><?php esc_html_e('No submissions found.','nahian-ultimate-cf7-elementor'); ?></td>
                        </tr>
                    <?php else: foreach($filtered_submissions as $idx=>$s):
                        $form_title = $s['form_title'] ?? '-';
                        $name = $s['data']['name'] ?? '-';
                        $email = $s['data']['email'] ?? '-';
                        $submitted = !empty($s['submitted_at']) ? strtotime($s['submitted_at']) : 0;
                        $date = $submitted ? date_i18n('j F Y', $submitted) : '-';
                        $time = $submitted ? date_i18n('g:ia', $submitted) : '-';
                    ?>
                    <tr>
                        <td><input type="checkbox" name="submission_ids[]" value="<?php echo esc_attr($idx); ?>"></td>
                        <td><?php echo esc_html($idx+1); ?></td>
                        <td><?php echo esc_html($form_title); ?></td>
                        <td><?php echo esc_html($name); ?></td>
                        <td><?php echo esc_html($email); ?></td>
                        <td><?php echo esc_html($date); ?></td>
                        <td><?php echo esc_html($time); ?></td>
                        <td>
                            <a href="<?php echo esc_url(add_query_arg(['page'=>'ucf7e-submission-view','submission_index'=>$idx], admin_url('admin.php'))); ?>" class="button button-primary"><?php esc_html_e('View','nahian-ultimate-cf7-elementor'); ?></a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
            <button type="submit" class="button button-secondary" style="margin-top:10px;"><?php esc_html_e('Delete Selected','nahian-ultimate-cf7-elementor'); ?></button>
        </form>
    </div>
    <script>
    jQuery(document).ready(function($){
        $('#ucf7e-submissions-datatable').DataTable({
            pageLength: 10,
            order:[[5,'desc']],
            responsive: true
        });

        // Select All Checkbox
        $('#ucf7e_select_all').on('click', function(){
            $('input[name="submission_ids[]"]').prop('checked', this.checked);
        });
    });
    </script>
<?php
}
