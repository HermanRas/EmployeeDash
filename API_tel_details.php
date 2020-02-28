<?php
        $Nr = '';
        $date = date_create()->modify('-30 days');
        $fdate = date_format($date, 'Y-m');

        //set filter date if available
        if (isset($_GET['fdate'])) {
                $fdate = $_GET['fdate'];
        }

        //set filter Nr if available
        if (isset($_GET['Nr'])) {
                $Nr = $_GET['Nr'];
        }

        //connect to a DSN "myDSN" 
        $connection_string = "DRIVER={SQL Server};SERVER=cdm-SER-SQL-02.petragroup.local;DATABASE=ARCTel"; 
        $db = odbc_connect($connection_string, "ARCTel", "ARCTel") or die ("could not connect<br />");
            //run sql query
            $stmt = "select * from [ARCTel].[dbo].[vFromNr_ALL]
                        WHERE [FromNr] = '$Nr' AND  datetime like '%$fdate%' 
                        order by DATETime DESC;";
            $result = odbc_exec($db, $stmt);
            if ($result == FALSE) die ("could not execute statement $stmt<br />");
            
            //open container
            $data =  '{';
            $data = $data. '"OperatorAvailabilityRecord":[';
            
            //start records
            //set loop counter
            $i = 1;
            echo '<h2>'.$fdate. '</h2>';
            echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
                    echo('<th>' . "DateTime" . '</th>');
                    echo('<th>' . "From" . '</th>');
                    echo('<th>' . "To" . '</th>');
                    echo('<th>' . 'Duration<span style="font-size:10px;"> (h.m:sec)</span>' . '</th>');
                    echo('<th>' . "Cost" . '</th>');
            echo('</tr></thead><tbody>');
            
            while (odbc_fetch_row($result)) // while there are rows
            {  
                // Get row data
                        echo('<tr>');
                        echo('<td>' . odbc_result($result, 'DateTime') . '</td>');
                        echo('<td>' . odbc_result($result, 'FromTelNr') . '</td>');
                        echo('<td>' . odbc_result($result, 'ToNr')  . '</td>');                       
                        echo('<td>' . odbc_result($result, 'CallDurationTime')  . '</td>');     

                        $callCost = odbc_result($result, 'CallCost');
                        if($callCost[0] == "." ){
                          $callCost = '0'.$callCost;      
                        }                  
                        echo('<td>R ' .  $callCost . '</td>');                       
                        echo('</tr>');
                }

                
            $stmt2 = "select sum([CallCost]) as 'Cost'
                        ,(SUM(DATEDIFF(second, '0:00:00', CallDurationTime))/60) as 'Time' 
                        from [ARCTel].[dbo].[vFromNr_ALL]
                      WHERE [FromNr] = '$Nr' AND  datetime like '%$fdate%';";
            $result2 = odbc_exec($db, $stmt2);

            while (odbc_fetch_row($result2)){
                        echo('<tr>');
                        echo('<td>&nbsp</td>' );
                        echo('<td>&nbsp</td>' );
                        echo('<td>&nbsp</td>' );
                        echo('<td><b>Total Time: ' . odbc_result($result2, 'Time')  . 'min</b></td>');     
                        echo('<td><b>Total Cost: R' . odbc_result($result2, 'Cost')  . '</b></td>');                       
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