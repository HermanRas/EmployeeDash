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
        $connection_string = "DRIVER={SQL Server};SERVER=DAT-SER-SQL-01.petragroup.local;DATABASE=VPN2"; 
        $db = odbc_connect($connection_string, "EAIEmployeeUpdate", "EAIEmployeeUpdate") or die ("could not connect<br />");
            //run sql query
            $stmt = "   SELECT
                                vVPN3_Logs.[Display Name],
                                vVPN3_Logs.tSurname,
                                vVPN3_Logs.tCalendarDate,
                                vVPN3_Logs.tDateTimeOn,
                                vVPN3_Logs.tDateTimeOff,
                                vVPN3_Logs.tDuration,
                                vVPN3_Logs.tInputOctets,
                                vVPN3_Logs.tOutputOctets,
                                vVPN3_Logs.CompanyNumber,
                                vVPN3_Logs.Operation,
                                vVPN3_Logs.Directorate,
                                vVPN3_Logs.Department,
                                vVPN3_Logs.ReportToLogonId
                                From
                                vVPN3_Logs
                        WHERE (ReportToLogonId = '$user' 
                                OR tSurname = '$user'
                                OR CompanyNumber in ($companyNumbers)
                              ) and tDateTimeOn like '$fDate%' ";
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
                    echo('<th>' . "Time Connected" . '</th>');
                    echo('<th>' . "Duration" . '</th>');
                    echo('<th>' . "Data Used" . '</th>');
                    echo('<th>' . "Price<small><br>Est Mobile Data Rate</small>" . '</th>');
            echo('</tr></thead><tbody>');
            
            while (odbc_fetch_row($result)) // while there are rows
            {  
                // Get row data
                        echo('<tr>');
                        echo('<td>' . odbc_result($result, 'Display Name') . '</td>');
                        echo('<td>' . odbc_result($result, 'tDateTimeOn') . '</td>');
                        echo('<td>' . odbc_result($result, 'tDuration') . '</td>');
                        $inData = odbc_result($result, 'tInputOctets');
                        $outData = odbc_result($result, 'tOutputOctets');
                        $totalData = $inData + $outData ;
                        $totalData = $totalData / 1000000;
                        echo('<td class="text-left">' . sprintf("%01.2f", $totalData)  . ' Mb</td>');
                        $price = $totalData * 0.20;
                        echo('<td>R ' . sprintf("%01.2f", $price) . '</td>');                     
                        echo('</tr>');
                }
                echo ('</tbody>');
                echo ('</table>');
                echo "<small>For data older then 3 months, please contact ICT</small><br>";
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