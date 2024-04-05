<?php
require_once('config.php');
$date = date_create()->modify('-30 days');
$fDate = date_format($date, 'Y-m');

//set filter date if available
if (isset($_GET['fDate'])) {
        $fDate = $_GET['fDate'];
}

//connect to a DSN "myDSN" 
include_once('config/db_query.php');
//run sql query
$sql = "SELECT * from [vBandwidthReportToVIPDetail]
                        WHERE (ReportToLogonId = '$user' OR LogonId = '$user') and [YearMonth] = '$fDate' ";
$args = [];
$result = sqlQuery($sql, $args, 'EAI_PeopleUpdate');

//open container
$data =  '{';
$data = $data . '"OperatorAvailabilityRecord":[';

//start records
//set loop counter
$i = 1;
echo '<h2>' . $fDate . '</h2>';
echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
echo ('<th>' . "Employee Name" . '</th>');
echo ('<th>' . "Megs Used" . '</th>');
echo ('<th>' . "Sessions" . '</th>');
echo ('<th>' . "Downloader" . '</th>');
echo ('<th>' . "Bandwidth Usage" . '</th>');
echo ('</tr></thead><tbody>');

foreach ($result[0] as $rec) {
        // Get row data
        echo ('<tr>');
        echo ('<td>' . $rec['Display Name'] . '</td>');
        echo ('<td class="text-right">' .  number_format($rec['MB']) . ' MB </td>');
        echo ('<td class="text-right">' . number_format($rec['Sessions']) . '</td>');
        echo ('<td>' . $rec['Downloader'] . '</td>');
        echo ('<td>' . $rec['Usage'] . '</td>');
        echo ('</tr>');
}
echo ('</tbody>');
echo ('</table>');
echo "<small>For a detailed Internet report, please contact ICT</small><br>";
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