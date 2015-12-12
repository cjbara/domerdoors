<?php
	$link = mysqli_connect("localhost", "jklamer", "jackpw","domerdoors")
	or die ("Connection failed: " .mysqli_connect_error());

//variables
$blankstring="";
$mates= array();
$numInd=0;
$querylist = array();
$undoMates =array();

//get needed info
$netID = (ISSET($_REQUEST['netID']) ? "'".$_REQUEST['netID']."'" : null); array_push($mates,$netID);
$password = (ISSET($_REQUEST['password']) ? "'".$_REQUEST['password']."'" : null);
$roomNum = (!empty($_REQUEST['RoomNum']) ? "'".$_REQUEST['RoomNum']."'" : null);
$mate1 = (!empty($_REQUEST['mate1']) ? "'".$_REQUEST['mate1']."'" : null); if($mate1!=null ) {$numInd= $numInd +1; array_push($mates,$mate1);}
$mate2 = (!empty($_REQUEST['mate2']) ? "'".$_REQUEST['mate2']."'" : null); if($mate2!=null ) {$numInd= $numInd +1; array_push($mates,$mate2);}
$mate3 = (!empty($_REQUEST['mate3']) ? "'".$_REQUEST['mate3']."'" : null); if($mate3!=null ) {$numInd= $numInd +1;array_push($mates,$mate3);}
$mate4 = (!empty($_REQUEST['mate4']) ? "'".$_REQUEST['mate4']."'" : null); if($mate4!=null ) {$numInd= $numInd +1;array_push($mates,$mate4);}
$mate5 = (!empty($_REQUEST['mate5']) ? "'".$_REQUEST['mate5']."'" : null); if($mate5!=null ) {$numInd= $numInd +1;array_push($mates,$mate5);}



//find dorm
$dormQ=mysqli_query($link,"select dorm from Resident where netID=$netID;");
if(mysqli_num_rows( $dormQ)==0){
	echo "Incorrect NetID";
	exit();
}

$row=mysqli_fetch_assoc($dormQ);
$dorm=$row['dorm'];



//see if person submitting is current pick
$currPickQ=mysqli_query($link,"select netID from Pick where dorm='$dorm' and hasPicked=0 order by pickNum limit 1;");
if(mysqli_num_rows( $currPickQ)==0){
	echo "No One Left to pick";
	exit();
}

$row=mysqli_fetch_assoc($currPickQ);
$currPick=$row['netID'];

if($netID != "'".$currPick."'")
{
	echo "It is not your turn to pick";
	exit();
}




//find number of residents in dormRoom selected
$numResQ=mysqli_query($link,"Select numResidents as num from Room where roomNum=$roomNum and isOccupied=0 and dorm='$dorm';");
if(mysqli_num_rows( $numResQ)==0){
	echo "Room is occupied or does not exist in this dorm";
	exit();
}

$row=mysqli_fetch_assoc($numResQ);
$numRes=$row['num'];

//make sure right number of roomates selected
if($numRes-1 != $numInd)
{
	echo "Incorrect number of roomates";
	exit();

}


//upate resident and pick and undo if no real person
//HAPPENS ALL OR NONE BABY
foreach($mates as $mate)
{
	$testQ=mysqli_query($link, "select * from Resident where netID=$mate and dorm='$dorm' and roomNum is null;");
	if(mysqli_num_rows( $testQ) == 0)
	{
		foreach($undoMates as $umate)
		{
			array_push($querylist,"Update Resident set roomNum=NULL where netID=$umate;");
			array_push($querylist,"Update Pick set hasPicked=0 where netID=$umate;");
		}
		echo "Incorrect Roomate netID or Roomate already in Room";
		exit();

	}else
	{
		array_push($querylist,"Update Resident set roomNum=$roomNum where netID=$mate;");
		array_push($querylist,"Update Pick set hasPicked=1 where netID=$mate;");
		array_push($undoMates,$mate);
	}

	//echo $mate;
}
//update Room

array_push($querylist,"Update Room set isOccupied=1 where dorm='$dorm' and roomNum=$roomNum;");

foreach($querylist as $query)
{
	$result = mysqli_query($link,$query);
	if(!$result)
	{
		echo "Query Failed: ".$query ;

	}
}


mysqli_close($link);

//go to pick page
$url = 'pick.php';
$params = 'netID='.$netID.'&password='.$password;
header('Location: '.$url.'?'.$params);
?>