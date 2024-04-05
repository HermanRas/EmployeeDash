<?php
require_once('config.php');
$date = date_create()->modify('-30 days');
$fDate = date_format($date, 'Y-m');

//set filter date if available
if (isset($_GET['fDate'])) {
        $fDate = $_GET['fDate'];
}

$Username = '';
//set filter date if available
if (isset($_GET['Username'])) {
        $Username = $_GET['Username'];
}

//connect to a DSN "myDSN" 
include_once('config/db_query.php');
//run sql query
$sql = "   SELECT
                vVPN3DetailedResults.Period,
                vVPN3DetailedResults.Username,
                vVPN3DetailedResults.CalendarDate,
                vVPN3DetailedResults.DateTimeIn,
                vVPN3DetailedResults.DateTimeOut,
                vVPN3DetailedResults.Rounded_Hours,
                vVPN3DetailedResults.Total_Minutes,
                vVPN3DetailedResults.[HH:MM],
                vVPN3DetailedResults.CompanyNumber,
                vVPN3DetailedResults.ReportToLogonId,
                vVPN3DetailedResults.[Display Name]
                From
                vVPN3DetailedResults
                Where
                vVPN3DetailedResults.Username Like '$Username' and Period like '$fDate%' ";
$args = [];
$result = sqlQuery($sql, $args, 'VPN2');

//open container
$data =  '{';
$data = $data . '"OperatorAvailabilityRecord":[';

//start records
//set loop counter
$i = 1;
echo '<h2>' . $fDate . '</h2>';
echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
echo ('<th style="text-align: left;">' . "Employee Name" . '</th>');
echo ('<th style="text-align: right;">' . "Date Connected" . '</th>');
echo ('<th style="text-align: right;">' . "Date Disconnected" . '</th>');
echo ('<th style="text-align: right;">' . "Total Min Connected" . '</th>');
echo ('<th style="text-align: right;">' . "Duration" . '</th>');
echo ('</tr></thead><tbody>');

foreach ($result[0] as $rec) {
        // Get row data
        echo ('<tr>');
        echo ('<td>' . $rec['Display Name'] . '</td>');
        echo ('<td style="text-align: right;">' . substr($rec['DateTimeIn'], 0, 16) . '</td>');
        echo ('<td style="text-align: right;">' .  substr($rec['DateTimeOut'], 0, 16) . '</td>');
        echo ('<td style="text-align: right;">' . $rec['Total_Minutes'] . '</td>');
        echo ('<td style="text-align: right;">' . $rec['HH:MM'] . '</td>');
        echo ('</tr>');
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
$data = $data . ']}';

//print $data;
?>