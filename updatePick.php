<html>
<head>
<?php


 echo "<link rel=\"stylesheet\" href=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css\">";
 echo  "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js\"></script>";
 echo "<script src=\"http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js\"></script>";
	$link = mysqli_connect("localhost", "jklamer", "jackpw","domerdoors")
	or die ("Connection failed: " .mysqli_connect_error());
echo "</head>";
//variables
$blankstring="";
$mates= array();
$numInd=0;
$querylist = array();
$undoMates =array();
$failed = false;

//get needed info
$netID = (ISSET($_REQUEST['netID']) ? "'".$_REQUEST['netID']."'" : null); array_push($mates,$netID);
$password = (ISSET($_REQUEST['password']) ? "'".$_REQUEST['password']."'" : null);
$roomNum = (!empty($_REQUEST['RoomNum']) ? "'".$_REQUEST['RoomNum']."'" : null);
$mate1 = (!empty($_REQUEST['mate1']) ? "'".$_REQUEST['mate1']."'" : null); if($mate1!=null ) {$numInd= $numInd +1; array_push($mates,$mate1);}
$mate2 = (!empty($_REQUEST['mate2']) ? "'".$_REQUEST['mate2']."'" : null); if($mate2!=null ) {$numInd= $numInd +1; array_push($mates,$mate2);}
$mate3 = (!empty($_REQUEST['mate3']) ? "'".$_REQUEST['mate3']."'" : null); if($mate3!=null ) {$numInd= $numInd +1;array_push($mates,$mate3);}
$mate4 = (!empty($_REQUEST['mate4']) ? "'".$_REQUEST['mate4']."'" : null); if($mate4!=null ) {$numInd= $numInd +1;array_push($mates,$mate4);}
$mate5 = (!empty($_REQUEST['mate5']) ? "'".$_REQUEST['mate5']."'" : null); if($mate5!=null ) {$numInd= $numInd +1;array_push($mates,$mate5);}

?>
<body>
<!---Navigation Bar at top-->
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <!--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">-->
      <ul class="nav navbar-nav">
        <li><a href="browseFloor.php?netID=<?php echo "$netID"?>&password=<?php echo "$password"?>">Browse Rooms</a></li>
        <li><a href="userPrefs.php?netID=<?php echo "$netID"?>&password=<?php echo "$password"?>">User Preferences</a></li>
        <li class="active"><a href="pick.php?netID=<?php echo "$netID"?>&password=<?php echo "$password"?>">Pick <span class="sr-only">(current)</span></a></li>
          </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="index.html">Logout</a></li>
      </ul>
        </div>
     </div>
  </nav>
<?php


echo "<div class=\"container\">";

//find dorm
$dormQ=mysqli_query($link,"select dorm from Resident where netID=$netID;");
if(mysqli_num_rows( $dormQ)==0){
	echo "<div class=\"alert alert-danger\"><strong>ERROR: </strong>" ;
	echo "Incorrect NetID</div>";
	$failed=true;
}

$row=mysqli_fetch_assoc($dormQ);
$dorm=$row['dorm'];



//see if person submitting is current pick
$currPickQ=mysqli_query($link,"select netID from Pick where dorm='$dorm' and hasPicked=0 order by pickNum limit 1;");
if(mysqli_num_rows( $currPickQ)==0){
	echo "<div class=\"alert alert-danger\"><strong>ERROR: </strong>" ;
	echo "No One Left to pick</div>";
	$failed=true;
}

$row=mysqli_fetch_assoc($currPickQ);
$currPick=$row['netID'];

if($netID != "'".$currPick."'")
{
	echo "<div class=\"alert alert-danger\"><strong>ERROR: </strong>" ;
	echo "It is not your turn to pick</div>";
	$failed=true;
}




//find number of residents in dormRoom selected
$numResQ=mysqli_query($link,"Select numResidents as num from Room where roomNum=$roomNum and isOccupied=0 and dorm='$dorm';");
if(mysqli_num_rows( $numResQ)==0){
	echo "<div class=\"alert alert-danger\"><strong>ERROR: </strong>" ;
	echo "Room is occupied or does not exist in this dorm</div>";
	$failed=true;
}

$row=mysqli_fetch_assoc($numResQ);
$numRes=$row['num'];

//make sure right number of roomates selected
if($numRes-1 != $numInd)
{
	echo "<div class=\"alert alert-danger\"><strong>ERROR: </strong>" ;
	echo "Incorrect number of roommates</div>";
	$failed=true;

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
		echo "<div class=\"alert alert-danger\"><strong>ERROR: </strong>" ;
		echo "Incorrect Roommate netID or Roomate already in Room</div>";
		$failed=true;

	}else
	{
		array_push($querylist,"Update Resident set roomNum=$roomNum where netID=$mate;");
		array_push($querylist,"Update Pick set hasPicked=1 where netID=$mate;");
		array_push($undoMates,$mate);
	}

}

