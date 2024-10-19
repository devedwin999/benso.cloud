<?php
include("includes/connection.php");
include("includes/function.php");


$cron = $_GET['cron'] ?? '';

if ($cron === 'clear_qrcode') {
    $directories = ["uploads/qrcode/bundle_barcode", "uploads/qrcode/bundle_qr", "uploads/qrcode/piece_qr"];
    
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                    
                    if (is_file($filePath)) {
                        unlink($filePath);
                    }
                }
            }
        }
    }
}

if ($cron === 'reminder_task') {
    
    $today = date('Y-m-d');
    $currentTasks = [];
    
    $sql = mysqli_query($mysqli, "SELECT * FROM reminder_tasks WHERE task_status = 0");
    
    $nmm = mysqli_num_rows($sql);
    if($nmm>0) {
        while($result = mysqli_fetch_array($sql)) {
            // foreach ($tasks as $task) {
            $startDate = strtotime($result['remainder_from']);
            $daysSinceStart = (strtotime($today) - $startDate) / (60 * 60 * 24);
            
            if ($daysSinceStart % $result['remainder_days'] == 0) {

                $start_timestamp = strtotime($today.' 00:00:00');
                $end_timestamp = strtotime($today.' 23:59:59');
                $task_duration = $end_timestamp - $start_timestamp;

                $ndt = array(
                    'type' => $result['type'],
                    'task_type' => $result['task_type'],
                    'task_msg' => $result['task_msg'],
                    'assigned_to' => $result['assigned_to'],
                    'assigned_toB' => $result['assigned_toB'],
                    'task_duration' =>$task_duration,
                    'allowed_time' => $result['allowed_time'],
                    'start_date' => date('Y-m-d H:i:s', $start_timestamp),
                    'end_date' => date('Y-m-d H:i:s', $end_timestamp),
                );
                
                $i = Insert('team_tasks', $ndt);
                $inid = mysqli_insert_id($mysqli);
                if ($i) {
                    
                    $r1 = explode(',', $result['assigned_to']);
                    $r2 = explode(',', $result['assigned_toB']);

                    for ($i = 0; $i < count($r1); $i++) {
                        $ar = array(
                            'task_id' => $inid,
                            'type' => 'assigned_to',
                            'employee_id' => $r1[$i],
                        );
                        Insert('team_tasks_for', $ar);
                    }
        
                    for ($i = 0; $i < count($r2); $i++) {
                        $ar = array(
                            'task_id' => $inid,
                            'type' => 'assigned_toB',
                            'employee_id' => $r2[$i],
                        );
                        Insert('team_tasks_for', $ar);
                    }
        
                    $data['res'][] = 0;
                } else {
                    $data['res'][] = 1;
                }
            }
        }

        timeline_history('Insert', 'reminder_tasks', $inid, 'Cron Running Success!. '. $nmm .' Reminder Tasks Creted.');
    } else {
        timeline_history('Insert', 'reminder_tasks', $inid, 'Cron Running Success!. No reminder tasks found!');
    }

    $data['result'][] = 'Success';

    echo json_encode($data);
}