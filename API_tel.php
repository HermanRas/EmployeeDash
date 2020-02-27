<?php
        require_once('config.php');
        $date = date_create()->modify('-30 days');
        $fDate = date_format($date, 'Y-m');

        //set filter date if available
        if (isset($_GET['fDate'])) {
                $fDate = $_GET['fDate'];
        }

        //connect to a DSN "myDSN" 
        $connection_string = "DRIVER={SQL Server};SERVER=DAT-SER-SQL-01.petragroup.local;DATABASE=EAI_PeopleUpdate"; 
        $db = odbc_connect($connection_string, "EAIEmployeeUpdate", "EAIEmployeeUpdate") or die ("could not connect<br />");
            //run sql query
            $stmt = "select top 1000 [EAI_PeopleUpdate].[dbo].[vPetraTelephoneUsage].*
                        from [EAI_PeopleUpdate].[dbo].[vPetraTelephoneUsage]
                        WHERE (ReportToLogonId = '$user' OR LogonId = '$user') and [YearMonth] = '$fDate' ";
            $result = odbc_exec($db, $stmt);
            if ($result == FALSE) die ("could not execute statement $stmt<br />");
            
            //open container
            $data =  '{';
            $data = $data. '"OperatorAvailabilityRecord":[';
            
            //start records
            //set loop counter
            $i = 1;
            echo '<h2 style="color:#444444; font-family:\'Lato\', Arial ,sans-serif; text-align:center; padding:0px; margin:0px;">'.$fDate. '</h2>';
            echo ('<table border="1" width="100%" style="border-spacing: 0 ;margin:auto;text-align:center;"><tr>');
                    echo('<th>' . "Employee Name" . '</th>');
                    echo('<th>' . "Tel Number" . '</th>');
                    echo('<th>' . "Call Cost" . '</th>');
                    echo('<th>' . "Detailed Account" . '</th>');
            echo('</tr>');
            
            while (odbc_fetch_row($result)) // while there are rows
            {  
                // Get row data
                        echo('<tr>');
                        echo('<td style="padding-left: 4px;padding-right: 4px;padding-top:0px;;text-align: left;font-weight: normal;">' . odbc_result($result, 'Display Name') . '</td>');
                        $fromNumber = odbc_result($result, 'FromTelNr') ;
                        $aLinkNumber = substr($fromNumber,-7,strlen($fromNumber));
                        echo('<td style="padding-left: 4px;padding-right: 4px;padding-top:0px;;text-align: center;font-weight: normal;">'. $fromNumber .'</td>');
                        echo('<td style="padding-left: 4px;padding-right: 4px;padding-top:0px;;text-align: right;font-weight: normal;">R ' . odbc_result($result, 'CallCost')  . '</td>');   
                        echo('<td style="padding-left: 4px;padding-right: 4px;padding-top:0px;;text-align: center;font-weight: normal;"><a href="./Details/viewReport.php?fdate='.$fDate.'&Nr='.$aLinkNumber.'" target="_Blank"> <img src="./img/DetailedBill.png" width="20px" alt="Detailed Bill"></a></td>');                    
                        echo('</tr>');
                }
                echo ('</table>');
                echo "<small>For a detailed printing report, please contact ICT</small><br>";
            
            //close container
            $data = $data. ']}';
            
            //print $data;
            //close sql connection
            odbc_free_result($result);
            odbc_close($db);
    ?>