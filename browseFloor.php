<html>
<head>
<title>Dorm HomePage</title>
  <!--These two links are for bootstrap -->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="./js/jquery.elevateZoom-3.0.8.min.js"></script>
  <script src="./js/jquery.elevatezoom.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>
<!--PHP script put at top so all html/css variables can access variables that php query for and get from previous page-->
<?php
      // Connect and select database
      $link = mysqli_connect("localhost", "dlewis12", "db2017","domerdoors")
    or die ("Connection failed: " .mysqli_connect_error());

      //Get netID and query for name
      $netID = $_REQUEST['netID'];
      $pwd = $_REQUEST['password'];
      $floor1 = (ISSET($_POST['1']) ? "'".$_POST['1']."'" : null);
      $floor2 = (ISSET($_POST['2']) ? "'".$_POST['2']."'" : null);
      $floor3 = (ISSET($_POST['3']) ? "'".$_POST['3']."'" : null);
      $floor4 = (ISSET($_POST['4']) ? "'".$_POST['4']."'" : null);
      $basement = (ISSET($_POST['basement']) ? "'".$_POST['basement']."'" : null);
      $query = "select name, dorm from Resident where Resident.netID = $netID and Resident.password = $pwd;";
      $result = mysqli_query($link,$query);

      if(mysqli_num_rows($result) > 0){
      // output data of each row and store dorm as variable
      while($row = mysqli_fetch_assoc($result)) {
          $name = $row['name'];
          $dormName = $row['dorm'];
          }
              //get image based on dorm
        if($dormName == "Knott" ){
          if($floor1 != null) $floorNum = 109801;
          if($floor2 != null) $floorNum = 109802;
          if($floor3 != null) $floorNum = 109803;
          if($floor4 != null) $floorNum = 109804;
        }
        else if ($dormName == "Carroll"){
          if($basement != null) $floorNum = "1017BSMT";
          if($floor1 != null) $floorNum = 101701;
          if($floor2 != null) $floorNum = 101702;
          if($floor3 != null) $floorNum = 101703;
          if($floor4 != null) $floorNum = 101704;
        }
        else if ($dormName == "Fisher"){
          if($basement != null) $floorNum = "1051BSMT";
          if($floor1 != null) $floorNum = 105101;
          if($floor2 != null) $floorNum = 105102;
          if($floor3 != null) $floorNum = 105103;
          if($floor4 != null) $floorNum = 105104;
        }
        else if ($dormName == "Dillon"){
          if($basement != null) $floorNum = "1030BSMT";
          if($floor1 != null) $floorNum = 103001;
          if($floor2 != null) $floorNum = 103002;
          if($floor3 != null) $floorNum = 103003;
        }
    }
    //if($_SERVER['REQUEST_METHOD'] === 'REQUEST'){
    if(isset($floorNum)){
    } else {
      $floorNum = $_REQUEST['floorNum'];
    }
      //$floorNum = $_REQUEST(['floorNum']);
  //    echo "$floorNum and $dormName";

    //if($floorNum == null){
    //  $floorNum = $_REQUEST(['floorNum']);
    //}
  //}
  ?>
