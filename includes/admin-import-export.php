<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function ucf7e_render_import_export_page() {

    ?>
    <div class="wrap ucf7-wrap">
        <h1><?php esc_html_e('CF7 Import / Export','nahian-ultimate-cf7-elementor'); ?></h1>

        <h2><?php esc_html_e('Export Submissions','nahian-ultimate-cf7-elementor'); ?></h2>
        <p><?php esc_html_e('Download all dummy submissions as a CSV file.','nahian-ultimate-cf7-elementor'); ?></p>
        <p>
            <a href="<?php echo esc_url(add_query_arg('ucf7e_export','csv', admin_url('admin.php?page=ucf7e-import-export'))); ?>" class="button button-primary">
                <?php esc_html_e('Download CSV','nahian-ultimate-cf7-elementor'); ?>
            </a>
        </p>

        <h2><?php esc_html_e('Import Submissions','nahian-ultimate-cf7-elementor'); ?></h2>
        <p><?php esc_html_e('You can upload a CSV file to import dummy submissions. (Note: No real DB used, just simulation)','nahian-ultimate-cf7-elementor'); ?></p>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="ucf7e_import_file" accept=".csv" required>
            <input type="submit" name="ucf7e_import_submit" class="button button-primary" value="<?php esc_attr_e('Import CSV','nahian-ultimate-cf7-elementor'); ?>">
        </form>
        <?php

        // Handle Import
        if(isset($_POST['ucf7e_import_submit']) && !empty($_FILES['ucf7e_import_file']['tmp_name'])){
            $file = $_FILES['ucf7e_import_file']['tmp_name'];
            $rows = array_map('str_getcsv', file($file));
            $header = array_shift($rows);

            $submissions = [];
            foreach($rows as $row){
                $submissions[] = [
                    'submitted_at' => $row[0] ?? date('Y-m-d H:i:s'),
                    'form_title'   => $row[1] ?? 'Imported Form',
                    'data' => [
                        'name'  => $row[2] ?? '',
                        'email' => $row[3] ?? '',
                        'msg'   => $row[4] ?? '',
                    ]
                ];
            }

            set_transient('ucf7e_dummy_submissions', $submissions, 12*HOUR_IN_SECONDS);

            echo '<div class="notice notice-success"><p>'.esc_html__('CSV imported successfully!','nahian-ultimate-cf7-elementor').'</p></div>';
        }

        // Handle CSV Export
        if(isset($_GET['ucf7e_export']) && $_GET['ucf7e_export']==='csv'){
            $submissions = ucf7e_get_dummy_submissions();
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="cf7-submissions.csv"');
            $out = fopen('php://output','w');
            fputcsv($out,['Date','Form','Name','Email','Message']);
            foreach($submissions as $s){
                fputcsv($out,[
                    $s['submitted_at'] ?? '',
                    $s['form_title'] ?? '',
                    $s['data']['name'] ?? '',
                    $s['data']['email'] ?? '',
                    $s['data']['msg'] ?? '',
                ]);
            }
            fclose($out);
            exit;
        }
        ?>
    </div>
    <?php
}
