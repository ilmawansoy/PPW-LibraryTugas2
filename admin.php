<?php
	session_start();

	if (!(isset($_SESSION['login_session']) && $_SESSION['login_session'] != '')||$_SESSION['login_session']['role']==='user') {
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

	function selectAdminBookList($table) {
		$conn = connectDB();
		
		$sql = "SELECT * FROM $table";
		
		if(!$result = mysqli_query($conn, $sql)) {
			die("Error: $sql");
		}
		mysqli_close($conn);
		return $result;
	}
	
	function addBook(){
		$conn = connectDB();

		$url = $_POST['src'];
		$title = $_POST['title'];
		$author = $_POST['author'];
		$publisher = $_POST['publisher'];
		$desc = $_POST['description'];
		$qntty = $_POST['qntty'];		
		$cek = false;
		
		$result = selectAdminBookList("book");

		while ($row = $result->fetch_assoc()) {
			if ($row['title'] == $title) {
				$cek = true;
				$jml = $row['quantity'];
				$book_id = $row['book_id'];
			} 
		}

		if ($cek) {
			$qntty = $qntty + $jml;
			$sql = "UPDATE book SET quantity='$qntty' WHERE book_id='$book_id'";
		} else {
			$sql = "INSERT into book (img_path, title, author, publisher, description, quantity) VALUES ('$url','$title', '$author', '$publisher', '$desc', '$qntty')";
		}
		if($result = mysqli_query($conn, $sql)) {
			//locate ke book details <-------------------- INI
			echo "success";
			header("Location: admin.php");
		} else {
			die("Error: $sql");
		}
		mysqli_close($conn);
	}

	if($_SERVER['REQUEST_METHOD']==='POST'){
		if($_POST['command']==="add"){
			addBook();
		}
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Page</title>
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
    <body>
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
			<div class="text-center">
				<form>
					<button type="button" class="btn btn-lg" data-toggle="modal" data-target="#addbook">Add New Book</button>
				</form>
			</div>			
			<div class="modal fade" id="addbook" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="addModalLabel">Add New Book</h4>
						</div>
						<div class="modal-body">
							<form action="admin.php" method="post">
								<div class="form-group">
									<label for="src">Thumbnail</label>
									<input type="url" class="form-control" id="add-src" name="src" placeholder="Image URL">
								</div>
								<div class="form-group">
									<label for="title">Title</label>
									<input type="text" class="form-control" id="add-title" name="title" placeholder="Book Title" required>
								</div>
								<div class="form-group">
									<label for="author">Author</label>
									<input type="text" class="form-control" id="add-author" name="author" placeholder="Book Author">
								</div>
								<div class="form-group">
									<label for="publisher">Publisher</label>
									<input type="text" class="form-control" id="add-publisher" name="publisher"
									placeholder="Book Publisher">
								</div>
								<div class="form-group">
									<label for="description">Description</label>
									<input type="text" class="form-control" id="add-description" name="description" placeholder="Book Description">
								</div>
								<div class="form-group">
									<label for="jenis">Quantity</label>
									<input type="number" class="form-control" id="add-qntty" name="qntty">
								</div>
								<input type="hidden" id="add-book" name="book">
								<input type="hidden" id="add-command" name="command" value="add">
								<button type="submit" class="btn btn-primary">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class='table table-striped'>
					<thead> <tr> <th>ID</th> <th>Thumbnail</th> <th>Title</th> <th>Author</th> <th>Publisher</th> <th>Description</th> <th>Quantity</th> </tr> </thead>
					<tbody>
						<?php
							$book = selectAdminBookList("book");
							while ($row = mysqli_fetch_row($book)) {
								echo "<tr>";
								foreach($row as $key => $value) {
									if(substr($value,0,4)==='http'){
										echo '<td><img src="'. $value .'"alt="cover" height="300" width="200"/></td>';
									}else{
										echo "<td>$value</td>";		
									}
								}
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="text-center">
				<i class="fa fa-angle-up" style="font-size:30px;color:black;"></i>
				<h4 class="to-top" style="margin-top: 0px;margin-bottom: 80px;">Back to Top</h4>
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