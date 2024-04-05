<?php
include_once('config.php');
$empcode = '';

//set user if available
if (isset($_GET['CN'])) {
    $empcode = $_GET['CN'];
}

//connect to a SQL Server
include_once('config/db_query.php');
$sql = "SELECT dbo.vPetraEmployeeStatus_web.DisplayName AS 'Employee Name', dbo.vPetraEmployeeStatus_web.CompanyNumber AS 'Company Number', dbo.vPetraEmployeeStatus_web.Medial_FollowUpDate AS 'Medical Expires', 
                        dbo.vPetraEmployeeStatus_web.Induction_Followupdate AS 'Induction Expires', dbo.vPetraEmployeeStatus_web.Access_LastDatePlaceBadged AS 'Last Point Badged', 
                        dbo.vPetraEmployeeStatus_web.UpcomingPlannedLeave AS 'Upcoming Planned Leave', dbo.vPetraEmployeeStatus_web.Inside, dbo.vPetraEmployeeStatus_web.XTimeExceptions, dbo.vPetraEmployeeStatus_web.XtimeOperation, 
                        dbo.vPetraEmployeeStatus_web.LeaveApproved, dbo.vPetraEmployeeStatus_web.Operation AS 'LeaveOperation', dbo.vPetraEmployeeStatus_web.LastOffClockDate, dbo.vPetraEmployeeStatus_web.[HH:MM], 
                        dbo.vPetraEmployeeStatus_web.SFTName AS XTimeProfile, dbo.vPetraEmployeeStatus_web.Shifts AS TimeProfile,vPetraEmployeeStatus_web.ToBeOnShift
                        FROM     dbo.vPetraEmployeeStatus_web LEFT OUTER JOIN
                        dbo.tVIPData ON dbo.tVIPData.CompanyNumber = dbo.vPetraEmployeeStatus_web.CompanyNumber
                        where ReportToManager = '$user'
                        or LogonId = '$user'
                        or vPetraEmployeeStatus_web.companynumber = '$empcode'
                        order by DisplayName asc";
/* Fetch all of the remaining rows in the result set */
$args = [];
$result = sqlQuery($sql, $args);
if ($result[0] == FALSE) die("could not execute statement $sql<br />");

//open container
$data =  '{';
$data = $data . '"OperatorAvailabilityRecord":[';

//start records
//set loop counter
$i = 1;
echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
echo ('<th>' . "Employee Name" . '</th>');
echo ('<th>' . "Medical Expires" . '</th>');
echo ('<th>' . "Induction Expires" . '</th>');
echo ('<th>' . "Last Point Badged" . '<span style="color:limegreen;font-weight 6px;"><br>(avg:15min)</span>' . '</th>');
echo ('<th>' . "Prev Shift" . '<span style="color:limegreen;font-weight 6px;"><br>Total (HH:MM)</span>' . '</th>');
echo ('<th>' . "Leave" . '</th>');
echo ('<th>' . "XTime<br>Exceptions" . '</th>');
echo ('</tr></thead><tbody>');


