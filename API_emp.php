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
            $stmt = "select 
                        DisplayName as 'Employee Name',
                        CompanyNumber as 'Company Number',
                        Medial_FollowUpDate as 'Medical Expires',
                        Induction_FollowUpdate as 'Induction Expires',
                        Access_LastDatePlaceBadged as 'Last Point Badged',
                        UpcomingPlannedLeave as 'Upcoming Planned Leave',
                        Inside

                        from [EAI_PeopleUpdate].[dbo].[vPetraEmployeeStatus_web]
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
            echo('<th>' . "Company Number" . '</th>');
            echo('<th>' . "Medical Expires" . '</th>');
            echo('<th>' . "Induction Expires" . '</th>');
            echo('<th>' . "Last Point Badged" . '</th>');
            echo('<th>' . "Upcoming Planned Leave" . '</th>');
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
                        $date = DateTime::createFromFormat('Y/m/d',$medical);
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

                        $medical = $medical . ' (' .($datediff). " Days)";
                } 


                //data color processing induction
                $induction = odbc_result($result, 'Induction Expires');
                $inductionColor = 'black';
                if(strlen($induction) < 1){
                        $inductionColor = 'red';
                        $induction = 'CONTACT HR';
                }else{
                        //calc induction age
                        $date = DateTime::createFromFormat('Y/m/d',$induction);
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
                        $induction = $induction . ' (' .($datediff). " Days)";
                } 

                //get badge color
                 $badgeColor = 'black';
                 $badge = odbc_result($result, 'Inside');
                 if($badge === "1"){
                    $badgeColor = 'green';    
                 }


                // Get row data
                        echo('<tr>');
                        echo('<td>' . odbc_result($result, 'Employee Name') . '</td>');
                        echo('<td>' . odbc_result($result, 'Company Number') . '</td>');
                        echo('<td style="color: '. $medicalColor .' ;">' . $medical . '</td>');
                        echo('<td style="color: '. $inductionColor .'">' . $induction . '</td>');
                        echo('<td style="color: '. $badgeColor .'">' . odbc_result($result, 'Last Point Badged') . '</td>');
                        echo('<td>' . odbc_result($result, 'Upcoming Planned Leave') . ' </td>');                                              
                        echo('</tr>');
                }
                echo ('</tbody>');
                echo ('<tfoot><tr>');
                echo('<th>' . "Employee Name" . '</th>');
                echo('<th>' . "Company Number" . '</th>');
                echo('<th>' . "Medical Expires" . '</th>');
                echo('<th>' . "Induction Expires" . '</th>');
                echo('<th>' . "Last Point Badged" . '</th>');
                echo('<th>' . "Upcoming Planned Leave" . '</th>');
                 echo('</tr></tfoot></table>');
                echo "<br>";
            
            //close container
            $data = $data. ']}';
            
            //print $data;
            //close sql connection
            odbc_free_result($result);
            odbc_close($db);
    ?>