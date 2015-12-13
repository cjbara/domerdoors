<html>
<head>
<title>User Preferences</title>
  <!--These two links are for bootstrap -->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>
<body>
<!--PHP script at top so rest of html/css can get variables-->
<?php
	    // Connect and select database
	    $link = mysqli_connect("localhost", "cjbara", "database","domerdoors")
		or die ("Connection failed: " .mysqli_connect_error());

	    //Get netID and query for name
	    $netID = $_REQUEST['netID'];
	    $pwd = $_REQUEST['password'];
	    $query = "select name, isStaff from Resident where Resident.netID = $netID and Resident.password = $pwd;";
	    $result = mysqli_query($link,$query);

	    if(mysqli_num_rows($result) > 0){
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) {
		    $name = $row['name'];
		    $isStaff = $row['isStaff'];
	    	}
	    }
	?>
<!---Navigation Bar at top-->
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
  	<!--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">-->
      <ul class="nav navbar-nav">
        <li><a href="browseFloor.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>">Browse Rooms </a></li>
        <li class="active"><a href="userPrefs.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>">User Preferences <span class="sr-only">(current)</span></a></li>
        <li><a href="pick.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>">Pick</a></li>
   	  </ul>
   	  <ul class="nav navbar-nav navbar-right">
   	  	<li><a href="index.html">Logout</a></li>
   	  </ul>
  	</div>
    </div>
  </nav>

