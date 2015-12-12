<html>
<head>
<title>Pick</title>
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
	    $query = "select name, dorm from Resident where Resident.netID = $netID and Resident.password = $pwd;";
	    $result = mysqli_query($link,$query);

	    if(mysqli_num_rows($result) > 0){
		// output data of each row
		while($row = mysqli_fetch_assoc($result)) {
		    $name = $row['name'];
        $dorm = $row['dorm'];
	    	}
	    }


	?>
<!---Navigation Bar at top-->
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
  	<!--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">-->
      <ul class="nav navbar-nav">
      	<li><a href="dorm.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>">Dorm</a></li>
        <li><a href="userPrefs.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>">User Preferences</a></li>
        <li class="active"><a href="pick.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>">Pick <span class="sr-only">(current)</span></a></li>
   	  </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="index.html">Logout</a></li>
      </ul>
  	</div>
     </div>
  </nav>

<!--Split Screen-->
<div class="container">
<div class="row equal">
  <!-- Insert FloorPlan -->
  <div class ="col-xs-6 col-sm-4">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Pick Order</th>
	  <th>Resident</th>
	  <th>netID</th>
        </tr>
      </thead>
      <tbody>
        <!--Get random generated student order-->
          <?php
            //query for number of residents that are not staff
            $numResQuery = "select count(*) as numResidents from Pick P, Resident R where R.netID = P.netID and R.isStaff is null;";
            $numResResult = mysqli_query($link,$numResQuery);

            //output data for numResidents
            if(mysqli_num_rows($numResResult) > 0){
              while($row = mysqli_fetch_assoc($numResResult)){
                $numResidents = $row['numResidents'];
              }
            }
	    //Query for name by order of picked residents
	    $orderQuery = "select pickNum, name, R.netID, hasPicked from Pick P, Resident R where R.netID = P.netID and isStaff is null and R.dorm = '$dorm' order by pickNum;";
            //ouput data by resident number
	   $i = 1;
	    $orderResult = mysqli_query($link,$orderQuery);
	    if(mysqli_num_rows($orderResult) > 0){
		while($row = mysqli_fetch_assoc($orderResult)){
		    echo "<tr class='";
		    if($row['hasPicked']) echo "danger";
		    else echo "success";
		    echo "'><td>";
		    if($row['hasPicked']) echo "<span style=\"text-decoration:line-through;\">";
		    echo $row['pickNum'];
		    if($row['hasPicked']) echo "</span>";
		    echo "</td>";
		    echo "<td>";
		    if($row['hasPicked']) echo "<span style=\"text-decoration:line-through;\">";
		    echo $row['name'];
		    if($row['hasPicked']) echo "</span>";
		    echo "</td>";
		    echo "<td>";
		    if($row['hasPicked']) echo "<span style=\"text-decoration:line-through;\">";
		    echo $row['netID'];
		    if($row['hasPicked']) echo "</span>";
		    echo "</td></tr>";
		}
	    }
          ?>
      </tbody>
    </table>
  </div>
  <!---Available, Favorites, Recommended Table -->
  <div class="col-xs-6 col-sm-4">           
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Available</th>
          <th>Recomended</th>
          <th>Favorites</th>
        </tr>
      </thead>
      <tbody>
        <!--Query for availble rooms-->
          <?php
          	//new query for availble rooms
          	$queryAvail = "select roomNum from Room where dorm = '$dorm' and isOccupied = 0 and freshmanRoom = 0 and staffRoom = 0 and lounge = 0 and studyRoom = 0 and bathroom = 0 and numResidents > 0 order by roomNum;";
          	$resultAvail = mysqli_query($link,$queryAvail);
            //new query for favorites
		        $netID = $_REQUEST['netID'];
            $queryFav = "select distinct F.roomNum , R.isOccupied from Favorites F, Room R where F.dorm = '$dorm' and F.dorm=R.dorm and F.netID = $netID and R.roomNum=F.roomNum order by favoriteNumber;";
            $resultFav = mysqli_query($link, $queryFav);
	   
	           //query for recomended
	          $recommended = "select R.roomNum from Recommended R, Room M where R.netID=$netID and R.dorm='$dorm' and R.dorm=M.dorm and R.roomNum=M.roomNum and M.isOccupied=0 order by R.score desc, R.sqareFootage desc;";
	          $resultRec = mysqli_query($link, $recommended);

            $count = 0;

         		//output data
         		while(mysqli_num_rows($resultAvail) > $count || mysqli_num_rows($resultFav) > $count ){
         			echo "<tr>";
              if($row = mysqli_fetch_assoc($resultAvail)){
         				echo "<td>".$row['roomNum']."</td>";
         			}else
              {
                echo "<td></td>";
              }
	            if($row3 = mysqli_fetch_assoc($resultRec)){
	           	echo "<td>".$row3['roomNum']."</td>";
	             } else {
                echo "<td></td>";
              }
              if($row2 = mysqli_fetch_assoc($resultFav))
              {
                echo "<td class=\"";
                echo ($row2['isOccupied'])? "danger": "success";
                echo "\">";
                if($row2['isOccupied']) echo "<span style=\"text-decoration:line-through;\">";
                echo $row2['roomNum'];
                if($row2['isOccupied']) echo "</span>";
                echo "</td>";
              }else
              {

                echo"<td></td>";
              }
         		
              $count= $count +1;
              echo"</tr>";
            }
          ?>
      <!--Crazy Query for Recomendation-->
      <!--Favorites-->
      </tbody>

    </table>
  </div>
  <!--Pick chart-->
  <div class="col-xs-6 col-sm-2">
  <!--Enter Room, Roommates, and PICK!-->

      <form action="updatePick.php" method="post">
	<div class="form-group">
    <input class="form-control" type="hidden" name="netID" value=<?php echo $_REQUEST['netID'] ?>>
    <input class="form-control" type="hidden" name="password" value=<?php echo $_REQUEST['password'] ?>>
	    <label for="RoomNum">Room Number</label>
		<input type="text" class="form-control" name="RoomNum">
    <label> Roomate netIDs:</label>
    <input type="text" class="form-control" name="mate1"><br>
    <input type="text" class="form-control" name="mate2"><br>
    <input type="text" class="form-control" name="mate3"><br>
    <input type="text" class="form-control" name="mate4"><br>
    <input type="text" class="form-control" name="mate5"><br>

	 <!--INSERT ROOMATE OPTION-->
  </div>
    	<button type="submit" class="btn btn-default">PICK!</button>
    </form>
  </div>
</div>
</div>
</body>
<?php
mysqli_close($link);
?>
