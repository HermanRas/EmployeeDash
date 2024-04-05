<?php
include_once('config.php');

$date = date_create()->modify('-30 days');
$fDate = date_format($date, 'Y/m');

//set filter date if available
if (isset($_GET['fDate'])) {
        $fDate = $_GET['fDate'];
}

//connect to a DSN "myDSN" 
include_once('config/db_query.php');
//run sql query
$sql = "SELECT 
        CalendarPeriod as 'Period',
        PageCount as 'Pages',
        CONVERT(DECIMAL(10,2),Amount) as Amount,
        Type as 'Colour',
        trxtype as 'JobType',
        DisplayName,
        EmployeeEmailAddress  
        from [eqcas].[dbo].[vALL]
        where (LogonID = '$user'
        OR ReportToLogonId = '$user')
        and CalendarPeriod = '$fDate'
        order by DisplayName,JobType asc";
$args = [];
$result = sqlQuery($sql, $args, 'eqcas');
if ($result[0] == FALSE) die("could not execute statement $sql<br />");

//open container
$data =  '{';
$data = $data . '"OperatorAvailabilityRecord":[';

//start records
//set loop counter
$i = 1;
echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
echo ('<th>' . "Full Name" . '</th>');
echo ('<th>' . "Period" . '</th>');
echo ('<th>' . " Pages " . '</th>');
echo ('<th>' . " Amount " . '</th>');
echo ('<th>' . "Colour" . '</th>');
echo ('<th>' . " Job Type " . '</th>');
echo ('</tr></thead><tbody>');
foreach ($result[0] as $rec) {
        // Get row data
        echo ('<tr>');
        echo ('<td>' . $rec['DisplayName'] . '</td>');
        echo ('<td>' . $rec['Period'] . '</td>');
        echo ('<td>' . $rec['Pages'] . '</td>');
        $am = $rec['Amount'];
        $am =  number_format($am, 2, ".", " ");
        echo ('<td>R ' . $am . '</td>');
        echo ('<td>' . $rec['Colour'] . '</td>');
        echo ('<td>' . $rec['JobType'] . '</td>');
        echo ('</tr>');
}
echo ('</tbody>');
echo ('</table>');
echo "<small>For a detailed printing report, please contact ICT</small><br>";
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