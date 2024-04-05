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
include_once('config/db_query.php');
//run sql query
$sql = "SELECT * from [ARCTel].[dbo].[vFromNr_ALL]
                        WHERE [FromNr] Like '%$Nr' AND  datetime like '%$fdate%' 
                        order by DATETime DESC;";
$args = [];
$result = sqlQuery($sql, $args, 'ARCTel');
if ($result[0] == FALSE) die("could not execute statement $sql<br />");

//open container
$data =  '{';
$data = $data . '"OperatorAvailabilityRecord":[';

//start records
//set loop counter
$i = 1;
echo '<h2>' . $fdate . '</h2>';
echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
echo ('<th>' . "DateTime" . '</th>');
echo ('<th>' . "From" . '</th>');
echo ('<th>' . "To" . '</th>');
echo ('<th>' . 'Duration<span style="font-size:10px;"> (h.m:sec)</span>' . '</th>');
echo ('<th>' . "Cost" . '</th>');
echo ('</tr></thead><tbody>');

foreach ($result[0] as $rec) {
        // Get row data
        echo ('<tr>');
        echo ('<td>' . $rec['DateTime'] . '</td>');
        echo ('<td>' . $rec['FromNr'] . '</td>');
        echo ('<td>' . $rec['ToNr']  . '</td>');
        echo ('<td>' . $rec['CallDurationTime']  . '</td>');

        $callCost = $rec['CallCost'];
        if ($callCost[0] == ".") {
                $callCost = '0' . $callCost;
        }
        echo ('<td>R ' .  $callCost . '</td>');
        echo ('</tr>');
}


$sql = "SELECT sum([CallCost]) as 'Cost'
                        ,(SUM(DATEDIFF(second, '0:00:00', CallDurationTime))/60) as 'Time' 
                        from [ARCTel].[dbo].[vFromNr_ALL]
                      WHERE [FromNr] like '%$Nr' AND  datetime like '%$fdate%';";
$args = [];
$result2 = sqlQuery($sql, $args, 'ARCTel');
if ($result2[0] == FALSE) die("could not execute statement $sql<br />");

foreach ($result2[0] as $rec) {

        echo ('<tr>');
        echo ('<td>&nbsp</td>');
        echo ('<td>&nbsp</td>');
        echo ('<td>&nbsp</td>');
        echo ('<td><b>Total Time: ' . $rec['Time']  . 'min</b></td>');
        echo ('<td><b>Total Cost: R' . sprintf("%01.2f", $rec['Cost'])  . '</b></td>');
        echo ('</tr>');
}
echo ('</tbody>');
echo ('</table>');
echo "<br>";
?>
<form action="getCSV.php" method="post">
        <input type="hidden" name="csv_text" id="csv_text">
        <input type="submit" value="Get CSV File" onclick="getCSVData();">
</form>
<?php
//close container
$data = $data . ']}';

//print $data;
?>