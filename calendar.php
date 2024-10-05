<?php

    date_default_timezone_set('America/New_York');


    $month = date('m');
    $year = date('Y');

    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $totalDaysInMonth = date('t', $firstDayOfMonth);

    $monthName = date('F', $firstDayOfMonth);
    $firstWeekday = date('w', $firstDayOfMonth);

    $table = "<h5>". $monthName ." ". $year ."</h5>";
    $table .= "<table class='table table-bordered' border='1' cellpadding='5' cellspacing='0'>";
    $table .= "<tr>";

    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    foreach ($daysOfWeek as $day) {
        $table .= "<th>$day</th>";
    }
    $table .= "</tr><tr>";

    for ($i = 0; $i < $firstWeekday; $i++) {
        $table .= "<td></td>";
    }

    for ($day = 1; $day <= $totalDaysInMonth; $day++) {
        if (($day + $firstWeekday - 1) % 7 == 0 && $day != 1) {
            $table .= "</tr><tr>";
        }
        $table .= "<td><div class='al-top'>$day</div></td>";
    }

    print $table .= "</tr></table>";

    // $data['table'][] = $table;

    // echo json_encode($data);
?>
