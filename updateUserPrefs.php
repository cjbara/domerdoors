<?php
// Connect and select database
$link = mysqli_connect("localhost", "dlewis12", "db2017","domerdoors")
	or die ("Connection failed: " .mysqli_linkect_error());

//Get preferences info
$netID = (ISSET($_POST['netID']) ? "'".$_POST['netID']."'" : null);
$password = (ISSET($_POST['password']) ? "'".$_POST['password']."'" : null);
$year = (ISSET($_POST['year']) ? $_POST['year'] : null);
$study = (ISSET($_POST['study']) ? $_POST['study'] : 0);
$party = (ISSET($_POST['party']) ? $_POST['party'] : 0);
$closeToStaff = (ISSET($_POST['closeToStaff']) ? $_POST['closeToStaff'] : null);
$closeToStairs = (ISSET($_POST['closeToStairs']) ? $_POST['closeToStairs'] : null);
$closeToElevator = (ISSET($_POST['closeToElevator']) ? $_POST['closeToElevator'] : null);
$goodView = (ISSET($_POST['goodView']) ? $_POST['goodView'] : null);
$prefSection = (ISSET($_POST['prefSection']) ? $_POST['prefSection'] : null);
$prefFloor = (ISSET($_POST['prefFloor']) ? $_POST['prefFloor'] : null);
$prefRoomSize = (ISSET($_POST['prefRoomSize']) ? $_POST['prefRoomSize'] : null);

//update database
$querylist = array();
if($year != null) { array_push($querylist, "update Resident set year=$year where netID=$netID;"); }
if($study != null) { array_push($querylist, "update Resident set study=$study where netID=$netID;"); }
if($party != null) { array_push($querylist, "update Resident set party=$party where netID=$netID;"); }
if($closeToStaff != null) { array_push($querylist, "update Resident set closeToStaff=$closeToStaff where netID=$netID;"); }
if($closeToStairs != null) { array_push($querylist, "update Resident set closeToStairs=$closeToStairs where netID=$netID;"); }
if($closeToElevator != null) { array_push($querylist, "update Resident set closeToElevator=$closeToElevator where netID=$netID;"); }
if($goodView != null) { array_push($querylist, "update Resident set goodView=$goodView where netID=$netID;"); }
if($prefSection != null) {array_push($querylist, "update Resident set prefSection=$prefSection where netID=$netID;"); }
if($prefFloor != null) {array_push($querylist, "update Resident set prefFloor=$prefFloor where netID=$netID;"); }
if($prefRoomSize != null) {array_push($querylist, "update Resident set prefRoomSize=$prefRoomSize where netID=$netID;"); }

foreach( $querylist as $query ){
	$result = mysqli_query($link, $query);
	//Success v failure
	if ($result) {
	    echo "Successfully updated preference for query $query \n";
	} else {
	    echo "Error: Could not update your preference for query $query \n";
	}
}

$netID = $_POST['netID'];
$dorm = "select dorm from Resident where netID='$netID';";
$dorm = mysqli_fetch_array(mysqli_query($link, $dorm));
$dorm = $dorm['dorm'];

//Update the user's recommended values
$preferences = "select * from Resident where netID='$netID';";
$preferences = mysqli_fetch_array(mysqli_query($link, $preferences));
foreach($preferences as $key => $value){
        echo "<p>$key => $value</p>";
}

$roomArray = array();

//find available rooms
$available = "select * from Available where dorm='$dorm';";
$availableResult = mysqli_query($link, $available);
while($row = mysqli_fetch_assoc($availableResult)){
    $roomArray[$row['roomNum']] = 0;
}

//Check if their stairs preference is good
$stairs = "select roomNum, closeToStairs from Available where dorm='$dorm';";
$stairs = mysqli_query($link, $stairs);
while($row = mysqli_fetch_assoc($stairs)){
    if($preferences['closeToStairs'] == $row['closeToStairs']){
        $roomArray[$row['roomNum']]++;
    }
}
//Check if their Elevator preference is good
$elevator = "select roomNum, closeToElevator from Available where dorm='$dorm';";
$elevator = mysqli_query($link, $elevator);
while($row = mysqli_fetch_assoc($elevator)){
    if($preferences['closeToElevator'] == $row['closeToElevator']){
        $roomArray[$row['roomNum']]++;
    }
}
//Check if their view preference is good
$view = "select roomNum, hasGoodView from Available where dorm='$dorm';";
$view = mysqli_query($link, $view);
while($row = mysqli_fetch_assoc($view)){
    if($preferences['goodView'] == $row['hasGoodView']){
        $roomArray[$row['roomNum']]++;
    }
}
//check if the room is in their preferred section
$section = "select roomNum, sectionID from Available where dorm='$dorm';";
$section = mysqli_query($link, $section);
while($row = mysqli_fetch_assoc($section)){
    if($preferences['prefSection'] == $row['sectionID']){
        $roomArray[$row['roomNum']]++;
    }
}
//check if the room is in their preferred floor
$floor = "select roomNum, floor from Available where dorm='$dorm';";
$floor = mysqli_query($link, $floor);
while($row = mysqli_fetch_assoc($floor)){
    if($preferences['prefFloor'] == $row['floor']){
        $roomArray[$row['roomNum']]++;
    }
}
//check if the room has their preferred amount of Roommates
$numResidents = "select roomNum, numResidents from Available where dorm='$dorm';";
$numResidents = mysqli_query($link, $numResidents);
while($row = mysqli_fetch_assoc($numResidents)){
    if($preferences['prefRoomSize'] == $row['numResidents']){
        $roomArray[$row['roomNum']]+=3; //add three points for the right room size
    }
}
//Check if they are near staff
$staff = "select roomNum, floor from Room R where dorm='$dorm' and staffRoom=1;";
$staff = mysqli_query($link, $staff);
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
//$party = mysqli_query($link, $party);
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
mysqli_query($link, $query);
foreach($roomArray as $room => $score){
    echo "<p>$room = $score</p>";
    $query = "insert into Recommended (netID, roomNum, dorm, score, sqareFootage) select '$netID', '$room', '$dorm', '$score', sqareFootage from Room where roomNum='$room' and dorm='$dorm';";
    if(mysqli_query($link, $query)){
        echo "Good Job";
    } else {
        echo $query;
    }
}

mysqli_close($link);

//go to user prefs page
$url = 'userPrefs.php';
$params = 'netID=\''.$netID.'\'&password='.$password;

header('Location: '.$url.'?'.$params);

?>


