<!--JUST STARTED! NEED TO SEARCH ALL AVAILABLE ROOMS AND THEN ORDER BY BASED ON INPUT-->

<?php
// Connect and select database
$link = mysqli_connect("localhost", "dlewis12", "db2017","domerdoors")
	or die ("Connection failed: " .mysqli_connect_error());

//Get info
$dorm = "''".$_REQUEST['dorm']."''";
$type = "''".$_POST['type']."''";
echo "$dorm and $type";
//query for seeing if ID and pwd match database
if($type = "Inc"){
	$query = "select roomNum from Room where Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 order by roomNum";
	$result = mysqli_query($link, $query);
}else if ($type = "Dec"){
	$query = "select roomNum from Room where Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 order by roomNum desc";
	$result = mysqli_query($link, $query);
}else if ($type = "sizeInc"){
	$query = "select roomNum from Room where Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 order by sqareFootage";
	$result = mysqli_query($link,$query);
}else if ($type = "sizeDec"){
	$query = "select roomNum from Room where Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 order by sqareFootage desc";
	$result = mysqli_query($link,$query);
}else if ($type = "Single"){
	$query = "select roomNum from Room where Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 and Room.numResidents=1";
	$result = mysqli_query($link,$query);
}else if ($type = "Double"){
	$query = "select roomNum from Room where Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 and Room.numResidents=2";
	$result = mysqli_query($link,$query);
}else if ($type = "Quad"){
	$query = "select roomNum from Room where Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 and Room.numResidents=4";
	$result = mysqli_query($link,$query);
}

if(mysqli_num_rows($result) > 0) {
    $url = 'browseFloor.php';
    $params = 'result='.$result;
    header('Location: '.$url.'?'.$params);
    
} else {
    echo "Error: Could not order correctly";
}

mysqli_close($link);
?>

