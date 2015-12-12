<html>
<head>
<title>Dorm HomePage</title>
  <!--These two links are for bootstrap -->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
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
	    $query = "select name, dorm from Resident where Resident.netID = $netID and Resident.password = $pwd;";
	    $result = mysqli_query($link,$query);

	    if(mysqli_num_rows($result) > 0){
			// output data of each row and store dorm as variable
			while($row = mysqli_fetch_assoc($result)) {
			    $name = $row['name'];
			    $dormName = $row['dorm'];
	    		}
	    	//get image based on dorm and number of floors
	    	if($dormName == "Knott"){
	    		$backgroundImage = "KnottHall2.jpg";
	    		$floors = 4;
	    		$basement = false;
	    	}
	    	else if ($dormName == "Carroll"){
	    		$backgroundImage = "CarrollHall2.jpg";
	    		$floors = 4;
	    		$basement = true;
	    	}
	    	else if ($dormName == "Fisher"){
	    		$backgroundImage = "FisherHall.jpg";
	    		$floors = 4;
	    		$basement = true;
	    	}
	    	else if ($dormName == "Dillon"){
	    		$backgroundImage = "DillonHall.jpg";
	    		$floors = 3;
	    		$basement = true;
	    	}
		}
	?>
<body>
<!---Navigation Bar at top-->
  <nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
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

<!--Welcome Message-->
<div class = "row">
<div class="container">
  <div class="jumbotron">
    <h1>Welcome,  
	<?php
	    echo $name;
	?>
    </h1>
  </div>
</div>
</div>
<div class="row-equal">
  <!--Image-->
  <img src="Images/<?php echo $backgroundImage;?>" width="500" height= "500" align="" />
  <style type="text/css">
div img {
   display:block;
   margin:auto;
}
</style>
  <!---Floor Option-->
  <form action="browseFloor.php?netID=<?php echo "$netID"?>&password=<?php echo "$pwd"?>&type=<?php echo "'Inc'";?>&type1=<?php echo "'Single'";?>&type2=<?php echo "'Double'";?>&type3=<?php echo "'Triple'";?>&type4=<?php echo "'Quad'";?>" method="post" align="middle">
	<div class="btn-group-wrap">
		<div class="btn-group-vertical round" role="group" aria-label="...">
		<?php
			if($basement == true){
				echo "<button type='submit' class='btn btn-default' name='basement'>Basement";
			}
			for($i = 1; $i<=$floors; $i = $i + 1){
				echo "<button type='submit' class='btn btn-default' name='$i'>Floor $i";
			}
		?>
		</div>
	</div>
  </form>
  </div>
</body>
<?php
mysqli_close($link);
?>

