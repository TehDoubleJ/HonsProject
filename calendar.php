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

$thisDay = date("d"); //todays Day (number)
$thisMonth = date("m"); //todays Month (number)
$thisYear = date("Y"); //4 digits

$firstDayOfMonth = $thisYear . "-" . $thisMonth . "-" . "01"; //1st of this month
$firstDayOfWeekOfMonth = date('w', strtotime($firstDayOfMonth)); //dayofweek for the 1st (number)
$lastDayOfMonth = date("t", strtotime($firstDayOfMonth)); //last day of this month (number)



//begin table and the days of the week headers
echo "<table class='table table-bordered table-striped'>";
echo "<tr>";
echo "<th>Monday</th>";
echo "<th>Tuesday</th>";
echo "<th>Wednesday</th>";
echo "<th>Thursday</th>";
echo "<th>Friday</th>";
echo "<th>Saturday</th>";
echo "<th>Sunday</th>";
echo "</tr>";

//if the 1st day of this month was Not a monday then output blanks for the previous days of the week (last days of last month)
if ($firstDayOfWeekOfMonth != cMonday) {
	echo "<tr>";
	for ($lastMonthDays = 1; $lastMonthDays < $firstDayOfWeekOfMonth; $lastMonthDays++) {
		echo "<td></td>"; //blank day
	}
}

//output calendar days
$thisDate = $dayOfWeek = "";
for ($day = 1; $day <= $lastDayOfMonth; $day++) {
	$thisDate = $day . "-" . date("m") . "-" . date("Y"); //the day to display (e.g. 12-02-2019
	$dayOfWeek = date('w', strtotime($thisDate)); //dayofweek (number)

	if ($dayOfWeek == cMonday) { //start a new table row on mondays
		echo "<tr>";
	}

	displayDay($thisDate, $day);

	if ($dayOfWeek == cSunday) { //end table row on sundays
		echo "</tr>";
	}
}

//end of calendar, end the row if it hasnt already ended
if ($dayOfWeek != cSunday) { //
	echo "</tr>";
}
echo "</table>";




function displayDay($thisDate, $day) {
	global $link;
	$dayContent = $day;
	
	//find todays appointments
	
	$sql = "Select * FROM jobs where jobstart = '$thisDate'";
	//echo "<br>" . $sql;
	if($resultSet = mysqli_query($link, $sql)){
		//echo "<br>found";
		while($rows = $resultSet->fetch_assoc()) {
			//echo "<br>".$rows["jobid"];
			$jobId = $rows["jobid"];
			$jobDisplay = $rows["jobtype"];
			$a = "<a href='readjob.php?jobid=$jobId'>$jobDisplay</a>";
			$dayContent = $dayContent . "<br>" . $a;
		}
	}

	echo "<td>$dayContent</td>\n";
}
?>
</body>
</html>
