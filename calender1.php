<?php
// Initialize the session
session_start();
require_once "config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<?php
//calender.php
?>
<!DOCTYPE html>
<html>
<head>
<?php
 include('header.php')
?>
<script>
</script>
</head>
<body>
<?php
define("cMonday", 1);
define("cTuesday", 2);
define("cWednesday", 3);
define("cThursday", 4);
define("cFriday", 5);
define("cSaturday", 6);
define("cSunday", 0);

$vDate = date("Y-m-d"); //2019-01-22
$vFirstDayOfMonth = sprintf("%d-%d-01",
date("Y"),
date("m")); //2019-01-01
$vFirstDayOfWeekOfMonth = date('w', strtotime($vFirstDayOfMonth)); //2 (Tuesday)
$vLastDayOfMonth = date("t", strtotime($vFirstDayOfMonth)); //31

//begin table and column headings
echo "<table class='table table-bordered table-striped'>";

$vFirstOfMonth = $vFirstDayOfMonth . " 00:00:00";
$vLastOfMonth = sprintf("%d-%d-%d 23:59:59",
date("Y"),
date("m"),
$vLastOfMonth);

$sql = "Select * FROM jobs where jobstart between '$vFirstofMonth' and '$vLastDayOfMonth' ";
$dates = array();
if($resultSet = mysqli_query($link, $sql)){
	
	while($rows = $resultSet->fetch_assoc())
						{
							if ( $dates[ $rows['jobstart'] ] == null ) {
								$dates[ $rows['jobstart'] ] = array();
							}
							array_push($dates[ $rows['jobstart'] ], $rows);
						}
						
					}
					//var_dump($dates);
//begin table and column headings
echo "<table class='table table-bordered table-striped'>";
echo "<tr>
<th>Monday</th>
<th>Tuesday</th>
<th>Wednesday</th>
<th>Thursday</th>
<th>Friday</th>
<th>Saturday</th>
<th>Sunday</th>
</tr>\n";

//if the 1st day of the month was Not a monday then output blanks for the previous days of the month
$lastMonthDays = 1;
if ($vFirstDayOfWeekOfMonth != cMonday) {
echo "<tr>\n";
for ($lastMonthDays = 1; $lastMonthDays < $vFirstDayOfWeekOfMonth; $lastMonthDays++) {
echo sprintf("\t<td></td>\n", $lastMonthDays);
}
}

//output calendar days
$thisDate = $dayOfWeek = "";
for ($day = 1; $day <= $vLastDayOfMonth; $day++) {
$thisDate = date( sprintf("%d-%d-%d",
date("Y"),
date("m"),
$day) ); //this day of the month
$dateRaw = date_create($thisDate)->format("d-m-Y");
$dayOfWeek = date('w', strtotime($thisDate)); //dayofweek numeric

if ($dayOfWeek == cMonday) { //begin a new row on mondays
echo "<tr>\n";
}




echo printDay($dateRaw, $day);

if ($dayOfWeek == cSunday) { //end row for sundays
echo "</tr>\n";
}
}

if ($dayOfWeek != cSunday) { //end row for sundays
echo "</tr>\n";
}

echo "</table>\n";
function printDay($thisDateRaw, $day) {
	global $dates;
	
	$content = "";
//echo $thisDateRaw;
$content = $day;
if (array_key_exists( $thisDateRaw, $dates )) {
	
foreach ($dates[$thisDateRaw] as $appointmentDBRecord) {
$content .= "<br>" . "<a href='readjob.php?jobid=". $appointmentDBRecord['jobid'] . "'>" . $appointmentDBRecord["jobtype"] . "</a>";

}
}
else {

}

echo "<td>" . $content ."</td>\n";
}
?>
</body>
</html>