foreach ($result[0] as $rec) {
    //data color processing medical
    $medical =  $rec['Medical Expires'];
    $medicalColor = 'black';
    if (strlen($medical) < 1) {
        $medicalColor = 'red';
        $medical = 'CONTACT HR';
    } else {
        //calc med age
        $date = DateTime::createFromFormat('Y-m-d', $medical);
        $now = new DateTime();
        $date = strtotime($date->format('Y/m/d'));
        $now = strtotime($now->format('Y/m/d'));
        $datediff = $date - $now;
        $datediff = round($datediff / (60 * 60 * 24));

        //MediumSpringGreen
        if ($datediff < 30) {
            $medicalColor = 'Brown';
        }
        if ($datediff < 15) {
            $medicalColor = 'Orange';
        }
        if ($datediff < 0) {
            $medicalColor = 'red';
        }

        $medical = $medical . '<br>(' . ($datediff) . " Days)";
    }


    //data color processing induction
    $induction = $rec['Induction Expires'];
    $inductionColor = 'black';
    if (strlen($induction ?? '') < 1) {
        $inductionColor = 'red';
        $induction = 'CONTACT HR';
    } else {
        //calc induction age
        $date = DateTime::createFromFormat('Y-m-d', $induction);
        $now = new DateTime();
        $date = strtotime($date->format('Y/m/d'));
        $now = strtotime($now->format('Y/m/d'));
        $datediff = $date - $now;
        $datediff = round($datediff / (60 * 60 * 24));

        if ($datediff < 30) {
            $inductionColor = 'brown';
        }
        if ($datediff < 15) {
            $inductionColor = 'Orange';
        }
        if ($datediff < 0) {
            $inductionColor = 'red';
        }
        $induction = $induction . '<br>(' . ($datediff) . " Days)";
    }

    //get badge color
    $badgeColor = 'LightCoral';
    $badge = $rec['Inside'];
    if ($badge === "1") {
        $badgeColor = 'LightGreen';
    }

    if ($badge === "1") {
        $badgeText = '<a style="cursor: pointer;" data-toggle="tooltip"
                                      data-placement="top" title="' . $rec['Last Point Badged'] . '">' . explode(' ', $rec['Last Point Badged'] ?? '')[0] . '<br>Entry</a>';
    } else {
        $badgeText = '<a style="cursor: pointer;" data-toggle="tooltip"
                                      data-placement="top" title="' . $rec['Last Point Badged'] . '">' . explode(' ', $rec['Last Point Badged'] ?? '')[0] . '<br>Exit</a>';
    }

    $EmpName = '<a style="cursor: pointer;" data-toggle="tooltip"
                                      data-placement="top" title="' . $rec['Company Number'] . '">' . $rec['Employee Name'] . '</a><br>';;

    $xTimeOperation = $rec['XtimeOperation'];
    $XtimeURL = '';

    //CDM
    if ($xTimeOperation == 'CDM') {
        $XtimeURL = '<br><a class="btn btn-outline-primary btn-sm" target="_blank" href="https://cdm-ser-tms-01.petragroup.local:8446/html/index.html#!/payhistory">Action</a>';
    }

    //FDM
    if ($xTimeOperation == 'FDM') {
        $XtimeURL = '<br><a class="btn btn-outline-primary btn-sm" target="_blank" href="https://fin-ser-tms-01.petragroup.local:8443/html/index.html#!/payhistory">Action</a>';
    }

    //KDM
    if ($xTimeOperation == 'KDM') {
        $XtimeURL = '<br><a class="btn btn-outline-primary btn-sm" target="_blank" href="https://kof-ser-tms-01.petragroup.local:8446/html/index.html#!/payhistory">Action</a>';
    }

    $LeaveOperation = $rec['LeaveOperation'];
    $leaveText = $rec['Upcoming Planned Leave'];
    $leaveText =    explode('-', $leaveText ?? '')[0];

    $LeaveApproved = $rec['LeaveApproved'];
    if ($LeaveApproved !== "1") {
        $LeaveApproved = '';
    } else {
        $LeaveApproved = '<a style="cursor: pointer;" data-toggle="tooltip" href="https://dat-ser-vip-02.petragroup.local/webselfservice/"
                                        data-placement="top" title="' . $leaveText . '" class="btn btn-outline-warning btn-sm">Action</a>';
    }

    $shiftData = '';
    if ($rec['XTimeProfile'] !== null) {
        $shiftData = '<b>Time Profile: </b>' .  $rec['XTimeProfile'] . '<br>';
    }


    if (strlen($leaveText) > 1) {
        $leaveText = $leaveText . '<br>' . $LeaveApproved;
    } else {
        $leaveText = '';
    }

    // 
    $badge = '';
    if ($rec['ToBeOnShift'] !==  null) {
        $badge = '<span class="badge bg-success">Scheduled for ' . $rec['ToBeOnShift'] . '.</span><br>';
    }

    // Get row data
    echo ('<tr>');
    echo ('<td>' . $EmpName . '</td>');
    echo ('<td class="text-center" style="color: ' . $medicalColor . ' ;">' . $medical . '</td>');
    echo ('<td class="text-center" style="color: ' . $inductionColor . '">' . $induction . '</td>');
    echo ('<td class="text-center" style="background-color: ' . $badgeColor . '">' . $badge . $badgeText . '</td>');
    echo ('<td class="text-center"><small>' . $shiftData . '<b>Shift Profile: </b>' .  $rec['TimeProfile'] . '<br><b>Prev Shift:</b> ' . $rec['LastOffClockDate'] . ' ' . $rec['HH:MM'] . '</small></td>');
    echo ('<td class="text-center">' . $leaveText . '<a class="btn btn-outline-primary btn-sm" href="leave.php?CN=' . $rec['Company Number'] . '">Balance</a>' .
        '</td>');
    echo ('<td class="text-center">' . $rec['XTimeExceptions'] . "$XtimeURL" . '</td>');
    echo ('</tr>');
}
echo ('</tbody>');
//     '<tfoot><tr>');
//     echo('<th>' . "Employee Name" . '</th>');
//     echo('<th>' . "Company Number" . '</th>');
//     echo('<th>' . "Medical Expires" . '</th>');
//     echo('<th>' . "Induction Expires" . '</th>');
//     echo('<th>' . "Last Point Badged" . '</th>');
//     echo('<th>' . "Upcoming Planned Leave" . '</th>');
//     echo('</tr></tfoot>
echo ('</table>'); ?>


<form action="getCSV.php" method="post">
    <input type="hidden" name="csv_text" id="csv_text">
    <input type="submit" value="Get CSV File" onclick="getCSVData();">
</form>

<?php
echo "<br>";

//close container
$data = $data . ']}';
?>