<div class="container">
  <div class="jumbotron">
    <h1>Welcome,  
	<?php
	    echo $name;
	?>
    </h1>
  </div>
  <div class="row">
    <div class="col-sm-6">
	<h4>Preferences:</h4>
	<?php
            //Get netID and query for name
            $netID = $_REQUEST['netID'];
            $pwd = $_REQUEST['password'];
            $query = "select year, dorm, roomNum, party, study, closeToStaff, closeToStairs, closeToElevator, goodView, prefSection, prefFloor , prefRoomSize from Resident where Resident.netID = $netID and Resident.password = $pwd;";
            $result = mysqli_query($link,$query);

            if(mysqli_num_rows($result)){
		// output data of each row
		$tuple = mysqli_fetch_assoc($result);
		echo "<table class=\"table table-bordered table-striped\">\n";
		echo "<tr><td>Graduation Year</td><td>".$tuple['year']."</td></tr>";
		echo "<tr><td>Dorm</td><td>".$tuple['dorm']."</td></tr>";
		echo "<tr><td>Room Number</td><td>";
		echo ($tuple['roomNum'])?$tuple['roomNum']:"No room picked";
		echo "</td></tr>";
		echo "<tr><td>Like to Party?</td><td>";
		echo ($tuple['party'])?"Yes":"No";
		echo "</td></tr>";
		echo "<tr><td>Like to Study in Room?</td><td>";
		echo ($tuple['study'])?"Yes":"No";
		echo "</td></tr>";
		echo "<tr><td>Want to be near Hall Staff?</td><td>";
		echo ($tuple['closeToStaff'])?"Yes":"No";
		echo "</td></tr>";
		echo "<tr><td>Want to be near Stairs?</td><td>";
		echo ($tuple['closeToStairs'])?"Yes":"No";
		echo "</td></tr>";
		echo "<tr><td>Want to be near an Elevator?</td><td>";
		echo ($tuple['closeToElevator'])?"Yes":"No";
		echo "</td></tr>";
		echo "<tr><td>Want a good view?</td><td>";
		echo ($tuple['goodView'])?"Yes":"No";
		echo "</td></tr>";
		echo "<tr><td>Preferred Section</td><td>";
	    $query = "SELECT S.name FROM Resident R, Section S where netID=$netID and S.ID=R.prefSection;"; 
	    $query = mysqli_query($link , $query); 
	    $query = mysqli_fetch_assoc($query);
	    	echo $query['name'];
		echo "</td></tr>";
		echo "<tr><td>Preferred Floor</td><td>";
		echo ($tuple['prefFloor']==0)?"Basement":$tuple['prefFloor'];
		echo "</td></tr>";
		echo "<tr><td>Preferred Setup</td><td>";
		switch($tuple['prefRoomSize']){
			case 1: echo "Single"; break;
			case 2: echo "Double"; break;
			case 3: echo "Triple"; break;
			case 4: echo "Quad"; break;
		}
		echo "</td></tr>";
		echo "</table>\n";
            }
        ?>
 <!-- User Preferences Begin-->
	<br><br><br><br>
	<?php if($isStaff == 1) :?>
		<a class="btn btn-danger" href="deleteUser.php">Delete a user?</a>
	<?php endif; ?>
	</form>	
    </div>
    <div class="col-sm-6">
      <h4><i>Update your preferences below:</i></h4>
      <form action="updateUserPrefs.php" method="post">
	   <input class="form-control" type="hidden" name="netID" value=<?php echo $_REQUEST['netID'] ?>>
	   <input class="form-control" type="hidden" name="password" value=<?php echo $_REQUEST['password'] ?>>
            <label for="Year">Graduation Year:</label>
	    <br>
	    <label class="radio-inline"><input type="radio" name="year" value="2017">2017</label>
	    <label class="radio-inline"><input type="radio" name="year" value="2018">2018</label>
	    <label class="radio-inline"><input type="radio" name="year" value="2019">2019</label>
	    <br>
	    <br>
            <label for="party">Do you like to party in your room?</label>
	    <br>
	    <label class="radio-inline"><input type="radio" name="party" value="1">Yes</label>
	    <label class="radio-inline"><input type="radio" name="party" value="0">No</label>
	    <br>
	    <br>
            <label for="study">Do you like to study in your room?</label>
	    <br>
	    <label class="radio-inline"><input type="radio" name="study" value="1">Yes</label>
	    <label class="radio-inline"><input type="radio" name="study" value="0">No</label>
	    <br>
	    <br>
            <label for="closeToStaff">Do you want to be close to a Hall Staff Member?</label>
	    <br>
	    <label class="radio-inline"><input type="radio" name="closeToStaff" value="1">Yes</label>
	    <label class="radio-inline"><input type="radio" name="closeToStaff" value="0">No</label>
	    <br>
	    <br>
            <label for="closeToStairs">Do you want to be close to stairs?</label>
	    <br>
	    <label class="radio-inline"><input type="radio" name="closeToStairs" value="1">Yes</label>
	    <label class="radio-inline"><input type="radio" name="closeToStairs" value="0">No</label>
	    <br>
	    <br>
            <label for="closeToElevator">Do you want to be close to an elevator?</label>
	    <br>
	    <label class="radio-inline"><input type="radio" name="closeToElevator" value="1">Yes</label>
	    <label class="radio-inline"><input type="radio" name="closeToElevator" value="0">No</label>
	    <br>
	    <br>
            <label for="goodView">Does a good view matter to you?</label>
	    <br>
	    <label class="radio-inline"><input type="radio" name="goodView" value="1">Yes</label>
	    <label class="radio-inline"><input type="radio" name="goodView" value="0">No</label>
	    <br>
	    <br>
	<?php 
	    $query = "SELECT dorm FROM Resident where netID=$netID;"; 
	    $query = mysqli_query($link , $query); 
	    $query = mysqli_fetch_assoc($query);
	    $dorm = $query['dorm'];

	    $section = "SELECT name, ID FROM Section where dorm='$dorm';"; 
	    $section = mysqli_query($link , $section);
	    echo "<label for=\"prefSection\">What is your preferred section?</label>";
            echo "<br>";
	    while (($row = mysqli_fetch_assoc($section))) {
		echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"prefSection\" value=\"".$row['ID']."\">".$row['name']."</label>";
	    }
            echo "<br>";
            echo "<br>";
	    $floor = "SELECT distinct floor FROM Room WHERE dorm='$dorm';"; 
	    $floor = mysqli_query($link , $floor);
            echo "<label for=\"prefFloor\">What is your preferred floor?</label>";
            echo "<br>";
	    while ($row = mysqli_fetch_assoc($floor)) {
		echo "<label class=\"radio-inline\"><input type=\"radio\" name=\"prefFloor\" value=\"".$row['floor']."\">";
	    	echo ($row['floor']==0)?"Basement":$row['floor'];
		echo "</label>";
	    }
            echo "<br>";
            echo "<br>";
	?>

	    <label for="prefRoomSize">What is your preferred setup?</label>
	    <br>
	    <label class="radio-inline"><input type="radio" name="prefRoomSize" value="1">Single</label>
	    <label class="radio-inline"><input type="radio" name="prefRoomSize" value="2">Double</label>
	    <label class="radio-inline"><input type="radio" name="prefRoomSize" value="3">Triple</label>
	    <label class="radio-inline"><input type="radio" name="prefRoomSize" value="4">Quad</label>
	    <br>
	    <br>
	<input class="btn btn-primary" type="submit" name="save" value="Save Changes" />
      </form>
    </div>
  </div>
</div>
</body>
<?php
mysqli_close($link);
?>
