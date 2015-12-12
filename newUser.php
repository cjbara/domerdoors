<html>
<head>
<title>Create New User</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
  <div class="jumbotron">
	<h1>New User</h1>
  </div>
  <div class="col-sm-6">
	<form action="newUserAction.php" method="post">

	    Name (First Last):  <input class="form-control" type="textbook" name="name" /><br />
	    NetID:  <input class="form-control" type="textbook" name="netID" /><br />
	    Enter a new Password: <input class="form-control" type="password" name="password" /><br />
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
	    <p> Please don't use a valuable password. This data is not encrypted. </p>
<!--		Staff Password: <input class="form-control" type="password" name="staffPassword" /><br />
		<p> If you are a staff member, please enter the administrative sensitive password </p>
-->	
	    <input class="btn btn-primary" type="submit" name="submit" value="Submit me!" />
	</form>
  </div>
</div>
</body>
</html>

