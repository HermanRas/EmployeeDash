<!-- PHP CONFIG -->
<?php
$url = "https://phq-7hxllh2.petragroup.local/web_dev/Projects/EmployeeDash/";

$user = '';


//set user if available
if ($_SERVER['AUTH_USER']) {
    $user = $_SERVER['AUTH_USER'];
    $user= str_replace("PETRAGROUP\\","",$user); 
}

if(isset($_GET['manager'])){
$user = $_GET['manager'];
}
?>

<!-- JAVA SCRIPT CONFIG -->
<script>
let emp_url = 'https://phq-7hxllh2.petragroup.local/web_dev/Projects/EmployeeDash/api_emp.php';
let tel_url = 'https://phq-7hxllh2.petragroup.local/web_dev/Projects/EmployeeDash/api_tel.php';
let mobile_url = 'https://phq-7hxllh2.petragroup.local/web_dev/Projects/EmployeeDash/api_mobileData.php';
let internet_url = 'https://phq-7hxllh2.petragroup.local/web_dev/Projects/EmployeeDash/api_internet.php';
let tel_detail_url = 'https://phq-7hxllh2.petragroup.local/web_dev/Projects/EmployeeDash/api_tel_details.php';
let prn_url = 'https://phq-7hxllh2.petragroup.local/web_dev/Projects/EmployeeDash/api_prn.php';
</script>