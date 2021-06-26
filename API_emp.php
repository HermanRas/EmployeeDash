<?php
        include_once('config.php');
        $empcode = '';

        //set user if available
        if (isset($_GET['CN'])) {
        $empcode = $_GET['CN']; 
        }

        //connect to a DSN "myDSN" 
        $connection_string = "DRIVER={SQL Server};SERVER=DAT-SER-SQL-01.petragroup.local;DATABASE=EAI_PeopleUpdate"; 
        $db = odbc_connect($connection_string, "EAIEmployeeUpdate", "EAIEmployeeUpdate") or die ("could not connect<br />");
            //run sql query
            $stmt = "SELECT 
                        DisplayName as 'Employee Name',
                        CompanyNumber as 'Company Number',
                        Medial_FollowUpDate as 'Medical Expires',
                        Induction_FollowUpdate as 'Induction Expires',
                        Access_LastDatePlaceBadged as 'Last Point Badged',
                        UpcomingPlannedLeave as 'Upcoming Planned Leave',
                        Inside,
                        ISNULL([tXtimeExceptions].Exceptions,0)  as 'XTimeExceptions',
                        [tXtimeExceptions].Operation as 'XTimeOperation'
                        from [EAI_PeopleUpdate].[dbo].[vPetraEmployeeStatus_web]
                        left join [tXtimeExceptions] on [tXtimeExceptions].[EmployeeCode] = [vPetraEmployeeStatus_web].[CompanyNumber]
                        where ReportToManager = '$user'
                        or LogonId = '$user'
                        or companynumber = '$empcode'
                        order by DisplayName asc";
            $result = odbc_exec($db, $stmt);
            if ($result == FALSE) die ("could not execute statement $stmt<br />");
            
            //open container
            $data =  '{';
            $data = $data. '"OperatorAvailabilityRecord":[';
            
            //start records
            //set loop counter
            $i = 1;
            echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
            echo('<th>' . "Employee Name" . '</th>');
            echo('<th>' . "Medical Expires" . '</th>');
            echo('<th>' . "Induction Expires" . '</th>');
            echo('<th>' . "Last Point Badged".'<span style="color:limegreen;font-weight 6px;"><br>(avg:15min)</span>' . '</th>');
            echo('<th>' . "Leave" . '</th>');
            echo('<th>' . "XTime<br>Exceptions" . '</th>');
            echo('</tr></thead><tbody>');
            while (odbc_fetch_row($result)) // while there are rows
            {  
                //data color processing medical
                $medical =  odbc_result($result, 'Medical Expires');
                $medicalColor = 'black';
                if(strlen($medical) < 1){
                        $medicalColor = 'red';
                        $medical = 'CONTACT HR';
                }else{
                        //calc med age
                        $date = DateTime::createFromFormat('Y-m-d',$medical);
                        $now = new DateTime();
                        $date = strtotime($date->format('Y/m/d'));
                        $now = strtotime($now->format('Y/m/d'));
                        $datediff = $date - $now;
                        $datediff = round($datediff / (60 * 60 * 24));
                        
                        //MediumSpringGreen
                        if ($datediff < 30){
                              $medicalColor = 'Brown';
                        }
                        if ($datediff < 15){
                              $medicalColor = 'Orange';
                        }
                        if ($datediff < 0){
                              $medicalColor = 'red';
                        }

                        $medical = $medical . '<br>(' .($datediff). " Days)";
                } 


                //data color processing induction
                $induction = odbc_result($result, 'Induction Expires');
                $inductionColor = 'black';
                if(strlen($induction) < 1){
                        $inductionColor = 'red';
                        $induction = 'CONTACT HR';
                }else{
                        //calc induction age
                        $date = DateTime::createFromFormat('Y-m-d',$induction);
                        $now = new DateTime();
                        $date = strtotime($date->format('Y/m/d'));
                        $now = strtotime($now->format('Y/m/d'));
                        $datediff = $date - $now;
                        $datediff = round($datediff / (60 * 60 * 24));

                        if ($datediff < 30){
                              $inductionColor = 'brown';
                        }
                        if ($datediff < 15){
                              $inductionColor = 'Orange';
                        }
                        if ($datediff < 0){
                              $inductionColor = 'red';
                        }
                        $induction = $induction . '<br>(' .($datediff). " Days)";
                } 

                //get badge color
                 $badgeColor = 'black';
                 $badge = odbc_result($result, 'Inside');
                 if($badge === "1"){
                    $badgeColor = 'green';    
                 }

                 if($badge === "1"){
                       $badgeText = '<a style="cursor: pointer;" data-toggle="tooltip"
                                      data-placement="top" title="'. odbc_result($result, 'Last Point Badged') .'">' . explode(' ',odbc_result($result, 'Last Point Badged'))[0].'<br>Entry</a>' ;
                 }else{
                       $badgeText = '<a style="cursor: pointer;" data-toggle="tooltip"
                                      data-placement="top" title="'. odbc_result($result, 'Last Point Badged') .'">' . explode(' ',odbc_result($result, 'Last Point Badged'))[0].'<br>Exit</a>' ;
                 }
                 
                 $EmpName = '<a style="cursor: pointer;" data-toggle="tooltip"
                                      data-placement="top" title="'. odbc_result($result, 'Company Number') . '">'.odbc_result($result, 'Employee Name').'</a><br>';;

                 $xTimeOperation = odbc_result($result, 'XTimeOperation');
                 $XtimeURL = '';
                  
                 //CDM
                 if ($xTimeOperation == 'CDM'){
                  $XtimeURL = '<br><a class="btn btn-outline-primary btn-sm" target="_blank" href="https://cdm-ser-tms-01.petragroup.local:8446/html/index.html#!/payhistory">Action</a>';
                 }
                  
                 //FDM
                 if ($xTimeOperation == 'FDM'){
                     $XtimeURL = '<br><a class="btn btn-outline-primary btn-sm" target="_blank" href="https://fin-ser-tms-01.petragroup.local:8443/html/index.html#!/payhistory">Action</a>';
                 }
                  
                 //KDM
                 if ($xTimeOperation == 'KDM'){
                     $XtimeURL = '<br><a class="btn btn-outline-primary btn-sm" target="_blank" href="https://kof-ser-tms-01.petragroup.local:8446/html/index.html#!/payhistory">Action</a>';
                 }

                 $leaveText = odbc_result($result, 'Upcoming Planned Leave');
                 
                 if (strlen($leaveText) > 1){
                        
                     $leaveText =    explode('-',$leaveText)[0].'<br>'.
                                    '<a style="cursor: pointer;" data-toggle="tooltip"
                                      data-placement="top" title="'. $leaveText . '" class="btn btn-outline-warning btn-sm">Action</a>';
                 }else{
                     $leaveText = '';
                 }

                // Get row data
                        echo('<tr>');
                        echo('<td>' . $EmpName . '</td>');
                        echo('<td class="text-center" style="color: '. $medicalColor .' ;">' . $medical . '</td>');
                        echo('<td class="text-center" style="color: '. $inductionColor .'">' . $induction . '</td>');
                        echo('<td class="text-center" style="color: '. $badgeColor .'">'.$badgeText.'</td>');
                        echo('<td class="text-center">' . $leaveText.'<a class="btn btn-outline-primary btn-sm" href="leave.php?CN='.odbc_result($result, 'Company Number').'">Balance</a>'.
                                     '</td>');
                        echo('<td class="text-center">' . odbc_result($result, 'XTimeExceptions') . "$XtimeURL" .'</td>');
                        echo('</tr>');
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
                echo ('</table>');?>


<form action="getCSV.php" method="post">
    <input type="hidden" name="csv_text" id="csv_text">
    <input type="submit" value="Get CSV File" onclick="getCSVData();">
</form>

<?php
                echo "<br>";
            
            //close container
            $data = $data. ']}';
            
            //print $data;
            //close sql connection
            odbc_free_result($result);
            odbc_close($db);
    ?>