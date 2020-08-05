<?php
        include_once('config.php');

        $date = date_create()->modify('-30 days');
        $fDate = date_format($date, 'Y/m');

        //set filter date if available
        if (isset($_GET['fDate'])) {
                $fDate = $_GET['fDate'];
        }

        //connect to a DSN "myDSN" 
        $connection_string = "DRIVER={SQL Server};SERVER=DAT-SER-SQL-01.petragroup.local;DATABASE=eqcas"; 
        $db = odbc_connect($connection_string, "eqcas", "eqcas") or die ("could not connect<br />");
            //run sql query
            $stmt = "select CalendarPeriod as 'Period', PageCount as 'Pages', CONVERT(DECIMAL(10,2),Amount) as Amount, Type as 'Colour', trxtype as 'JobType', DisplayName as 'Full Name', EmployeeEmailAddress  from [eqcas].[dbo].[vALL]
                        where (LogonID = '$user'
                        OR ReportToLogonId = '$user')
                        and CalendarPeriod = '$fDate'
                        order by DisplayName,JobType asc";
            $result = odbc_exec($db, $stmt);
            if ($result == FALSE) die ("could not execute statement $stmt<br />");
            
            //open container
            $data =  '{';
            $data = $data. '"OperatorAvailabilityRecord":[';
            
            //start records
            //set loop counter
            $i = 1;
            echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
                    echo('<th>' . "Full Name" . '</th>');
                    echo('<th>' . "Period" . '</th>');
                    echo('<th>' . " Pages " . '</th>');
                    echo('<th>' . " Amount " . '</th>');
                    echo('<th>' . "Colour" . '</th>');
                    echo('<th>' . " Job Type " . '</th>');
            echo('</tr></thead><tbody>');
            while (odbc_fetch_row($result)) // while there are rows
            {  
                // Get row data
                        echo('<tr>');
                        echo('<td>' . odbc_result($result, 'Full Name') . '</td>');
                        echo('<td>' . odbc_result($result, 'Period') . '</td>');
                        echo('<td>' . odbc_result($result, 'Pages') . '</td>');
                        $am = odbc_result($result, 'Amount');
                        $am =  number_format($am, 2,".", " ");    
                        echo('<td>R ' . $am . '</td>');
                        echo('<td>' . odbc_result($result, 'Colour') . '</td>');
                        echo('<td>' . odbc_result($result, 'JobType') . '</td>');
                        echo('</tr>');
                }
                echo ('</tbody>');
                echo('</table>');
                echo "<small>For a detailed printing report, please contact ICT</small><br>";
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