<?php
	session_start();

	if (!(isset($_SESSION['login_session']) && $_SESSION['login_session'] != '')||$_SESSION['login_session']['role']==='admin') {
		header ("Location: index.php");
	}

	function connectDB() {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "personal_library";
		
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		
		// Check connection
		if (!$conn) {
			die("Connection failed: " + mysqli_connect_error());
		}
		return $conn;
	}

	function allBorrowedBook() {
		$conn = connectDB();

		$userid = $_SESSION['login_session']['user_id'];		
		$sql = "SELECT book_id, img_path, title, author, publisher, description FROM book WHERE book_id IN (SELECT book_id FROM loan WHERE user_id='$userid')";

		if(!$result = mysqli_query($conn, $sql)) {
			die("Error: $sql");
		}
		mysqli_close($conn);
		return $result;
	}

	function deleteRecord($id){
		$conn = connectDB();
		
		$sql = "DELETE FROM loan WHERE book_id=$id";
		
		if(!$result = mysqli_query($conn, $sql)) {
			die("Error: $sql");
		}
		mysqli_close($conn);
	}

	function addBook($id){
		$conn = connectDB();

		$sql = "UPDATE book SET quantity=quantity+1 WHERE book_id=$id";

		if(!$result = mysqli_query($conn, $sql)) {
			die("Error: $sql");
		}
		mysqli_close($conn);
	}

	if($_SERVER['REQUEST_METHOD']==='POST'){
		if($_POST['command']==="return"){
			deleteRecord($_POST['return']);
			addBook($_POST['return']);
		}
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Loan Book List</title>
        <script src="libs/jquery/dist/jquery.min.js"></script>
	    <script src="libs/bootstrap/dist/js/bootstrap.min.js"></script>
	    <script src="src/js/script.js"></script>
	    <link rel="stylesheet" type="text/css" href="libs/bootstrap/dist/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
   		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
   		<link rel="stylesheet" href="src/css/style.css">

    </head>
    <body >
    	<nav class="navbar navbar-default navbar-fixed-top">
	    	<div class="container">
	        <!-- Brand and toggle get grouped for better mobile display -->
	        	<div class="navbar-header">
	          		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-2">
	            		<span class="sr-only">Toggle navigation</span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			        </button>
	        		<a class="navbar-brand" href="index.php">BiblioPhile</a>
	        	</div>
	        <!-- Collect the nav links, forms, and other content for toggling -->
		        <div class="collapse navbar-collapse" id="navbar-collapse-2">
					<ul class="nav navbar-nav navbar-right">
			            <li><a href="index.php">HOME</a></li>
			            <li><a href="home.php">BOOKS</a></li>
			            <?php
		            		if ($_SESSION['login_session']['role'] == 'admin') {
		            			echo '
		            			<li><a href="admin.php">ADD BOOKS</a></li>
		            			<li><a class="btn btn-default btn-outline btn-circle"  href="services/logout.php">LOGOUT</a></li>
					          </ul>';
		            		} else {
			            		echo '
			            		<li><a href="user.php">MY BOOKS</a></li>
			            		<li><a class="btn btn-default btn-outline btn-circle"  href="services/logout.php">LOGOUT</a></li>
						          </ul>';
						      }
			            ?>
			    </div><!-- /.navbar-collapse -->
			</div><!-- /.container -->
	    </nav><!-- /.navbar -->
	    <nav class="navbar navbar-default"></nav>
		<div class="container">	
			<h1 style="text-align: center;">Loan Book List</h1>
			<div class="table-responsive">
				<table class='table table-striped'>
					<thead> <tr> <th style='padding: 20px;'></th> <th>Title</th> <th>Author</th> <th>Publisher</th> <th>Description</th> <th></th> <th></th><th></th> </tr> </thead>
					<tbody>
						<?php
							$loan = allBorrowedBook();
							$num=1;
							while ($row = $loan->fetch_assoc()) {
								echo '<tr><td>'.$num.'</td>
						  		<td><img src="'. $row['img_path'] .'" alt="cover" height="300" width="200"/></td>
								<td>'. $row['title'] .'</td>		
								<td>'. $row['author'] .'</td>
								<td>'. $row['publisher'] .'</td>
								<td>'. $row['description'] .'</td>
						  		<td>
								<form action="user.php" method="post">
									<input type="hidden" id="return" name="return" value="'.$row['book_id'].'">
									<input type="hidden" id="return-command" name="command" value="return">
									<button type="submit" class="btn">Return</button>
								</form></td>
								<td>
								<a href="detail.php?id='.$row['book_id'].'" type="submit" class="btn">Detail</a>
								</td></tr>';
								++$num;
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<footer>
	        <div class="container text-center">
	            <div class="col-md-4">
                    <span class="copyright">Copyright &copy; Bibliophile, 2016</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter fa-lg"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook fa-lg"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin fa-lg"></i></a>
                        </li>
                    </ul>
                </div>
                <div id="term" class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="#">Privacy Policy</a>
                        </li>
                        <li><a href="#">Terms of Use</a>
                        </li>
                    </ul>
                </div>
	        </div>
	    </footer>
    </body>
</html>