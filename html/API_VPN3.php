<?php
require_once('config.php');
$date = date_create()->modify('-1 days');
$fDate = date_format($date, 'Y-m');

//set filter date if available
if (isset($_GET['fDate'])) {
        $fDate = $_GET['fDate'];
}

//connect to a DSN "myDSN" 
$connection_string = "DRIVER={SQL Server};SERVER=DAT-SER-SQL-01.petragroup.local;DATABASE=VPN2";
$db = odbc_connect($connection_string, "EAIEmployeeUpdate", "EAIEmployeeUpdate") or die("could not connect<br />");
//run sql query
$stmt = "   SELECT
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
$result = odbc_exec($db, $stmt);
if ($result == FALSE) die("could not execute statement $stmt<br />");

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

while (odbc_fetch_row($result)) // while there are rows
{
        // Get row data
        echo ('<tr>');
        echo ('<td>' . odbc_result($result, 'Display Name') . '</td>');
        echo ('<td style="text-align: right;">' . odbc_result($result, 'Total_Minutes') . '</td>');
        echo ('<td style="text-align: right;">' . odbc_result($result, 'HH:MM') . '</td>');
        $Username = odbc_result($result, 'Username');
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
//close sql connection
odbc_free_result($result);
odbc_close($db);
?>