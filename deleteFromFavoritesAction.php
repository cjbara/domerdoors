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
$favorites = 0;

$favs = "SELECT count(*) as c from Favorites where netID=".$netID.";";

$favs = mysqli_query($conn, $favs);
$favs = mysqli_fetch_assoc($favs);

$favcheck = "SELECT count(*) as c from Favorites where netID=".$netID." and dorm=".$dorm." and roomNum=".$roomNum.";";
$favcheck = mysqli_query($conn , $favcheck);
$favcheck = mysqli_fetch_assoc($favcheck);

if ($favcheck['c']) {
    $sql = "DELETE FROM Favorites where netID=".$netID." and dorm=".$dorm." and roomNum=".$roomNum.";";
    if (mysqli_query($conn , $sql)) {
	echo "Room $roomNum removed from favorites for $netID";
    } else {
	echo "Could not delete $roomNum from favorites for $netID";
    }
} else {
    echo "NOPE";
}
mysqli_close($conn);

?>
