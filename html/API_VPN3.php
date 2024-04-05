<?php
require_once('config.php');
$date = date_create()->modify('-1 days');
$fDate = date_format($date, 'Y-m');

//set filter date if available
if (isset($_GET['fDate'])) {
        $fDate = $_GET['fDate'];
}

//connect to a DSN "myDSN" 
include_once('config/db_query.php');
//run sql query
$sql = "   SELECT
                vVPN3MonthResults.Period,
                vVPN3MonthResults.Username,
                vVPN3MonthResults.Total_Minutes,
                vVPN3MonthResults.[HH:MM],
                vVPN3MonthResults.CompanyNumber,
                vVPN3MonthResults.ReportToLogonId,
                vVPN3MonthResults.[Display Name]
                From
                vVPN3MonthResults
                Where
                (vVPN3MonthResults.Username = '$user' Or
                vVPN3MonthResults.ReportToLogonId = '$user') and Period like '$fDate%' ";
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
echo ('<th style="text-align: right;">' . "Total Min Connected" . '</th>');
echo ('<th style="text-align: right;">' . "Duration" . '</th>');
echo ('<th style="text-align: right;">' . "Details" . '</th>');
echo ('</tr></thead><tbody>');

foreach ($result[0] as $rec) {
        // Get row data
        echo ('<tr>');
        echo ('<td>' . $rec['Display Name'] . '</td>');
        echo ('<td style="text-align: right;">' . $rec['Total_Minutes'] . '</td>');
        echo ('<td style="text-align: right;">' . $rec['HH:MM'] . '</td>');
        $Username = $rec['Username'];
        echo ('<td style="text-align: right;"><a href="vpn3_details.php?fDate=' . $fDate . '&Username=' . $Username . '">Detailed Bill</a></td>');
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