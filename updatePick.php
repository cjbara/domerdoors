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
