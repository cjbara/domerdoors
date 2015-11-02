<html>
<head>
<title>Delete User</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
  <div class="jumbotron">
        <h1>Delete User</h1>
  </div>
  <div class="col-sm-6">
        <form action="deleteUserAction.php" method="post">

            Enter NetID of user to be deleted: 
	    <input class="form-control" type="textbook" name="netID" /><br />

	    <div class="alert alert-warning">Warning: this cannot be undone</div>
            <input class="btn btn-danger" type="submit" name="submit" value="Delete User" />
        </form>
  </div>
</div>
</body>
</html>
