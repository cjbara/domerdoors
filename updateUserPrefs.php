<?php
// Connect and select database
$link = mysqli_connect("localhost", "dlewis12", "db2017","domerdoors")
	or die ("Connection failed: " .mysqli_connect_error());

//Get preferences info
$netID = (ISSET($_POST['netID']) ? "'".$_POST['netID']."'" : null);
$password = (ISSET($_POST['password']) ? "'".$_POST['password']."'" : null);
$year = (ISSET($_POST['year']) ? $_POST['year'] : null);
$dorm = (ISSET($_POST['dorm']) ? "'".$_POST['dorm']."'" : null);
$study = (ISSET($_POST['study']) ? $_POST['study'] : 0);
$party = (ISSET($_POST['party']) ? $_POST['party'] : 0);
$closeToStaff = (ISSET($_POST['closeToStaff']) ? $_POST['closeToStaff'] : null);
$closeToStairs = (ISSET($_POST['closeToStairs']) ? $_POST['closeToStairs'] : null);
$closeToElevator = (ISSET($_POST['closeToElevator']) ? $_POST['closeToElevator'] : null);
$goodView = (ISSET($_POST['goodView']) ? $_POST['goodView'] : null);

//update database
$querylist = array();
if($year != null) { array_push($querylist, "update Resident set year=$year where netID=$netID;"); }
if($dorm != null) { array_push($querylist, "update Resident set dorm=$dorm where netID=$netID;"); }
if($study != null) { array_push($querylist, "update Resident set study=$study where netID=$netID;"); }
if($party != null) { array_push($querylist, "update Resident set party=$party where netID=$netID;"); }
if($closeToStaff != null) { array_push($querylist, "update Resident set closeToStaff=$closeToStaff where netID=$netID;"); }
if($closeToStairs != null) { array_push($querylist, "update Resident set closeToStairs=$closeToStairs where netID=$netID;"); }
if($closeToElevator != null) { array_push($querylist, "update Resident set closeToElevator=$closeToElevator where netID=$netID;"); }
if($goodView != null) { array_push($querylist, "update Resident set goodView=$goodView where netID=$netID;"); }

foreach( $querylist as $query ){
	$result = mysqli_query($link, $query);
	//Success v failure
	if ($result) {
	    echo "Successfully updated preference for query $query \n";
	} else {
	    echo "Error: Could not update your preference for query $query \n";
	}
}
mysqli_close($link);

//go to user prefs page
$url = 'userPrefs.php';
$params = 'netID='.$netID.'&password='.$password;

header('Location: '.$url.'?'.$params);

?>


