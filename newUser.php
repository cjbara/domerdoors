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
	    Graduation Year: <input class="form-control" type="textbook" name="year" /><br />
	    NetID:  <input class="form-control" type="textbook" name="netID" /><br />
	    Enter a new Password: <input class="form-control" type="textbook" name="password" /><br />
	    <p> Please don't use a valuable password. This data is not encrypted. </p>
	
	    <input class="btn btn-primary" type="submit" name="submit" value="Submit me!" />
	</form>
  </div>
</div>
</body>
</html>

