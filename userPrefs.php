<html>
<head>
<title>User Preferences</title>
  <!--These two links are for bootstrap -->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>
<body>

<div class="container">
  <div class="jumbotron">
    <h1>Welcome,  
	<?php
	    // Connect and select database
	    $link = mysqli_connect("localhost", "dlewis12", "db2017","domerdoors")
		or die ("Connection failed: " .mysqli_connect_error());

	    //Get netID and query for name
	    $netID = $_REQUEST['netID'];
	    $pwd = $_REQUEST['password'];
	    $query = "select name from Resident where Resident.netID = $netID and Resident.password = $pwd;";
	    $result = mysqli_query($link,$query);

	    if(mysqli_num_rows($result) > 0){
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) {
		    echo $row['name'];
	    	}
	    }
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
            $query = "select year, dorm, roomNum, party, study, closeToStaff, closeToStairs, closeToElevator, goodView, prefSection, prefFloor from Resident where Resident.netID = $netID and Resident.password = $pwd;";
            $result = mysqli_query($link,$query);

	    $tablearr = array("Graduation Year", "Dorm", "Room Number", "Like to party?", "Like to study in room?", "Want to be near Hall Staff?", "Want to be near stairs?", "Want to be near an elevator?", "Want a good view?", "Preferred Section", "Preferred Floor");
	    $i = 0; // to index through array

            if(mysqli_num_rows($result) > 0){
		// output data of each row
		echo "<table class=\"table table-striped\">\n";
		$tuple = mysqli_fetch_array($result, MYSQL_ASSOC);
		foreach ($tuple as $col_value) {
		    if($col_value == "0") { $col_value="No"; }
		    //if($col_value == 0) { $col_value="No"; }
		    if($col_value == "1") { $col_value="Yes"; }
		    echo "\t<tr>\n";
		    echo "\t\t<td>$tablearr[$i]</td>\n";
		    echo "\t\t<td>$col_value</td>\n";
		    echo "\t</tr>\n";
		    $i = $i + 1;
		}
		echo "</table>\n";
            }
        ?>
	<br><br><br><br>
	<a class="btn btn-danger" href="deleteUser.php">Delete a user?</a>
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
            <label for="dorm">Select Dorm:</label>
	    <br>
	    <label class="radio-inline"><input type="radio" name="dorm" value="Carroll">Carroll Hall</label>
	    <label class="radio-inline"><input type="radio" name="dorm" value="Dillon">Dillon Hall</label>
	    <label class="radio-inline"><input type="radio" name="dorm" value="Fisher">Fisher Hall</label>
	    <label class="radio-inline"><input type="radio" name="dorm" value="Knott">Knott Hall</label>
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
<!--      <form>
      <h4>Will you/your roomates spend most of the time:</h4>
        <input type="radio" name="function" id="Study">Studying
        <input type="radio" name="function" id="Fun">Having Fun
        <input type="radio" name="function" id="Both">An even amount of both
      </form>
      <form>
      <h4>Do you and/or your roomates want to be close to or far from a RA/AR/Rector:</h4>
        <input type="radio" name="function" id="CloseRA">Close to
        <input type="radio" name="function" id="FarRA">Far from
      </form>
      <form>
      <h4>Do you and/or your roomates want to be close to or far from stairs:</h4>
        <input type="radio" name="function" id="CloseStrs">Close to
        <input type="radio" name="function" id="FarStrs">Far from
      </form>
      <form>
      <h4>Do you and/or your roomates want to be close to or far from the elevator:</h4>
        <input type="radio" name="function" id="CloseElv">Close to
        <input type="radio" name="function" id="FarElv">Far from
      </form>
      <form>
      <h4>Do you and/or your roomates want a nice view of campus:</h4>
        <input type="radio" name="function" id="View">Yes
        <input type="radio" name="function" id="NoView">No
        <input type="radio" name="function" id="NoPref">Doesn't Matter      
      </form>-->
	<input class="btn btn-primary" type="submit" name="save" value="Save Changes" />
      </form>
    </div>
  </div>
</div>
</body>
<?php
mysqli_close($link);
?>
