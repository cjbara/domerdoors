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
$netID = $_POST['netID'];
$pw = $_POST['pw'];
$dorm = "'".$_POST['dorm']."'";
$roomNum = "'".$_POST['roomNum']."'";

$favcheck = "SELECT count(*) as c from Favorites where netID=".$netID." and dorm=".$dorm." and roomNum=".$roomNum.";";
$favcheck = mysqli_query($conn , $favcheck);
$favcheck = mysqli_fetch_assoc($favcheck);

if (!$favcheck['c']) {
    $sql = "INSERT INTO Favorites (netID, dorm, roomNum) VALUES ($netID, $dorm, $roomNum);";
    if(mysqli_query($conn, $sql)){
	echo "Room $roomNum added to favorites for $netID";
    } else {
	echo "Could Not add $roomNum to favorites for $netID";
    }
} else {
    echo "Room $roomNum already added to favorites for $netID";
    //header('Refresh: 3; Location: browseFloor.php?netID='.$netID.'&password='.$pw);
}

header('Location: browseFloor.php?netID='.$netID.'&password='.$pw);

mysqli_close($conn);

?>
