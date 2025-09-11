<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function ucf7e_render_analytics_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You must be an admin to access this page.', 'nahian-ultimate-cf7-elementor' ) );
    }

    $submissions = get_option('ucf7e_submissions', []);
    $form_stats = [];
    $date_stats = [];
    $day_of_week_stats = array_fill(0,7,0);
    $hour_stats = array_fill(0,24,0);

    $today = date('Y-m-d');
    $week_start = date('Y-m-d', strtotime('-7 days'));
    $month_start = date('Y-m-01');

    $week_count = $month_count = 0;

    foreach ($submissions as $s) {
        $fid  = $s['form_id'] ?? 0;
        $title = sanitize_text_field($s['form_title'] ?? '');
        $date = date('Y-m-d', strtotime($s['submitted_at'] ?? ''));
        $dow  = date('w', strtotime($s['submitted_at'] ?? '0'));
        $hour = date('G', strtotime($s['submitted_at'] ?? '0'));

        if (!isset($form_stats[$fid])) $form_stats[$fid] = ['title'=>$title,'count'=>0];
        $form_stats[$fid]['count']++;

        if (!isset($date_stats[$date])) $date_stats[$date] = 0;
        $date_stats[$date]++;

        $day_of_week_stats[$dow]++;
        $hour_stats[$hour]++;

        if ($date >= $week_start) $week_count++;
        if ($date >= $month_start) $month_count++;
    }

    $most_active = !empty($form_stats) ? array_reduce($form_stats, fn($carry,$item)=>(!$carry||$item['count']>$carry['count'])?$item:$carry, null) : null;
    $avg_per_day = $submissions ? round(array_sum(array_values($date_stats))/count($date_stats),2) : 0;

    $top_forms = $form_stats;
    usort($top_forms, fn($a,$b)=>$b['count']-$a['count']);
    $top_forms = array_slice($top_forms,0,5);
    ?>
    <div class="wrap ucf7-wrap">
        <h1><?php esc_html_e('CF7 Advanced Analytics','nahian-ultimate-cf7-elementor'); ?></h1>

        <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:30px;">
            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3><?php esc_html_e('Total Submissions','nahian-ultimate-cf7-elementor'); ?></h3>
                <p style="font-size:1.6em;"><?php echo esc_html(count($submissions)); ?></p>
            </div>

            <?php if($most_active): ?>
            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3><?php esc_html_e('Most Active Form','nahian-ultimate-cf7-elementor'); ?></h3>
                <p><?php echo esc_html($most_active['title']); ?></p>
                <p><?php echo esc_html($most_active['count']); ?> <?php esc_html_e('submissions','nahian-ultimate-cf7-elementor'); ?></p>
            </div>
            <?php endif; ?>

            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3><?php esc_html_e('Average / Day','nahian-ultimate-cf7-elementor'); ?></h3>
                <p><?php echo esc_html($avg_per_day); ?></p>
            </div>

            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3><?php esc_html_e('Submissions This Week','nahian-ultimate-cf7-elementor'); ?></h3>
                <p><?php echo esc_html($week_count); ?></p>
            </div>

            <div style="flex:1 1 200px;background:#fff;padding:20px;border-left:5px solid #2271b1;">
                <h3><?php esc_html_e('Submissions This Month','nahian-ultimate-cf7-elementor'); ?></h3>
                <p><?php echo esc_html($month_count); ?></p>
            </div>
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:30px;margin-bottom:30px;">
            <div style="flex:1 1 45%;background:#fff;padding:20px;">
                <h2><?php esc_html_e('Submissions Per Form','nahian-ultimate-cf7-elementor'); ?></h2>
                <canvas id="ucf7e-form-chart" height="200"></canvas>
            </div>

            <div style="flex:1 1 45%;background:#fff;padding:20px;">
                <h2><?php esc_html_e('Daily Submission Trend','nahian-ultimate-cf7-elementor'); ?></h2>
                <canvas id="ucf7e-date-chart" height="200"></canvas>
            </div>

            <div style="flex:1 1 45%;background:#fff;padding:20px;">
                <h2><?php esc_html_e('Submissions by Day of Week','nahian-ultimate-cf7-elementor'); ?></h2>
                <canvas id="ucf7e-dow-chart" height="200"></canvas>
            </div>

            <div style="flex:1 1 45%;background:#fff;padding:20px;">
                <h2><?php esc_html_e('Submissions by Hour','nahian-ultimate-cf7-elementor'); ?></h2>
                <canvas id="ucf7e-hour-chart" height="200"></canvas>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function(){
        new Chart(document.getElementById('ucf7e-form-chart').getContext('2d'), {
            type:'bar',
            data:{labels:<?php echo wp_json_encode(array_map('esc_html', array_column($top_forms,'title'))); ?>,datasets:[{label:'Submissions',data:<?php echo wp_json_encode(array_column($top_forms,'count')); ?>,backgroundColor:'#2271b1',borderRadius:4}]},
            options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
        });

        new Chart(document.getElementById('ucf7e-date-chart').getContext('2d'), {
            type:'line',
            data:{labels:<?php echo wp_json_encode(array_keys($date_stats)); ?>,datasets:[{label:'Submissions per Day',data:<?php echo wp_json_encode(array_values($date_stats)); ?>,borderColor:'#2271b1',backgroundColor:'rgba(34,113,177,0.1)',fill:true,tension:0.3,pointRadius:5}]},
            options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
        });

        new Chart(document.getElementById('ucf7e-dow-chart').getContext('2d'), {
            type:'bar',
            data:{labels:['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],datasets:[{label:'Submissions',data:<?php echo wp_json_encode(array_values($day_of_week_stats)); ?>,backgroundColor:'#f39c12',borderRadius:4}]},
            options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
        });

        new Chart(document.getElementById('ucf7e-hour-chart').getContext('2d'), {
            type:'bar',
            data:{labels:[...Array(24).keys()],datasets:[{label:'Submissions',data:<?php echo wp_json_encode(array_values($hour_stats)); ?>,backgroundColor:'#16a085',borderRadius:4}]},
            options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
        });
    });
    </script>
<?php
}
