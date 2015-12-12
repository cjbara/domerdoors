<!--HAVING TRROUBLE ADDING ROOM TO FAVORITES RELATION-->

<?php
// Connect and select database
$link = mysqli_connect("localhost", "dlewis12", "db2017","domerdoors")
	or die ("Connection failed: " .mysqli_connect_error());

//Get login info
$netID = "'".$_REQUEST['netID']."'";
$pwd = "'".$_REQUEST['password']."'";
$dorm = "''".$_REQUEST['dorm']."''";
$room = "''".$_REQUEST['roomNum']."''";
$roomNum = "'".$_REQUEST['roomNum']."'";
echo "$netID, $pwd, $dorm, $room, $roomNum";
//query for room to see if it's available/valid
$checkQuery = "select roomNum from Room where Room.roomNum = $roomNum and Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0;";
$updateQuery = "insert into Favorites (netID,dorm,roomNum) values($netID,$dorm,$room);";

//if match. Continue to next query
$resultCheck = mysqli_query($link, $checkQuery);
if(mysqli_num_rows($resultCheck) > 0) {
    //update Query
    if( mysqli_query($link, $updateQuery) ){
    //if(mysqli_num_rows($resultUpdate)) {
    	$url = 'pick.php';
    	$params = 'netID='.$netID.'&password='.$pwd.'&dorm='.$dorm;
    	header('Location: '.$url.'?'.$params);
    } else {
    	echo "Error: Could not add room: $room to Favorites";
    }
} else {
    echo "Error: Could not locate room: $room";
}

mysqli_close($link);
?>

