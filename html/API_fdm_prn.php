<?php
include_once('config.php');

$date = date_create()->modify('-30 days');
$fDate = date_format($date, 'Y/m');

//set filter date if available
if (isset($_GET['fDate'])) {
        $fDate = $_GET['fDate'];
}

//connect to a DSN "myDSN" 
$connection_string = "DRIVER={SQL Server};SERVER=DAT-SER-SQL-01.petragroup.local;DATABASE=eqcas";
$db = odbc_connect($connection_string, "eqcas", "eqcas") or die("could not connect<br />");
//run sql query
$stmt = "SELECT
                        v3_FDM.CalendarPeriod,
                        v3_FDM.PageCount,
                        v3_FDM.Amount,
                        v3_FDM.Type,
                        v3_FDM.trxtype,
                        v3_FDM.CompanyNumber,
                        v3_FDM.DisplayName,
                        v3_FDM.EmployeeEmailAddress,
                        v3_FDM.JobTitle,
                        v3_FDM.Department,
                        v3_FDM.ContractingCompany,
                        v3_FDM.ReportToFullName,
                        v3_FDM.ReportToEmailAddress
                        From
                        v3_FDM
                        order by CalendarPeriod,CompanyNumber asc";
$result = odbc_exec($db, $stmt);

//open container
$data =  '{';
$data = $data . '"OperatorAvailabilityRecord":[';

//start records
//set loop counter
$i = 1;
echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
echo ('<th>' . "Period" . '</th>');
echo ('<th>' . "PageCount" . '</th>');
echo ('<th>' . " Amount " . '</th>');
echo ('<th>' . " WB / COLOR " . '</th>');
echo ('<th>' . "Copy / Print" . '</th>');
echo ('<th>' . "CompanyNumber" . '</th>');
echo ('<th>' . "DisplayName" . '</th>');
echo ('<th>' . "Department" . '</th>');
echo ('<th>' . "ContractingCompany" . '</th>');
echo ('<th>' . "ReportToFullName" . '</th>');
echo ('</tr></thead><tbody>');
while (odbc_fetch_row($result)) // while there are rows
{
        // Get row data
        echo ('<tr>');
        echo ('<td>' . odbc_result($result, 'CalendarPeriod') . '</td>');
        echo ('<td>' . odbc_result($result, 'PageCount') . '</td>');
        $am = odbc_result($result, 'Amount');
        $am =  number_format($am, 2, ".", " ");
        echo ('<td>R ' . $am . '</td>');
        echo ('<td>' . odbc_result($result, 'Type') . '</td>');
        echo ('<td>' . odbc_result($result, 'trxtype') . '</td>');
        echo ('<td>' . odbc_result($result, 'CompanyNumber') . '</td>');
        echo ('<td>' . odbc_result($result, 'DisplayName') . '</td>');
        echo ('<td>' . odbc_result($result, 'Department') . '</td>');
        echo ('<td>' . odbc_result($result, 'ContractingCompany') . '</td>');
        echo ('<td>' . odbc_result($result, 'ReportToFullName') . '</td>');
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
//close sql connection
odbc_free_result($result);
odbc_close($db);
?>