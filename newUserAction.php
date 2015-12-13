<?php
$servername = "localhost";
$username = "dlewis12";
$password = "db2017";
$dbname = "domerdoors";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//Create variables
$netID = (ISSET($_POST['netID']) ? "'".$_POST['netID']."'" : null);
$password = (ISSET($_POST['password']) ? "'".$_POST['password']."'" : null);
$year = (ISSET($_POST['year']) ? $_POST['year'] : null);
$name = (ISSET($_POST['name']) ? "'".$_POST['name']."'" : null);
$dorm = (ISSET($_POST['dorm']) ? "'".$_POST['dorm']."'" : null);
$staffPassword = (ISSET($_POST['staffPassword']) ? $_POST['staffPassword'] : null);

//if all fields are entered
if( ($netID && $password && $year && $name && $dorm ) ) {

	//convert to uppercase
	if($dorm == "'knott'"){
		$dorm = "'Knott'";
	}
	else if($dorm == "'carroll'"){
		$dorm = "'Carroll'";
	}
	else if($dorm == "'fisher'"){
		$dorm = "'Fisher'";
	}
	else if($dorm == "'dillon'"){
		$dorm = "'Dillon'";
	} 
	if($staffPassword == "admin"){
		//give staff priviledges
		$sql = "INSERT INTO Resident (netID, name, year, dorm, password, isStaff) values ($netID, $name, $year, $dorm, $password, 1)";
		$pick = "insert into Pick (netID, dorm, pickNum, hasPicked) select $netID, $dorm, c, 0 from (select count(*)+1 as c from Pick where dorm=$dorm) S;";
	} else {
		$sql = "INSERT INTO Resident (netID, name, year, dorm, password) values ($netID, $name, $year, $dorm, $password)";
		$pick = "insert into Pick (netID, dorm, pickNum, hasPicked) select $netID, $dorm, c, 0 from (select count(*)+1 as c from Pick where dorm=$dorm) S;";
	}

	echo $sql;
	echo $pick;

	if (mysqli_query($conn, $sql)) {
		mysqli_query($conn, $pick);
   		echo "New user created successfully";
		$url = 'userPrefs.php';
		$params = 'netID='.$netID.'&password='.$password;
		header('Location: '.$url.'?'.$params);
	} else {
	echo $pick;
    	echo "Error: Could not make this new user for netID $netID";
	}

} else {
	header('Location: newUser.php');
}
mysqli_close($conn);
?>