//if any error check failed
if($failed)
{
	echo "<a  class=\"btn btn-danger\" href=\"pick.php?netID=$netID&password=$password\">Return</a>";
	echo "</div>\n";

	mysqli_close($link);
	exit();
} else {

  //run the script to update everyone's recommended rooms
  $residents = "select * from Resident where dorm='$dorm';";
  $residents = mysqli_query($link, $residents);
  while($preferences = mysqli_fetch_assoc($residents)){
    $net = $preferences['netID']; 
    $roomArray = array();

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
    $partyYes = "select A.roomNum, A.dorm, A.sectionID from Available A, ( select ID, dorm, sectionName, count(case when party=1 then 1 end) as party, count(case when party=0 then 1 end) as noParty from (select R.name, R.dorm, R.roomNum, S.ID, S.name as sectionName, party   from Resident R, (     select R.roomNum, S.ID, S.name, S.dorm      from Room R, Section S      where S.ID=R.sectionID) S   where R.roomNum=S.roomNum   and R.dorm=S.dorm   ) S group by S.ID  having party > noParty ) P where P.ID = A.sectionID and A.dorm = '$dorm';";
    $partyNo = "select A.roomNum, A.dorm, A.sectionID from Available A, ( select ID, dorm, sectionName, count(case when party=1 then 1 end) as party, count(case when party=0 then 1 end) as noParty from (select R.name, R.dorm, R.roomNum, S.ID, S.name as sectionName, party   from Resident R, (     select R.roomNum, S.ID, S.name, S.dorm      from Room R, Section S      where S.ID=R.sectionID) S   where R.roomNum=S.roomNum   and R.dorm=S.dorm   ) S group by S.ID  having party < noParty ) P where P.ID = A.sectionID and A.dorm = '$dorm';";
    if($preferences['party'] == 1){
      $partyYes = mysqli_query($link, $partyYes);
      while($row = mysqli_fetch_assoc($partyYes)){
        $roomArray[$row['roomNum']]+=2;
      }
      $partyNo = mysqli_query($link, $partyNo);
      while($row = mysqli_fetch_assoc($partyNo)){
        $roomArray[$row['roomNum']]--;
      }
    } else if($preferences['party'] == 0) {
      $partyYes = mysqli_query($link, $partyYes);
      while($row = mysqli_fetch_assoc($partyYes)){
        $roomArray[$row['roomNum']]-=2;
      }
      $partyNo = mysqli_query($link, $partyNo);
      while($row = mysqli_fetch_assoc($partyNo)){
        $roomArray[$row['roomNum']]++;
      }
    }
    //check if the section is a study section
    $studyYes = "select A.roomNum, A.dorm, A.sectionID from Available A, ( select ID, dorm, sectionName, count(case when study=1 then 1 end) as study, count(case when study=0 then 1 end) as noStudy from (select R.name, R.dorm, R.roomNum, S.ID, S.name as sectionName, study   from Resident R, (     select R.roomNum, S.ID, S.name, S.dorm      from Room R, Section S      where S.ID=R.sectionID) S   where R.roomNum=S.roomNum   and R.dorm=S.dorm   ) S group by S.ID  having study > noStudy ) P where P.ID = A.sectionID and A.dorm = '$dorm';";
    $studyNo = "select A.roomNum, A.dorm, A.sectionID from Available A, ( select ID, dorm, sectionName, count(case when study=1 then 1 end) as study, count(case when study=0 then 1 end) as noStudy from (select R.name, R.dorm, R.roomNum, S.ID, S.name as sectionName, study   from Resident R, (     select R.roomNum, S.ID, S.name, S.dorm      from Room R, Section S      where S.ID=R.sectionID) S   where R.roomNum=S.roomNum   and R.dorm=S.dorm   ) S group by S.ID  having study < noStudy ) P where P.ID = A.sectionID and A.dorm = '$dorm';";
    if($preferences['study'] == 1){
      $studyYes = mysqli_query($link, $studyYes);
      while($row = mysqli_fetch_assoc($studyYes)){
        $roomArray[$row['roomNum']]+=2;
      }
      $studyNo = mysqli_query($link, $studyNo);
      while($row = mysqli_fetch_assoc($studyNo)){
        $roomArray[$row['roomNum']]--;
      }
    } else if($preferences['study'] == 0) {
      $studyYes = mysqli_query($link, $studyYes);
      while($row = mysqli_fetch_assoc($studyYes)){
        $roomArray[$row['roomNum']]-=2;
      }
      $studyNo = mysqli_query($link, $studyNo);
      while($row = mysqli_fetch_assoc($studyNo)){
        $roomArray[$row['roomNum']]++;
      }
    }

    //check how many people from your grade are in your section
    $sameYear = "select A.roomNum, A.dorm, A.sectionID from Available A, ( select ID, dorm, sectionName, count(case when year=".$preferences['year']." then 1 end) as sameGrade, count(case when year<>".$preferences['year']." then 1 end) as diffGrade from (select R.name, R.dorm, R.roomNum, S.ID, S.name as sectionName, year   from Resident R, (     select R.roomNum, S.ID, S.name, S.dorm     from Room R, Section S     where S.ID=R.sectionID) S   where R.roomNum=S.roomNum   and R.dorm=S.dorm   ) S group by S.ID  having sameGrade > diffGrade ) P where P.ID = A.sectionID and A.dorm = '$dorm';";
    $sameYear = mysqli_query($link, $sameYear);
    while($row = mysqli_fetch_assoc($studyYes)){
      $roomArray[$row['roomNum']]++;
    }
    
    //Enter the array into the database
    $query = "delete from Recommended where netID='$net';";
    mysqli_query($link, $query);
    foreach($roomArray as $room => $score){
        $query = "insert into Recommended (netID, roomNum, dorm, score, sqareFootage) select '$net', '$room', '$dorm', '$score', sqareFootage from Room where roomNum='$room' and dorm='$dorm';";
        if(mysqli_query($link, $query)){
        } else {
            echo $query;
        }
    }
  }
}

//update Room

array_push($querylist,"Update Room set isOccupied=1 where dorm='$dorm' and roomNum=$roomNum;");
//update each roomate
foreach($querylist as $query)
{
	$result = mysqli_query($link,$query);
	if(!$result)
	{
		echo "Query Failed: ".$query ;
		
	}
}

?>
</body>
<?php

mysqli_close($link);
//go to pick page
$url = 'pick.php';
$params = 'netID='.$netID.'&password='.$password;
header('Location: '.$url.'?'.$params);
?>
