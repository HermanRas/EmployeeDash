<?php
$CN = '';
$date = date_create()->modify('-30 days');
$fDate = date_format($date, 'Y-m');


//set filter Nr if available
if (isset($_GET['CN'])) {
        $CN = $_GET['CN'];
}

//connect to a DSN "myDSN" 
include_once('config/db_query.php');
//run sql query
$sql = "SELECT top 1000 [PetraAPP].[dbo].[vLeaveBalances].* from [PetraAPP].[dbo].[vLeaveBalances]
                        WHERE [EmployeeCode] = '$CN'
                        ORDER BY ShortDescription ASC;";
$args = [];
$result = sqlQuery($sql, $args, 'EAI_PeopleUpdate');

//run sql query
$sql = "SELECT top 1 [PetraAPP].[dbo].[vLeaveBalances].* from [PetraAPP].[dbo].[vLeaveBalances]
                        WHERE [EmployeeCode] = '$CN'
                        ORDER BY ShortDescription ASC;";
$args = [];
$result2 = sqlQuery($sql, $args, 'EAI_PeopleUpdate');

//open container
$data =  '{';
$data = $data . '"OperatorAvailabilityRecord":[';

//start records
//set loop counter
$i = 1;
echo '<h2>' . $result2[0][0]['DisplayName'] . ' - ' . $fDate . '</h2>';
echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
echo ('<th>' . "Leave Type" . '</th>');
echo ('<th>' . "Entitlement" . '</th>');
echo ('<th>' . "Due@Start" . '</th>');
echo ('<th>' . 'Allocated' . '</th>');
echo ('<th>' . "Taken" . '</th>');
echo ('<th>' . "Due@End" . '</th>');
echo ('</tr></thead><tbody>');

foreach ($result[0] as $rec) {
        // Get row data
        echo ('<tr>');
        echo ('<td>' . $rec['ShortDescription'] . '</td>');
        echo ('<td style="text-align:right;">' . $rec['Ent'] . '</td>');
        echo ('<td style="text-align:right;">' . $rec['Due@Start']  . '</td>');
        echo ('<td style="text-align:right;">' . $rec['AllocatedTo']  . '</td>');
        echo ('<td style="text-align:right;">' . $rec['Taken']  . '</td>');
        echo ('<td style="text-align:right;">' . $rec['Due@End']  . '</td>');
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