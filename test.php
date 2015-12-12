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
$netID = "cjbara";
$dorm = "Fisher";

$preferences = "select * from Resident where netID='$netID';";
$preferences = mysqli_fetch_array(mysqli_query($conn, $preferences));
foreach($preferences as $key => $value){
	echo "<p>$key => $value</p>";
}

$roomArray = array();

//find available rooms
$available = "select * from Available where dorm='$dorm';";
$availableResult = mysqli_query($conn, $available);
while($row = mysqli_fetch_assoc($availableResult)){
    $roomArray[$row['roomNum']] = 0;
}

//Check if their stairs preference is good
$stairs = "select roomNum, closeToStairs from Available where dorm='$dorm';";
$stairs = mysqli_query($conn, $stairs);
while($row = mysqli_fetch_assoc($stairs)){
    if($preferences['closeToStairs'] == $row['closeToStairs']){
	$roomArray[$row['roomNum']]++;
    }
}
//Check if their Elevator preference is good
$elevator = "select roomNum, closeToElevator from Available where dorm='$dorm';";
$elevator = mysqli_query($conn, $elevator);
while($row = mysqli_fetch_assoc($elevator)){
    if($preferences['closeToElevator'] == $row['closeToElevator']){
	$roomArray[$row['roomNum']]++;
    }
}
//Check if their view preference is good
$view = "select roomNum, hasGoodView from Available where dorm='$dorm';";
$view = mysqli_query($conn, $view);
while($row = mysqli_fetch_assoc($view)){
    if($preferences['goodView'] == $row['hasGoodView']){
	$roomArray[$row['roomNum']]++;
    }
}
//check if the room is in their preferred section
$section = "select roomNum, sectionID from Available where dorm='$dorm';";
$section = mysqli_query($conn, $section);
while($row = mysqli_fetch_assoc($section)){
    if($preferences['prefSection'] == $row['sectionID']){
	$roomArray[$row['roomNum']]++;
    }
}
//check if the room is in their preferred floor
$floor = "select roomNum, floor from Available where dorm='$dorm';";
$floor = mysqli_query($conn, $floor);
while($row = mysqli_fetch_assoc($floor)){
    if($preferences['prefFloor'] == $row['floor']){
	$roomArray[$row['roomNum']]++;
    }
}
//check if the room has their preferred amount of Roommates
$numResidents = "select roomNum, numResidents from Available where dorm='$dorm';";
$numResidents = mysqli_query($conn, $numResidents);
while($row = mysqli_fetch_assoc($numResidents)){
    if($preferences['prefRoomSize'] == $row['numResidents']){
	$roomArray[$row['roomNum']]+=3; //add three points for the right room size
    }
}
//Check if they are near staff
$staff = "select roomNum, floor from Room R where dorm='$dorm' and staffRoom=1;";
$staff = mysqli_query($conn, $staff);
while($row = mysqli_fetch_assoc($staff)){
    if($preferences['closeToStaff'] == 1){ //user wants to be close to staff
	foreach($roomArray as $room => $score){
	    if(abs($room-$row['roomNum']) <= 6){
		$roomArray[$room]++;
	    }
	}
    } else { //user wants to be far from staff
	foreach($roomArray as $room => $score){
	    if(abs($room-$row['roomNum']) <= 6){
		$roomArray[$room]--;
	    }
	}
    }
}
//check if the section is a party section
//$party = "select count(*) from Room, Resident, Section where Section.ID=Room.sectionID and Room.roomNum=Resident.roomNum and Resident.party=1 and Section.ID=$section;";
//$party = mysqli_query($conn, $party);
//echo $party;

//check if the section is a study section
/*
select count(*) from Room, Resident, Section where Section.ID=Room.sectionID and Room.roomNum=Resident.roomNum and Resident.study=1;
*/

//check how many people from your grade are in your section
/*
select count(*) from Room, Resident, Section where Section.ID=Room.sectionID and Room.roomNum=Resident.roomNum and Resident.year;
*/

//Enter the array into the database
$query = "delete from Recommended where netID='$netID';";
mysqli_query($conn, $query);
foreach($roomArray as $room => $score){
    echo "<p>$room = $score</p>";
    $query = "insert into Recommended (netID, roomNum, dorm, score, sqareFootage) select '$netID', '$room', '$dorm', '$score', sqareFootage from Room where roomNum='$room' and dorm='$dorm';";
    if(mysqli_query($conn, $query)){
	echo "Good Job";
    } else {
	echo $query;
    }
}

mysqli_close($conn);

?>
