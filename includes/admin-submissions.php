<?php
if ( ! defined('ABSPATH') ) exit;

function ucf7e_render_submissions_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You are not allowed to access this page.','nahian-ultimate-cf7-elementor'));
    }

    $submissions = get_option('ucf7e_submissions', []);

    // Handle Bulk Delete
    if (isset($_POST['ucf7e_bulk_delete']) && !empty($_POST['submissions'])) {
        check_admin_referer('ucf7e_bulk_action','ucf7e_nonce');
        $ids_to_delete = $_POST['submissions'];
        foreach ($ids_to_delete as $id) {
            if (isset($submissions[$id])) {
                unset($submissions[$id]);
            }
        }
        update_option('ucf7e_submissions', $submissions);
        echo '<div class="updated"><p>'.__('Selected submissions deleted.','nahian-ultimate-cf7-elementor').'</p></div>';
    }

    // Handle Clear All
    if (isset($_POST['ucf7e_clear_all'])) {
        check_admin_referer('ucf7e_clear_all_action','ucf7e_clear_all_nonce');
        delete_option('ucf7e_submissions');
        $submissions = [];
        echo '<div class="updated"><p>'.__('All submissions cleared.','nahian-ultimate-cf7-elementor').'</p></div>';
    }

    // Filters
    $selected_form_id = isset($_GET['form_id']) ? sanitize_text_field($_GET['form_id']) : '';
    $search_term      = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    $filtered_submissions = $submissions;

    // Filter by form id
    if ($selected_form_id) {
        $filtered_submissions = array_filter($filtered_submissions, function($s) use ($selected_form_id) {
            return $s['form_id'] == $selected_form_id;
        });
    }

    // Search filter
    if ($search_term) {
        $filtered_submissions = array_filter($filtered_submissions, function($s) use ($search_term) {
            return stripos(json_encode($s['data']), $search_term) !== false;
        });
    }

    // ✅ Sort submissions by submitted_at (latest first)
    usort($filtered_submissions, function($a, $b) {
        $timeA = !empty($a['submitted_at']) ? strtotime($a['submitted_at']) : 0;
        $timeB = !empty($b['submitted_at']) ? strtotime($b['submitted_at']) : 0;
        return $timeB <=> $timeA; // Descending
    });

    ?>
    <div class="wrap">
        <h1><?php _e('Form Submissions','nahian-ultimate-cf7-elementor'); ?></h1>

        <form method="get">
            <input type="hidden" name="page" value="ucf7e-submissions" />
            <input type="text" name="s" value="<?php echo esc_attr($search_term); ?>" placeholder="<?php _e('Search...','nahian-ultimate-cf7-elementor'); ?>" />
            <select name="form_id">
                <option value=""><?php _e('All Forms','nahian-ultimate-cf7-elementor'); ?></option>
                <?php
                $forms = array_unique(array_column($submissions, 'form_title','form_id'));
                foreach($forms as $fid => $title) {
                    echo '<option value="'.esc_attr($fid).'" '.selected($selected_form_id,$fid,false).'>'.esc_html($title).'</option>';
                }
                ?>
            </select>
            <button class="button"><?php _e('Filter','nahian-ultimate-cf7-elementor'); ?></button>
        </form>

        <form method="post">
            <?php wp_nonce_field('ucf7e_bulk_action','ucf7e_nonce'); ?>
            <table class="widefat striped">
    <thead><tr>
        <td><input type="checkbox" id="select-all"></td>
        <th><?php _e('S/N'); ?></th>
        <th><?php _e('Form'); ?></th>
        <th><?php _e('Email'); ?></th>
        <th><?php _e('Date'); ?></th>
        <th><?php _e('Time'); ?></th>
        <th><?php _e('Actions'); ?></th>
    </tr></thead>
    <tbody>
    <?php if(!$filtered): ?>
        <tr><td colspan="7"><?php _e('No submissions found.'); ?></td></tr>
    <?php else: $sn=1; foreach($filtered as $id=>$s): 
        $timestamp = !empty($s['submitted_at']) ? strtotime($s['submitted_at']) : 0;
        $date = $timestamp ? date_i18n('j F Y', $timestamp) : '-';
        $time = $timestamp ? date_i18n('g:ia', $timestamp) : '-';
    ?>
        <tr>
            <td><input type="checkbox" name="submissions[]" value="<?php echo esc_attr($id); ?>"></td>
            <td><?php echo $sn++; ?></td>
            <td><?php echo esc_html($s['form_title']); ?></td>
            <td><?php echo esc_html($s['data']['your-email'] ?? __('(no email)')); ?></td>
            <td><?php echo esc_html($date); ?></td>
            <td><?php echo esc_html($time); ?></td>
            <td><a href="<?php echo admin_url('admin.php?page=ucf7e-submission-view&id='.$id); ?>"><?php _e('View'); ?></a></td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>


            <p>
                <input type="submit" name="ucf7e_bulk_delete" class="button button-danger" value="<?php _e('Delete Selected','nahian-ultimate-cf7-elementor'); ?>" />
            </p>
        </form>

        <form method="post" onsubmit="return confirm('<?php _e('Are you sure you want to clear all submissions?','nahian-ultimate-cf7-elementor'); ?>');">
            <?php wp_nonce_field('ucf7e_clear_all_action','ucf7e_clear_all_nonce'); ?>
            <input type="submit" name="ucf7e_clear_all" class="button button-danger" value="<?php _e('Clear All Submissions','nahian-ultimate-cf7-elementor'); ?>" />
        </form>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('click', function(e){
            document.querySelectorAll('input[name="submissions[]"]').forEach(function(cb){
                cb.checked = e.target.checked;
            });
        });
    </script>
    <?php
}
