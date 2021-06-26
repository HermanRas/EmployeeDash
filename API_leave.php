<?php
        $CN = '';
        $date = date_create()->modify('-30 days');
        $fDate = date_format($date, 'Y-m');


        //set filter Nr if available
        if (isset($_GET['CN'])) {
                $CN = $_GET['CN'];
        }

        //connect to a DSN "myDSN" 
        $connection_string = "DRIVER={SQL Server};SERVER=DAT-SER-SQL-01.petragroup.local;DATABASE=PetraAPP"; 
        $db = odbc_connect($connection_string, "EAIEmployeeUpdate", "EAIEmployeeUpdate") or die ("could not connect<br />");

            //run sql query
            $stmt = "SELECT top 1000 [PetraAPP].[dbo].[vLeaveBalances].* from [PetraAPP].[dbo].[vLeaveBalances]
                        WHERE [EmployeeCode] = '$CN'
                        ORDER BY ShortDescription ASC;";
            $result = odbc_exec($db, $stmt);
            if ($result == FALSE) die ("could not execute statement $stmt<br />");
            
            //run sql query
            $stmt = "SELECT top 1 [PetraAPP].[dbo].[vLeaveBalances].* from [PetraAPP].[dbo].[vLeaveBalances]
                        WHERE [EmployeeCode] = '$CN'
                        ORDER BY ShortDescription ASC;";
            $res = odbc_exec($db, $stmt);
            if ($res == FALSE) die ("could not execute statement $stmt<br />");
            
            //open container
            $data =  '{';
            $data = $data. '"OperatorAvailabilityRecord":[';
            
            //start records
            //set loop counter
            $i = 1;
            echo '<h2>'. odbc_result($res, 'DisplayName') . ' - ' .$fDate. '</h2>';
            echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
                    echo('<th>' . "Leave Type" . '</th>');
                    echo('<th>' . "Entitlement" . '</th>');
                    echo('<th>' . "Due@Start" . '</th>');
                    echo('<th>' . 'Allocated' . '</th>');
                    echo('<th>' . "Taken" . '</th>');
                    echo('<th>' . "Due@End" . '</th>');
            echo('</tr></thead><tbody>');
            
            while (odbc_fetch_row($result)) // while there are rows
            {  
                // Get row data
                        echo('<tr>');
                        echo('<td>' . odbc_result($result, 'ShortDescription') . '</td>');
                        echo('<td style="text-align:right;">' . odbc_result($result, 'Ent') . '</td>');
                        echo('<td style="text-align:right;">' . odbc_result($result, 'Due@Start')  . '</td>');
                        echo('<td style="text-align:right;">' . odbc_result($result, 'AllocatedTo')  . '</td>');
                        echo('<td style="text-align:right;">' . odbc_result($result, 'Taken')  . '</td>');
                        echo('<td style="text-align:right;">' . odbc_result($result, 'Due@End')  . '</td>');
                        echo('</tr>');
                }
                echo ('</tbody>');
                echo('</table>');
                echo "<br>";
?>
<form action="getCSV.php" method="post">
    <input type="hidden" name="csv_text" id="csv_text">
    <input type="submit" value="Get CSV File" onclick="getCSVData();">
</form>
<?php
            //close container
            $data = $data. ']}';
            
            //print $data;
            //close sql connection
            odbc_free_result($result);
            odbc_close($db);
    ?>