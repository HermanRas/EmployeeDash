<?php
require_once('config.php');
$date = date_create()->modify('-30 days');
$fDate = date_format($date, 'Y-m');

//set filter date if available
if (isset($_GET['fDate'])) {
        $fDate = $_GET['fDate'];
}

$CNs = [];
$companyNumbers = "''";
if (isset($_GET['CNs'])) {
        $CNs = $_GET['CNs'];
        $CNs = explode(',', $CNs);
        foreach ($CNs as $CN) {
                $companyNumbers = $companyNumbers . ",'$CN'";
        }
}

//connect to a DSN "myDSN" 
include_once('config/db_query.php');
//run sql query
$sql = "SELECT [EAI_PeopleUpdate].[dbo].[vPetraTelephoneUsage].*
                        FROM [EAI_PeopleUpdate].[dbo].[vPetraTelephoneUsage]
                        WHERE (ReportToLogonId = '$user' 
                                OR LogonId = '$user'
                                OR CompanyNumber in ($companyNumbers)
                              ) and [YearMonth] = '$fDate' ";
$args = [];
$result = sqlQuery($sql, $args, 'EAI_PeopleUpdate');
if ($result[0] == FALSE) die("could not execute statement $sql<br />");

//open container
$data =  '{';
$data = $data . '"OperatorAvailabilityRecord":[';

//start records
//set loop counter
$i = 1;
echo '<h2>' . $fDate . '</h2>';
echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
echo ('<th>' . "Employee Name" . '</th>');
echo ('<th>' . "Tel Number" . '</th>');
echo ('<th>' . "Call Cost" . '</th>');
echo ('<th>' . "Detailed Account" . '</th>');
echo ('</tr></thead><tbody>');

//loop through results
foreach ($result[0] as $rec) {
        // Get row data
        echo ('<tr>');
        echo ('<td>' . $rec['Display Name'] . '</td>');
        $fromNumber = $rec['FromTelNr'];
        $aLinkNumber = substr($fromNumber, -11, strlen($fromNumber));
        echo ('<td>' . $fromNumber . '</td>');
        echo ('<td>R ' . sprintf("%01.2f", $rec['CallCost'])  . '</td>');
        echo ('<td><a href="telephone_details.php?fdate=' . $fDate . '&Nr=' . $aLinkNumber . '">Detailed Bill</a></td>');
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