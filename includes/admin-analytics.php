<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function ucf7e_render_analytics_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You must be an admin to access this page.', 'nahian-ultimate-cf7-elementor' ) );
    }

    // Load real submissions from the option
    $submissions = get_option('ucf7e_submissions', []);

    $form_stats = [];
    $date_stats = [];
    $day_of_week_stats = array_fill(0,7,0); // 0=Sun, 6=Sat
    $hour_stats = array_fill(0,24,0);       // 0-23 hours

    $today = date('Y-m-d');
    $week_start = date('Y-m-d', strtotime('-7 days'));
    $month_start = date('Y-m-01');

    $week_count = 0;
    $month_count = 0;

    foreach ($submissions as $s) {
        $fid = $s['form_id'];
        $date = date('Y-m-d', strtotime($s['submitted_at']));
        $dow  = date('w', strtotime($s['submitted_at']));
        $hour = date('G', strtotime($s['submitted_at']));

        if (!isset($form_stats[$fid])) $form_stats[$fid] = ['title'=>$s['form_title'],'count'=>0];
        $form_stats[$fid]['count']++;

        if (!isset($date_stats[$date])) $date_stats[$date] = 0;
        $date_stats[$date]++;

        $day_of_week_stats[$dow]++;
        $hour_stats[$hour]++;

        if ($date >= $week_start) $week_count++;
        if ($date >= $month_start) $month_count++;
    }

    // Most active form
    $most_active = !empty($form_stats) ? array_reduce($form_stats, fn($carry,$item)=>(!$carry||$item['count']>$carry['count'])?$item:$carry, null) : null;
    $avg_per_day = $submissions ? round(array_sum(array_values($date_stats))/count($date_stats),2) : 0;

    // Top 5 Forms
    $top_forms = $form_stats;
    usort($top_forms, fn($a,$b)=>$b['count']-$a['count']);
    $top_forms = array_slice($top_forms,0,5);

    ?>
    <div class="wrap ucf7-wrap">
        <h1><?php esc_html_e('CF7 Advanced Analytics','nahian-ultimate-cf7-elementor'); ?></h1>

        <!-- Stats Cards -->
        <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:30px;">
            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3>Total Submissions</h3>
                <p style="font-size:1.6em;"><?php echo esc_html(count($submissions)); ?></p>
            </div>
            <?php if($most_active): ?>
            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3>Most Active Form</h3>
                <p><?php echo esc_html($most_active['title']); ?></p>
                <p><?php echo esc_html($most_active['count']); ?> submissions</p>
            </div>
            <?php endif; ?>
            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3>Average / Day</h3>
                <p><?php echo esc_html($avg_per_day); ?></p>
            </div>
            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3>Submissions This Week</h3>
                <p><?php echo esc_html($week_count); ?></p>
            </div>
            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3>Submissions This Month</h3>
                <p><?php echo esc_html($month_count); ?></p>
            </div>
        </div>

        <!-- Charts -->
        <div style="display:flex;flex-wrap:wrap;gap:30px;margin-bottom:30px;">
            <div style="flex:1 1 45%;background:#fff;padding:20px;">
                <h2>Submissions Per Form</h2>
                <canvas id="ucf7e-form-chart" height="200"></canvas>
            </div>
            <div style="flex:1 1 45%;background:#fff;padding:20px;">
                <h2>Daily Submission Trend</h2>
                <canvas id="ucf7e-date-chart" height="200"></canvas>
            </div>
            <div style="flex:1 1 45%;background:#fff;padding:20px;">
                <h2>Submissions by Day of Week</h2>
                <canvas id="ucf7e-dow-chart" height="200"></canvas>
            </div>
            <div style="flex:1 1 45%;background:#fff;padding:20px;">
                <h2>Submissions by Hour</h2>
                <canvas id="ucf7e-hour-chart" height="200"></canvas>
            </div>
        </div>
    </div>
<?php
}