<body><!--
  LOCAL STORAGE
  <div id="result"></div>
  <script>
  //check storage
  if(typeof(Storage) !== "undefined"){
    //store elements
    localStorage.setItem("dormName", <?php //echo "$dormName";?>);
    localStorage.setItem("floor1", <?php// echo "$floor1";?>);
    localStorage.setItem("floor2", <?php// echo "$floor2";?>);
    localStorage.setItem("floor3", <?php// echo "$floor3";?>);
    localStorage.setItem("floor4", <?php// echo "$floor4";?>);
    localStorage.setItem("basement", <?php// echo "$basement";?>);    
    } else {
      document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Storage...";
    }
    </script>-->
<?php/*
      apc_store("floor1", $floor1, 300 );
      apc_store("floor2", $floor2, 300 );
      apc_store("floor3", $floor3, 300 );
      apc_store("floor4", $floor4, 300 );
      apc_store("basement", $basement, 300 );
      //if everything null, get from local storage
      if($floor1 && $floor2 && $floor3 && $floor4 && $basement){
        $floor1 = var_dump(apc_fetch('floor1'));
        $floor2 = var_dump(apc_fetch('floor2'));
        $floor3 = var_dump(apc_fetch('floor3'));
        $floor4 = var_dump(apc_fetch('floor4'));
        $basement = var_dump(apc_fetch('basement'));
      }*/?>

<!---Navigation Bar at top-->
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class=container>
        <!--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">-->
      <ul class="nav navbar-nav">
        <li class="active"><a href="dorm.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>">Dorm <span class="sr-only">(current)</span></a></li>
        <li><a href="userPrefs.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>">User Preferences</a></li>
        <li><a href="pick.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>">Pick</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
                <li><a href="index.html">Logout</a></li>
          </ul>

        </div>
    </div>
  </nav>
<!-- Insert Floor Screenshot-->
<div class="row equal">
  <div class="col-sm-4">
    <img src="FloorPlans/<?php echo $dormName;?>/<?php echo $floorNum;?>.jpg" width="100%" id="zoom_01" data-zoom-image="FloorPlans/<?php echo $dormName;?>/<?php echo $floorNum;?>.jpg" />
  </div>
<!--Sort rooms by-->
  <div class="col-sm-2">
    <!--<button class="btn btn-default btn-md dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Sort By<span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li><a href="sort.php?">Increasing</a></li>
          <li><a href="sort.php?">Decreasing</a></li>
          <li><a href="sort.php">Room Size Decreasing</a></li>
          <li><a href="sort.php">Type: Single</a></li>
          <li><a href="sort.php">Type: Double</a></li>
          <li><a href="sort.php">Type: Quad</a></li>
        </ul>
      <select name="sortBy" onChange="return updateRooms">
        <option value= -->
    <h4>Sort Rooms By:</h4>
    <form action="browseFloor.php?netID=<?php echo "$netID";?>&password=<?php echo "$pwd";?>&floorNum=<?php echo "$floorNum";?>" method="post">
      <label class="radio-inline"><input type="radio" name="type" value="Inc">Increasing</label><br>
      <label class="radio-inline"><input type="radio" name="type" value="Dec">Decreasing</label><br>
      <label class="radio-inline"><input type="radio" name="type" value="sizeDec">Room Size Increasing</label><br>
      <label class="radio-inline"><input type="radio" name="type" value="sizeInc">Room Size Decreasing</label><br><br>
    <h4>Type of room:</h4>
      <label class="checkbox-inline"><input type="checkbox" name="type1" value="Single">Single</label><br>
      <label class="checkbox-inline"><input type="checkbox" name="type2" value="Double">Double</label><br>
      <label class="checkbox-inline"><input type="checkbox" name="type3" value="Triple">Triple</label><br>
      <label class="checkbox-inline"><input type="checkbox" name="type4" value="Quad">Quad</label><br>
      <input class="btn btn-primary" type="submit" name="update" value="Update" />
    </form>
  </div>
  <!-- Insert Rooms Table -->
  <div class ="col-sm-3">
    <table class="table table-bordered">
    <thead>
        <tr><th colspan='2'>Rooms Available</th></tr>
      </thead>
      <tbody>
        <!--Display available rooms-->
          <?php
            //$type = null;
            //query for available rooms
//            if($_REQUEST['type'] != null){
              $type = (ISSET($_REQUEST['type']) ? $_REQUEST['type'] : null);
              $query = "select roomNum from (select * from Room where Room.dorm = '$dormName' and Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0) A where ";
 
              $type1 = (ISSET($_REQUEST['type1']) ? $_REQUEST['type1'] : null);
              $type2 = (ISSET($_REQUEST['type2']) ? $_REQUEST['type2'] : null);
              $type3 = (ISSET($_REQUEST['type3']) ? $_REQUEST['type3'] : null);
              $type4 = (ISSET($_REQUEST['type4']) ? $_REQUEST['type4'] : null);
              if ($type1 == "Single"){
                $query.="A.numResidents=1";
                if($type2 == "Double"){
                  $query.=" or A.numResidents=2";
                }
                if($type3 == "Triple"){
                  $query.=" or A.numResidents=3";
                }
                if($type4 == "Quad"){
                  $query.=" or A.numResidents=4";
                }
              } else if ($type2 == "Double"){
                $query.="A.numResidents=2";
                if($type3 == "Triple"){
                  $query.=" or A.numResidents=3";
                }
                if($type4 == "Quad"){
                  $query.=" or A.numResidents=4";
                }
              }else if ($type3 == "Triple"){
                $query.="A.numResidents=3";
                  if($type4 == "Quad"){
                  $query.=" or A.numResidents=4";
                  }
              }else if ($type4 == "Quad"){
                $query.="A.numResidents=4";
              } else{
                 $query = "select roomNum from Room where Room.dorm = '$dormName' and Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 ";
              }
              if ($type == "Inc"){
                $query.=" order by roomNum";
              }else if ($type == "Dec"){
                $query.=" order by roomNum desc";
              }else if ($type == "sizeInc"){
                $query.=" order by sqareFootage";
              }else if ($type == "sizeDec"){
                $query.=" order by sqareFootage desc";
              }
              $query.=";";
              $result = mysqli_query($link,$query);
              //$query = "select roomNum from Room where Room.dorm = '$dormName' and Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 order by roomNum;";
              //if($_SERVER['REQUEST_METHOD'] === 'REQUEST'){
              //  $result = _REQUEST(['result']);
              //}else{
              //  $result = mysqli_query($link,$query);
              //}
            //output data for numResidents
//            } else {
//              $query = "select roomNum from Room where Room.dorm = '$dormName' and Room.staffRoom = 0 and Room.isOccupied = 0 and Room.freshmanRoom = 0 and Room.lounge = 0 and Room.studyRoom = 0 and Room.bathroom = 0 order by roomNum desc;";
//              $result = mysqli_query($link,$query);
//            }
            if(mysqli_num_rows($result) > 0){
              while($row = mysqli_fetch_assoc($result)){
		$roomNum = $row['roomNum'];
                echo "<tr class='success'><td><a href=\"#\" data-toggle=\"modal\" data-target=\"#roomModal\">".$row['roomNum']."</a></td>";
		echo "<td>";
		echo "<form action=\"addToFavoritesAction.php\" method=\"post\">";
	  	echo "<input type=\"hidden\" name=\"netID\" value=\"$netID\">";
	  	echo "<input type=\"hidden\" name=\"pw\" value=\"".$_REQUEST['password']."\">";
	  	echo "<input type=\"hidden\" name=\"dorm\" value=\"$dormName\">";
	  	echo "<input type=\"hidden\" name=\"roomNum\" value=\"$roomNum\">";
   	  	echo "<button type=\"submit\" class=\"btn btn-primary btn-small\">Add to Favorites</button>";
		echo "</form></td></tr>";
              }
            }
          ?>
      </tbody>
    </table>
  </div>
    <!--Enter Rooom Number for favorites-->
<!--  <div class="col-sm-2">
   <p>Enter a room number below and press enter to add to your favorites list</p>
    <form action="addToFavoritesAction.php" method="post" >
          <label for="roomNum">Room Number</label>
          <input type="textbook" class="form-control" name="roomNum">
	  <input type="hidden" name="netID" value="<?php //echo $netID;?>">
	  <input type="hidden" name="dorm" value="<?php //echo $dormName;?>">
    </form>
  </div> -->
  <!-- Insert Favorites Table -->
  <div class ="col-sm-2">
    <table class="table table-bordered">
    <thead>
        <tr><th colspan='3'>Your Favorite Rooms</th></tr>
      </thead>
      <tbody>
        <!--Display available rooms-->
          <?php
            //query for available rooms
	      $netID = $_REQUEST['netID'];
              $query = "select distinct favoriteNumber, roomNum from Favorites where netID=$netID;";
	      $favs = mysqli_query($link,$query);
            //output data for numResidents
            if(mysqli_num_rows($favs) > 0){
              while($row = mysqli_fetch_assoc($favs)){
		$roomNum = $row['roomNum'];
                echo "<tr class='success'><td>".$row['favoriteNumber']."</td><td><a href=\"#\" data-toggle=\"modal\" data-target=\"#roomModal\">".$row['roomNum']."</a></td>";
		echo "<td>";
		echo "<form action=\"deleteFromFavoritesAction.php\" method=\"post\">";
	    		echo "<input type=\"hidden\" name=\"netID\" value=\"$netID\">";
        	        echo "<input type=\"hidden\" name=\"pw\" value=\"".$_REQUEST['password']."\">";
        	        echo "<input type=\"hidden\" name=\"dorm\" value=\"$dormName\">";
               		echo "<input type=\"hidden\" name=\"roomNum\" value=\"$roomNum\">";
			echo "<button type=\"submit\" class=\"btn btn-danger btn-small\">Delete from Favorites</button>";
		echo "</form></td></tr>";
              }
            }
          ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Room Modal -->
<div id="roomModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo "$dormName $roomNum"?></h4>
      </div>
      <div class="modal-body">
        <?php 
              $query = "select * from Room where dorm='".$dormName."' and roomNum='".$roomNum."';";
              if($_SERVER['REQUEST_METHOD'] === 'REQUEST'){
                $result = _REQUEST(['result']);
              }else{
                $result = mysqli_query($link,$query);
              }
		$row = mysqli_fetch_assoc($result);
            //output data for numResidents
            if($row){
		$roomNum = $row['roomNum'];
		$section = "select name, dorm from Section where ID=".$row['sectionID'].";";
		$section = mysqli_query($link,$section);
		$section = mysqli_fetch_assoc($section);
		//room type (single, double...
		echo "<table class=\"table table-bordered\"><tr><td>Room Type</td><td>";
		switch($row['numResidents']){
			case 1: echo "Single"; break;
			case 2: echo "Double"; break;
			case 3: echo "Triple"; break;
			case 4: echo "Quad"; break;
			case 5: echo "Quint"; break;
			case 6: echo "6-man"; break;
		}
		echo "</td></tr>";

		echo "<tr><td>Square Footage</td><td>";
		echo $row['sqareFootage'];
		echo " ft<sup>2</sup></td></tr>";

		echo "<tr><td>Floor</td><td>";
		echo ($row['floor'] == 0)? "Basement": $row['floor'];
		echo "</td></tr>";

		echo "<tr><td>Section</td><td>";
		echo $section['dorm']." ".$section['name'];
		echo "</td></tr>";

		echo "<tr><td>Has a Good View</td><td>";
		echo ($row['hasGoodView'] == 1)? "Yes": "No";
		echo "</td></tr>";

		echo "<tr><td>Near Stairs</td><td>";
		echo ($row['closeToStairs'] == 1)? "Yes": "No";
		echo "</td></tr>";

		echo "<tr><td>Near Elevator</td><td>";
		echo ($row['closeToElevator'] == 1)? "Yes": "No";
		echo "</td></tr>";

		echo "</table>";
            }
		
	?>
      </div>
      <div class="modal-footer">
	<form action="addToFavoritesAction.php" method="post">
	  <input type="hidden" name="netID" value="echo $netID;\">
	  <input type="hidden" name="dorm" value="echo $dormName;">
	  <input type="hidden" name="roomNum" value="echo $roomNum;">
   	  <button type="submit" class="btn btn-primary">Add room to Favorites</button>
<!--	<form action="deleteFromFavoritesAction.php" method="post">
          <input type="hidden" name="netID" value="echo $netID;">
          <input type="hidden" name="dorm" value="echo $dormName;">
          <input type="hidden" name="roomNum" value="echo $roomNum;">
          <button type="submit" class="btn btn-danger">Delete room from Favorites</button>
	</form>-->
      </div>

    </div>

  </div>
</div>

<script> $("#zoom_01").elevateZoom({
	zoomWindowWidth:750, 
	zoomWindowHeight:500
}); </script>
</body>
<?php
mysqli_close($link);
?>

