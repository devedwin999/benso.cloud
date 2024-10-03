<?php
include("includes/connection.php");
include("includes/function.php");

include("includes/perm.php");

// $end = strtotime('end date');
// $start = strtotime('start date');
// $diff = abs($start - $end);

$status = array(
        0 => 'Not Started',
        1 => 'In Progress',
        2 => 'Completed',
    );
    
$status_color = array(
        0 => '#ffc107',
        1 => '#17a2b8',
        2 => '#28a745',
    );

$search_type = $_REQUEST['search_type'];

if($search_type == 'teamTaskCount') {
    
    $num = mysqli_num_rows(mysqli_query($mysqli, "SELECT * FROM team_tasks_for a LEFT JOIN team_tasks b ON b.id=a.task_id WHERE b.task_complete IS NULL AND a.employee_id = ".$_REQUEST['user']));
    
    $usr = mysqli_fetch_array(mysqli_query($mysqli, "SELECT task_remainder_level FROM employee_detail WHERE id = '". $logUser ."'"));
    $up = $usr['task_remainder_level'];
    if($up == 'C' || $up == 'D') {
        $qry1 = "SELECT a.* ";
        $qry1 .= " FROM team_tasks a ";
        $qry1 .= " WHERE a.task_complete IS NULL";
        $qry1 .= " ORDER BY a.id DESC";
        
        $num1 = mysqli_query($mysqli, $qry1);
        
        $num_row2 = 0;
        while($task1 = mysqli_fetch_array($num1)) {
            
            $t_start = $task1['start_date'];
            $t_end = $task1['end_date'];
            
            $t_end = strtotime($t_end);
            $t_start = strtotime($t_start);
            
            $dif_n = abs($t_start - $t_end);
            
            if($up == 'C') {
                $nSec = $dif_n/(100/get_setting_val('TEAM_TASK_FOLL_C'));
            } else if($up == 'D') {
                $nSec = $dif_n/(100/get_setting_val('TEAM_TASK_FOLL_D'));
            }
            
            $wrn_date = $t_start + $nSec;
            
            if($wrn_date < strtotime(date('Y-m-d H:i:s'))) {
                $num_row2++;
            }
        }
    } else {
        $num_row2 = 0;
    }
    
    
    
    $dta['num'] = $num + $num_row2;
        
    echo json_encode($dta);
    
} else if($search_type == 'LoadNotification') {
    
    // team task A & B permissions
    $qry = "SELECT a.*, b.created_by, b.task_msg, b.end_date, b.task_type ";
    $qry .= " FROM team_tasks_for a ";
    $qry .= " LEFT JOIN team_tasks b ON b.id=a.task_id ";
    $qry .= " WHERE a.employee_id = '".$_REQUEST['user']."' AND b.task_complete IS NULL";
    $qry .= " ORDER BY id DESC";
    
    $num = mysqli_query($mysqli, $qry);
    $num_row1 = mysqli_num_rows($num);
    
    while($task = mysqli_fetch_array($num)) {
        $created = mysqli_fetch_array(mysqli_query($mysqli, "SELECT employee_name, employee_photo FROM employee_detail WHERE id = '". $task['created_by'] ."'"));
        
        $sml = ($task['type']=='assigned_toB') ? '<small>(You As Follower)</small>' : '';
        
        $data['list'][] = '<li> <a onclick="openTaskSheet('. $task['task_id'] .')"> <img src="'. $created['employee_photo'] .'" alt=""> <h3>'. $task['task_type'] .''. $sml .'</h3> <p>'. $task['task_msg'] .'</p> <p style="font-size: 12px;color: #0600ff;">By : '. $created['employee_name'] .'</p> <p style="font-size: 12px;color: #eb8d17;">Due : '. date("d M, Y h:i A", strtotime($task['end_date'])) .'</p> </a> </li>';
    }
    
    // team task C & D permissions
    
    $usr = mysqli_fetch_array(mysqli_query($mysqli, "SELECT task_remainder_level FROM employee_detail WHERE id = '". $logUser ."'"));
    
    $up = $usr['task_remainder_level'];
    
    if($up == 'C' || $up == 'D') {
        $qry1 = "SELECT a.* ";
        $qry1 .= " FROM team_tasks a ";
        $qry1 .= " WHERE a.task_complete IS NULL";
        $qry1 .= " ORDER BY a.id DESC";
        
        $num1 = mysqli_query($mysqli, $qry1);
        
        // $num_row2 = mysqli_num_rows($num1);
        
        $num_row2 = 0;
        while($task1 = mysqli_fetch_array($num1)) {
            $created = mysqli_fetch_array(mysqli_query($mysqli, "SELECT employee_name, employee_photo FROM employee_detail WHERE id = '". $task1['created_by'] ."'"));
            
            $sml =  '<small style="color:red">(Remainder)</small>';
            
            $t_start = $task1['start_date'];
            $t_end = $task1['end_date'];
            
            $t_end = strtotime($t_end);
            $t_start = strtotime($t_start);
            
            $dif_n = abs($t_start - $t_end);
            
            if($up == 'C') {
                $nSec = $dif_n/(100/get_setting_val('TEAM_TASK_FOLL_C'));
            } else if($up == 'D') {
                $nSec = $dif_n/(100/get_setting_val('TEAM_TASK_FOLL_D'));
            }
            
            $wrn_date = $t_start + $nSec;
            
            // $data['list'][] = '<li>'.  date('Y-m-d H:i:s', $wrn_date) .'--------'.date('Y-m-d H:i:s').'</li>';
            
            
            if($wrn_date < strtotime(date('Y-m-d H:i:s'))) {
                $num_row2++;
                $data['list'][] = '<li> <a onclick="openTaskSheet_OnlyView('. $task1['id'] .')"> <img src="'. $created['employee_photo'] .'" alt=""> <h3>'. $task1['task_type'] .''. $sml .'</h3> <p>'. $task1['task_msg'] .'</p> <p style="font-size: 12px;color: #0600ff;">To : '. implode(',', emp_name($task1['assigned_to'])) .'</p> <p style="font-size: 12px;color: red;">Due : '. date("d M, Y h:i A", strtotime($task1['end_date'])) .'</p> </a> </li>';
            }
        }
    } else {
        $num_row2 = 0;
    }
    
    
    if($num_row1 == 0 && $num_row2 == 0) {
        $data['list'][] = '<li style="text-align:center;"> <p><i class="icon-copy dw dw-notification-11"></i> No Notification</p> </li>';
    }

    echo json_encode($data);
    
} else if($search_type == 'startTeamTask') { 
    
    $arr = array(
        'task_id' => $_REQUEST['id'],
        'employee_id' => $logUser,
        'start_time' => date('Y-m-d H:i:s'),
    );
    
    $in = Insert('team_task_timer', $arr);
    
    $mq = mysqli_insert_id($mysqli);
    
    timeline_history('Insert', 'team_task_timer', $mq, 'Task Timer Started');
    
    $st = array(
        'task_status' => 1,
    );
    
    Update('team_tasks_for', $st, ' WHERE employee_id= "'. $logUser .'" AND task_id = "'. $_REQUEST['id'] .'"');
    
    if($in) {
        $data['res'][] = 0;
        $data['inid'][] = $mq;
    } else {
        $data['res'][] = 1;
    }
    
    echo json_encode($data);

} else if($search_type == 'stopTeamTask') {
    
    $po = mysqli_fetch_array(mysqli_query($mysqli, "SELECT id, start_time, task_id FROM team_task_timer WHERE id = '". $_REQUEST['id'] ."' "));
    
    // $time1 = new DateTime(date('H:i:s', strtotime($po['start_time'])));
    // $time2 = new DateTime(date('H:i:s', strtotime(date('Y-m-d H:i:s'))));
    // $interval = $time1->diff($time2);
    // $sec = $interval->format('%s');
    
    $timestamp1 = strtotime(date('Y-m-d H:i:s'));
    $timestamp2 = strtotime($po['start_time']);
    
    $diff = abs($timestamp2 - $timestamp1);
    
    $arr = array(
        'end_time' => date('Y-m-d H:i:s'),
        'total_time' => $diff,
    );
    
    $in = Update('team_task_timer', $arr, ' WHERE id = '. $po['id']);
    
    timeline_history('Update', 'team_task_timer', $_REQUEST['id'], 'Task Timer Stoped');
    
    if($in) {
        $data['res'][] = 0;
        $data['inid'][] = $po['task_id'];
    } else {
        $data['res'][] = 1;
    }
    
    echo json_encode($data);

} else if($search_type == 'saveTeamTaskComment') {
    
    $arr = array(
        'employee_id' => $logUser,
        'table_name' => 'team_tasks',
        'primary_id' => $_REQUEST['id'],
        'comment' => $_REQUEST['comment'],
        'creaed_unit' => $logUnit,
    );
    
    $in = Insert('common_comments', $arr, ' WHERE id = '. $po['id']);
    
    $inid = mysqli_insert_id($mysqli);
    
    timeline_history('Insert', 'common_comments', $inid, 'Comment Added for Team Task');
    
    if($in) {
        $data['res'][] = 0;
        $data['inid'][] = $inid;
        $data['task_id'][] = $_REQUEST['id'];
    } else {
        $data['res'][] = 1;
    }
    
    echo json_encode($data);

} else if($search_type == 'markAsComplete') {
    
    // echo '<pre>', print_r($_FILES, 1); exit;
    
if (!is_dir("uploads/task_proof/". $_REQUEST['id'] ."/")) {
    mkdir("uploads/task_proof/". $_REQUEST['id'] . "/");
}
    
$proof = $_FILES['proof_image']['name'];

$fille = explode('.', $proof);

$newName = 'proof_'.rand(10000, 999999). '.' . end($fille);

$uploaddir = 'uploads/task_proof/'. $_REQUEST['id'] .'/';
$uploadfile = $uploaddir . $newName;

move_uploaded_file($_FILES['proof_image']['tmp_name'], $uploadfile);


    $arr = array(
        'task_complete' => 'yes',
        'task_proof' => $uploadfile,
        'completed_by' => $logUser,
        'completed_date' => date('Y-m-d H:i:s'),
    );
    
    $in = Update('team_tasks', $arr, ' WHERE id = '. $_REQUEST['id']);
    
    timeline_history('Update', 'team_tasks', $_REQUEST['id'], 'Task Completed.');
    
    
    $mk = mysqli_query($mysqli, "SELECT start_time, id FROM team_task_timer WHERE end_time IS NULL AND task_id = ". $_REQUEST['id']);
    
    while($jk = mysqli_fetch_array($mk)) {
        $ti1 = strtotime(date('Y-m-d H:i:s'));
        $ti2 = strtotime($jk['start_time']);
        
        $dif = abs($ti2 - $ti1);
        
        $array = array(
            'end_time' => date('Y-m-d H:i:s'),
            'total_time' => $dif,
        );
        
        $in = Update('team_task_timer', $array, ' WHERE id = '. $jk['id']);
    }
    
    $nup = array(
        'task_status' => 2,
    );
    
    Update('team_tasks_for', $nup, ' WHERE task_id = '. $_REQUEST['id']);
    
    
    if($in) {
        $data['res'][] = 0;
    } else {
        $data['res'][] = 1;
    }
    
    echo json_encode($data);

} else if($search_type == 'gatAllteamTaskComments') {
    
   $sqE = mysqli_query($mysqli, "SELECT * FROM common_comments WHERE table_name = 'team_tasks' AND primary_id = '". $_REQUEST['id'] ."' ORDER BY id DESC");
                            
    while($cmtt = mysqli_fetch_array($sqE)) {
        
        $emp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT employee_name, employee_photo FROM employee_detail WHERE id = '". $cmtt['employee_id'] ."'"));
?>
    <div class="row" style="padding:15px">
        <div class="" style="width:20%">
            <img src="<?= $emp['employee_photo']; ?>" style="height:50px;width:50px;border-radius:50%" title="<?= $emp['employee_name'] ?>">
        </div>
        
        <div class="" style="width:80%">
            <div style="display: flex;justify-content: space-between;">
                <p class="f-12"><i class="icon-copy dw dw-user1"></i> <?= $emp['employee_name'] ?></p>
                <p class="f-12"><i class="icon-copy dw dw-wall-clock2"></i> <?= date('d M Y - h:i A', strtotime($cmtt['created_date'])); ?></p>
            </div>
            
            <span style="font-size:15px">&nbsp;&nbsp;<?= $cmtt['comment'] ?></span>
        </div>
    </div>
    <hr>
<?php } 
    
} else if($search_type == 'getTaskSheet') {
    
    $qry = "SELECT a.*, b.task_status, b.type ";
    $qry .= " FROM team_tasks a ";
    $qry .= " LEFT JOIN team_tasks_for b ON a.id = b.task_id ";
    $qry .= " WHERE a.id = '".$_REQUEST['id']."' AND b.employee_id = '". $logUser ."'";

    $num = mysqli_query($mysqli, $qry);
    
    $task = mysqli_fetch_array($num);
        
    $created = mysqli_fetch_array(mysqli_query($mysqli, "SELECT employee_name, employee_photo FROM employee_detail WHERE id = '". $task['created_by'] ."'"));
    
    $sml = ($task['type']=='assigned_toB') ? '<small>(You As Follower)</small>' : '';
    
?>

<div class="modal-header">
    <h5 class="modal-title" id="taskLabel"><?= $task['task_type'].' '.$sml; ?></h5>
    
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

    <div class="modal-body">
        <div class="row">
            <div class="col-md-8">
                <div class="d-flex">
                    
                    <div style="width:70%">
                        <span class="f-12">Description :</span> <p style="color: #49a2ff;"><?= $task['task_msg']; ?></p>
                    </div>
                    <div style="width:30%" id="timerButton">
                        <?php
                            $po = mysqli_query($mysqli, "SELECT id FROM team_task_timer WHERE task_id = '". $task['id'] ."' AND employee_id = '". $logUser ."' AND end_time IS NULL");
                            $ftch = mysqli_fetch_array($po);
                            $H = mysqli_num_rows($po);
                            
                            if($H==1) {
                        ?>
                            <a class="btn btn-outline-danger timerA" onclick="stopTeamTask(<?= $ftch['id']; ?>)"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i> Stop Timer</a>
                        <?php } else { ?>
                            <a class="btn btn-outline-success timerA" onclick="startTeamTask(<?= $task['id']; ?>)"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i> Start Timer</a>
                        <?php } ?>
                    </div>
                </div>
                
                <hr>
                
                <div class="">
                    <h6 class="u">Comments</h6>
                    <br>
                    <textarea class="form-control" name="team_task_comment" id="team_task_comment" placeholder="Add Comment" style="height:90px"></textarea>
                    <div style="padding: 5px;text-align: right;">
                        <a class="btn btn-outline-primary cmtBtn d-none" onclick="saveTeamTaskComment(<?= $task['id']; ?>)">Save Comment</a>
                    </div>
                    <hr>
                    <br>
                    
                    <div id="addedComments">
                        <?php
                            $sqE = mysqli_query($mysqli, "SELECT * FROM common_comments WHERE table_name = 'team_tasks' AND primary_id = '". $task['id'] ."' ORDER BY id DESC");
                            
                            while($cmtt = mysqli_fetch_array($sqE)) {
                                
                                $emp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT employee_name, employee_photo FROM employee_detail WHERE id = '". $cmtt['employee_id'] ."'"));
                        ?>
                            <div class="row" style="padding:15px">
                                <div class="" style="width:20%">
                                    <img src="<?= $emp['employee_photo']; ?>" style="height:50px;width:50px;border-radius:50%" title="<?= $emp['employee_name'] ?>">
                                </div>
                                
                                <div class="" style="width:80%">
                                    <div style="display: flex;justify-content: space-between;">
                                        <p class="f-12"><i class="icon-copy dw dw-user1"></i> <?= $emp['employee_name'] ?></p>
                                        <p class="f-12"><i class="icon-copy dw dw-wall-clock2"></i> <?= date('d M Y - h:i A', strtotime($cmtt['created_date'])); ?></p>
                                    </div>
                                    
                                    <span style="font-size:15px">&nbsp;&nbsp;<?= $cmtt['comment'] ?></span>
                                </div>
                            </div>
                            <hr>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4" style="background: #f0f5f7;">
                <P class="u">Task Info</P>
                
                <span class="f-12">Created By <span style="color:#17a2b8"><?= $created['employee_name']; ?></span> <span class="icon-copy ti-info-alt" title="Created at <?= date('d-m-Y H:i A', strtotime($task['created_date'])); ?>"></span></span>
                
                <table class="table f-12">
                    <tr>
                        <td><i class="fa fa-star-half-o pull-left fa-fw fa-lg"></i>Status</td>
                        <td id="modTaskStatus" style="color:<?= $status_color[$task['task_status']]; ?>"><?= $status[$task['task_status']]; ?></td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>Start Date</td>
                        <td><?= date('d M Y h:i A', strtotime($task['start_date'])); ?></td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-calendar-check-o fa-fw fa-lg pull-left"></i>End Date</td>
                        <td><?= date('d M Y h:i A', strtotime($task['end_date'])); ?></td>
                    </tr>
                    <tr>
                        <td>Allowed Working Time</td>
                        <td><?= time_calculator($task['allowed_time']) ?></td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #dee2e6;"><i class="fa fa-asterisk fa-fw fa-lg"></i>Working Time</td>
                            <?php
                                $running1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT start_time FROM team_task_timer WHERE task_id='". $task['id'] ."' AND employee_id = '". $logUser ."' AND end_time IS NULL"));
                                
                                $t1 = strtotime(date('Y-m-d H:i:s'));
                                $t2 = strtotime($running1['start_time']);
                                
                                if(!empty($running1['start_time'])) {
                                    $diff2 = abs($t2 - $t1);
                                } else {
                                    $diff2 = 0;
                                }
                                
                                $stoped1 = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(total_time) as total_time FROM team_task_timer WHERE task_id='". $task['id'] ."' AND employee_id = '". $logUser ."'"));
                                
                                $init = $stoped1['total_time'] + $diff2;
                            ?>
                        <td style="border-bottom: 1px solid #dee2e6;"><?= time_calculator($init); ?></td>
                    </tr>
                </table>
                
                <p class="u"><i class="fa fa-user-o" aria-hidden="true"></i> Followers :</p>
                    <div style="text-align: center !important;">
                        
                        <?php
                        $sq = mysqli_query($mysqli, "SELECT * FROM employee_detail WHERE id IN (". $task['assigned_toB'] .") "); 
                        while($foll = mysqli_fetch_array($sq)){
                        ?>
                            <img src="<?= $foll['employee_photo']; ?>" style="height:50px;width:50px;border-radius: 50%;" title="<?= $foll['employee_name']; ?>">&nbsp;
                        <?php } ?>
                    </div>
                <hr>
                
                <?php if($task['type'] == 'assigned_toB') { ?>
                
                    <p class="u"><i class="fa fa-user-o" aria-hidden="true"></i> Assignees :</p>
                        <div style="text-align: center !important;">
                            
                            <?php
                            $sq = mysqli_query($mysqli, "SELECT * FROM employee_detail WHERE id IN (". $task['assigned_to'] .") "); 
                            while($foll = mysqli_fetch_array($sq)){
                            ?>
                                <img src="<?= $foll['employee_photo']; ?>" style="height:50px;width:50px;border-radius: 50%;" title="<?= $foll['employee_name']; ?>">&nbsp;
                            <?php } ?>
                        </div>
                    <hr>
                
                    <p class="u">Task Status :</p>
                    
                    <table class="table f-12">
                        <tr>
                            <td>Assignees</td>
                            <td>Status</td>
                            <td>Working Hr</td>
                        </tr>
                        
                        <?php
                            $khj = mysqli_query($mysqli, "SELECT * FROM team_tasks_for WHERE task_id = '". $task['id'] ."' AND type='assigned_to'");
                            while($foll = mysqli_fetch_array($khj)){
                                
                                $running = mysqli_fetch_array(mysqli_query($mysqli, "SELECT start_time FROM team_task_timer WHERE task_id='". $task['id'] ."' AND employee_id = '". $foll['employee_id'] ."' AND end_time IS NULL"));
                                
                                $t1 = strtotime(date('Y-m-d H:i:s'));
                                $t2 = strtotime($running['start_time']);
                                
                                if(!empty($running['start_time'])) {
                                    $diff2 = abs($t2 - $t1);
                                } else {
                                    $diff2 = 0;
                                }
                                
                                $stoped = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(total_time) as total_time FROM team_task_timer WHERE task_id='". $task['id'] ."' AND employee_id = '". $foll['employee_id'] ."'"));
                                
                                $init = $stoped['total_time'] + $diff2;
                                $hours = floor($init / 3600);
                                $minutes = floor(($init / 60) % 60);
                                $seconds = $init % 60;
                                
                            ?>
                                <tr>
                                    <td><?= implode(',', emp_name($foll['employee_id'])); ?></td>
                                    <td id="" style="color:<?= $status_color[$foll['task_status']]; ?>"><?= $status[$foll['task_status']]; ?></td>
                                    <td><?= $hours.':'.$minutes.':'.$seconds; ?></td>
                                </tr>
                        <?php } ?>
                    </table>
                <?php } //if($logUser==102) { ?>
                <form id="ImageForm" onsubmit="return false">
                    <input type="file" class="form-control" style="border:none; background-color: #f0f5f7;" name="proof_image" id="proof_image">
                    <br>
                    <button style="position: relative; left:20%;" type="button" class="btn btn-outline-info <?= ($task['task_status']==1 || $task['type']=='assigned_toB') ? 'd-block' : 'd-none' ?> compl" onclick="markAsComplete(<?= $task['id']; ?>)"><i class="icon-copy ion-ios-checkmark-outline"></i> Mark As Complete</button>
                </form>
                
                <?php //} ?>
                    <br>
            </div>
        </div>
    </div>
    <!--justify-content: space-between;-->
    <div class="modal-footer" style="display: flex;">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>

<script>
    $(document).ready(function(){
        $('#team_task_comment').on('keyup', function() {
            
            var a = $(this).val();
            
            if(a=="") {
                $(".cmtBtn").addClass('d-none');
            } else {
                $(".cmtBtn").removeClass('d-none');
            }
        });
    });
</script>


<?php } else if($search_type == 'openTaskSheet_OnlyView') {
    
    $qry = "SELECT a.*, b.task_status, b.type ";
    $qry .= " FROM team_tasks a ";
    $qry .= " LEFT JOIN team_tasks_for b ON a.id = b.task_id ";
    $qry .= " WHERE a.id = '".$_REQUEST['id']."'";

    $num = mysqli_query($mysqli, $qry);
    
    $task = mysqli_fetch_array($num);
        
    $created = mysqli_fetch_array(mysqli_query($mysqli, "SELECT employee_name, employee_photo FROM employee_detail WHERE id = '". $task['created_by'] ."'"));
    
    $sml = '<small style="color:red">(Remainder)</small>';
    
?>

<div class="modal-header">
    <h5 class="modal-title" id="taskLabel"><?= $task['task_type'].' '.$sml; ?></h5>
    
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex">
                
                <div style="width:100%">
                    <span class="f-12">Description :</span> <p style="color: #49a2ff;"><?= $task['task_msg']; ?></p>
                </div>
                <div style="width:0%" id="timerButton">
                    <?php
                        // $po = mysqli_query($mysqli, "SELECT id FROM team_task_timer WHERE task_id = '". $task['id'] ."' AND employee_id = '". $logUser ."' AND end_time IS NULL");
                        // $ftch = mysqli_fetch_array($po);
                        // $H = mysqli_num_rows($po);
                        
                        // if($H==1) {
                    ?>
                        <!--<a class="btn btn-outline-danger timerA" onclick="stopTeamTask(<?= $ftch['id']; ?>)"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i> Stop Timer</a>-->
                    <?php //} else { ?>
                        <!--<a class="btn btn-outline-success timerA" onclick="startTeamTask(<?= $task['id']; ?>)"><i class="icon-copy fa fa-clock-o" aria-hidden="true"></i> Start Timer</a>-->
                    <?php //} ?>
                </div>
            </div>
            
            <hr>
            
            <div class="">
                <h6 class="u">Comments</h6>
                <br>
                <textarea class="form-control" name="team_task_comment" id="team_task_comment" placeholder="Add Comment" style="height:90px"></textarea>
                <div style="padding: 5px;text-align: right;">
                    <a class="btn btn-outline-primary cmtBtn d-none" onclick="saveTeamTaskComment(<?= $task['id']; ?>)">Save Comment</a>
                </div>
                <hr>
                <br>
                
                <div id="addedComments">
                    <?php
                        $sqE = mysqli_query($mysqli, "SELECT * FROM common_comments WHERE table_name = 'team_tasks' AND primary_id = '". $task['id'] ."' ORDER BY id DESC");
                        
                        while($cmtt = mysqli_fetch_array($sqE)) {
                            
                            $emp = mysqli_fetch_array(mysqli_query($mysqli, "SELECT employee_name, employee_photo FROM employee_detail WHERE id = '". $cmtt['employee_id'] ."'"));
                    ?>
                        <div class="row" style="padding:15px">
                            <div class="" style="width:20%">
                                <img src="<?= $emp['employee_photo']; ?>" style="height:50px;width:50px;border-radius:50%" title="<?= $emp['employee_name'] ?>">
                            </div>
                            
                            <div class="" style="width:80%">
                                <div style="display: flex;justify-content: space-between;">
                                    <p class="f-12"><i class="icon-copy dw dw-user1"></i> <?= $emp['employee_name'] ?></p>
                                    <p class="f-12"><i class="icon-copy dw dw-wall-clock2"></i> <?= date('d M Y - h:i A', strtotime($cmtt['created_date'])); ?></p>
                                </div>
                                
                                <span style="font-size:15px">&nbsp;&nbsp;<?= $cmtt['comment'] ?></span>
                            </div>
                        </div>
                        <hr>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-4" style="background: #f0f5f7;">
            <P class="u">Task Info</P>
            
            <span class="f-12">Created By <span style="color:#17a2b8"><?= $created['employee_name']; ?></span> <span class="icon-copy ti-info-alt" title="Created at <?= $task['created_date']; ?>"></span></span>
            
            <table class="table f-12">
                <tr>
                    <td><i class="fa fa-star-half-o pull-left fa-fw fa-lg"></i>Status</td>
                    <td id="modTaskStatus" style="color:<?= $status_color[$task['task_status']]; ?>"><?= $status[$task['task_status']]; ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>Start Date</td>
                    <td><?= date('d M Y h:i A', strtotime($task['start_date'])); ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-calendar-check-o fa-fw fa-lg pull-left"></i>End Date</td>
                    <td><?= date('d M Y h:i A', strtotime($task['end_date'])); ?></td>
                </tr>
                
            </table>
            
            <p class="u"><i class="fa fa-user-o" aria-hidden="true"></i> Followers :</p>
                <div style="text-align: center !important;">
                    
                    <?php
                    $sq = mysqli_query($mysqli, "SELECT * FROM employee_detail WHERE id IN (". $task['assigned_toB'] .") "); 
                    while($foll = mysqli_fetch_array($sq)){
                    ?>
                        <img src="<?= $foll['employee_photo']; ?>" style="height:50px;width:50px;border-radius: 50%;" title="<?= $foll['employee_name']; ?>">&nbsp;
                    <?php } ?>
                </div>
            <hr>
            
            <?php //if($task['type'] == 'assigned_toB') { ?>
            
                <p class="u"><i class="fa fa-user-o" aria-hidden="true"></i> Assignees :</p>
                    <div style="text-align: center !important;">
                        
                        <?php
                        $sq = mysqli_query($mysqli, "SELECT * FROM employee_detail WHERE id IN (". $task['assigned_to'] .") "); 
                        while($foll = mysqli_fetch_array($sq)){
                        ?>
                            <img src="<?= $foll['employee_photo']; ?>" style="height:50px;width:50px;border-radius: 50%;" title="<?= $foll['employee_name']; ?>">&nbsp;
                        <?php } ?>
                    </div>
                <hr>
            
                <p class="u">Task Status :</p>
                
                <table class="table f-12">
                    <tr>
                        <td>Assignees</td>
                        <td>Status</td>
                        <td>Working Hr</td>
                    </tr>
                    
                    <?php
                        $khj = mysqli_query($mysqli, "SELECT * FROM team_tasks_for WHERE task_id = '". $task['id'] ."' AND type='assigned_to'");
                        while($foll = mysqli_fetch_array($khj)){
                            
                            $running = mysqli_fetch_array(mysqli_query($mysqli, "SELECT start_time FROM team_task_timer WHERE task_id='". $task['id'] ."' AND employee_id = '". $foll['employee_id'] ."' AND end_time IS NULL"));
                            
                            $t1 = strtotime(date('Y-m-d H:i:s'));
                            $t2 = strtotime($running['start_time']);
                            
                            if(!empty($running['start_time'])) {
                                $diff2 = abs($t2 - $t1);
                            } else {
                                $diff2 = 0;
                            }
                            
                            $stoped = mysqli_fetch_array(mysqli_query($mysqli, "SELECT sum(total_time) as total_time FROM team_task_timer WHERE task_id='". $task['id'] ."' AND employee_id = '". $foll['employee_id'] ."'"));
                            
                            $init = $stoped['total_time'] + $diff2;
                            $hours = floor($init / 3600);
                            $minutes = floor(($init / 60) % 60);
                            $seconds = $init % 60;
                            
                        ?>
                            <tr>
                                <td><?= implode(',', emp_name($foll['employee_id'])); ?></td>
                                <td id="" style="color:<?= $status_color[$foll['task_status']]; ?>"><?= $status[$foll['task_status']]; ?></td>
                                <td><?= $hours.':'.$minutes.':'.$seconds; ?></td>
                            </tr>
                    <?php //} ?>
                    
                </table>
                
                <?php if($task['task_complete'] != 'yes') { ?>
                    <form id="ImageForm" onsubmit="return false">
                        <input type="file" class="form-control" style="border:none; background-color: #f0f5f7;" name="proof_image" id="proof_image">
                        <br>
                        <button style="position: relative; left:20%;" type="button" class="btn btn-outline-info <?= ($task['task_status']==1 || $task['type']=='assigned_toB') ? 'd-block' : 'd-none' ?> compl" onclick="markAsComplete(<?= $task['id']; ?>)"><i class="icon-copy ion-ios-checkmark-outline"></i> Mark As Complete</button>
                    </form>
                <?php } else {
                    print '<a href="download.php?f='. $task['task_proof'] .'" class="f-12" style="color:#a5a5a5"><i class="icon-copy fa fa-cloud-download" aria-hidden="true"></i> Download Proof</a>';
                    print '<br><span class="f-12">Task Completed <br>By <span style="color:#17a2b8">'. implode(',', emp_name($task['completed_by'])) .'</span> @ '. date('d M Y h:i A', strtotime($task['completed_date'])) .'</span>';
                }
                ?>
                <br>
            <?php } ?>
        </div>
    </div>
</div>

<div class="modal-footer" style="display: flex;">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>

<script>
    $(document).ready(function(){
        $('#team_task_comment').on('keyup', function() {
            
            var a = $(this).val();
            
            if(a=="") {
                $(".cmtBtn").addClass('d-none');
            } else {
                $(".cmtBtn").removeClass('d-none');
            }
        });
    });
</script>
<?php } ?>

















