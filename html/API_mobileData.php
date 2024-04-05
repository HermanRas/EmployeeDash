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
$sql = "SELECT [EAI_PeopleUpdate].[dbo].[v3GDataReportToVIPDetail].*
                from [EAI_PeopleUpdate].[dbo].[v3GDataReportToVIPDetail]
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
echo ('<th>' . "Data Cost" . '</th>');
echo ('</tr></thead><tbody>');

foreach ($result[0] as $rec) {
        // Get row data
        echo ('<tr>');
        echo ('<td>' . $rec['Display Name'] . '</td>');
        echo ('<td class="text-right">' . $rec['MB'] . ' MB </td>');
        echo ('<td class="text-right">R ' . sprintf("%01.2f", $rec['PricePerMeg'])  . '</td>');
        echo ('</tr>');
}
echo ('</tbody>');
echo ('</table>');
echo "<small>For a detailed 3G reports, please contact ICT</small><br>";
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
?>