<?php
$servername = "localhost";
$username = "cjbara";
$password = "database";
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

//if all fields are entered
if( ($netID && $password && $year && $name) ) {

$sql = "INSERT INTO Resident (netID, name, year, password) values ($netID, $name, $year, $password)";

if (mysqli_query($conn, $sql)) {
    echo "New user created successfully";
	$url = 'userPrefs.php';
	$params = 'netID='.$netID.'&password='.$password;

	header('Location: '.$url.'?'.$params);
} else {
    echo "Error: Could not make this new user for netID $netID";
}

mysqli_close($conn);


} else {
	header('Location: newUser.php');
}
?>
