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
// Connection to sqlite using PDO and set error mode
$dsn = 'sqlite:MessageLog.sqlite';
$conn = new PDO($dsn);

// enable errors
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//connect to a DSN "myDSN" 
$sql = "SELECT substr(SendTime,0,17) as 'SendTime', MessageFrom, MessageTo, MessageText  
                from MessageOut 
                Where ConnectorId like '2'
                and SendTime like '$fDate%'";
$result = $conn->query($sql);
$colcount = $result->columnCount();

//open container
#    $data =  '{';
#    $data = $data. '"OperatorAvailabilityRecord":[';

//start records
//set loop counter
$i = 1;
echo '<h2> E-Mail to SMS Usage Report ' . $fDate . '</h2>';
echo ('<table id="example" class="table table-striped table-bordered" style="width:100%"><thead><tr>');
echo ('<th>' . "Send Time" . '</th>');
echo ('<th>' . "Message From" . '</th>');
echo ('<th>' . "Message To" . '</th>');
echo ('<th>' . "Message Text" . '</th>');
echo ('</tr></thead><tbody>');

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        // Get row data
        echo ('<tr>');
        echo ('<td>' . $row['SendTime'] . '</td>');
        echo ('<td>' . $row['MessageFrom'] . '</td>');
        echo ('<td>' . $row['MessageTo'] . '</td>');
        echo ('<td>' . $row['MessageText'] . '</td>');
        echo ('</tr>');
}
echo ('</tbody>');
echo ('</table>');
?>
<form action="getCSV.php" method="post">
        <input type="hidden" name="csv_text" id="csv_text">
        <input type="submit" value="Get CSV File" onclick="getCSVData();">
</form>