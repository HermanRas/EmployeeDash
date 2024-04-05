<?php
        require_once('config.php');
        $date = date_create()->modify('-30 days');
        $fDate = date_format($date, 'Y-m');

        //set filter date if available
        if (isset($_GET['fDate'])) {
                $fDate = $_GET['fDate'];
        }

        $CNs = [];
        $companyNumbers = "''";
        if (isset($_GET['CNs'])){
                $CNs = $_GET['CNs'];
                $CNs = explode(',',$CNs);
                foreach ($CNs as $CN) {
                        $companyNumbers = $companyNumbers.",'$CN'";
                }
        }

        //connect to a DSN "myDSN" 
        $connection_string = "DRIVER={SQL Server};SERVER=DAT-SER-SQL-01.petragroup.local;DATABASE=EAI_PeopleUpdate"; 
        $db = odbc_connect($connection_string, "EAIEmployeeUpdate", "EAIEmployeeUpdate") or die ("could not connect<br />");
            //run sql query
            $stmt = "SELECT [EAI_PeopleUpdate].[dbo].[vPetraTelephoneUsage].*
                        FROM [EAI_PeopleUpdate].[dbo].[vPetraTelephoneUsage]
                        WHERE (ReportToLogonId = '$user' 
                                OR LogonId = '$user'
                                OR CompanyNumber in ($companyNumbers)
                              ) and [YearMonth] = '$fDate' ";
            $result = odbc_exec($db, $stmt);
            if ($result == FALSE) die ("could not execute statement $stmt<br />");
            
            //open container
            $data =  '{';
            $data = $data. '"OperatorAvailabilityRecord":[';
            
            //start records
            //set loop counter
            $i = 1;
            echo '<h2>'.$fDate. '</h2>';
            echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
                    echo('<th>' . "Employee Name" . '</th>');
                    echo('<th>' . "Tel Number" . '</th>');
                    echo('<th>' . "Call Cost" . '</th>');
                    echo('<th>' . "Detailed Account" . '</th>');
            echo('</tr></thead><tbody>');
            
            while (odbc_fetch_row($result)) // while there are rows
            {  
                // Get row data
                        echo('<tr>');
                        echo('<td>' . odbc_result($result, 'Display Name') . '</td>');
                        $fromNumber = odbc_result($result, 'FromTelNr') ;
                        $aLinkNumber = substr($fromNumber,-7,strlen($fromNumber));
                        echo('<td>'. $fromNumber .'</td>');
                        echo('<td>R ' . sprintf("%01.2f",odbc_result($result, 'CallCost'))  . '</td>');   
                        echo('<td><a href="https://dat-ser-web-01.petragroup.local/EmployeeDash/telephone_details.php?fdate='.$fDate.'&Nr='.$aLinkNumber.'">Detailed Bill</a></td>');                    
                        echo('</tr>');
                }
                echo ('</tbody>');
                echo ('</table>');
                echo "<small>For a detailed printing report, please contact ICT</small><br>";
?>

<?php
            //close container
            $data = $data. ']}';
            
            //print $data;
            //close sql connection
            odbc_free_result($result);
            odbc_close($db);
    ?>