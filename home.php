<?php 
	session_start();

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

	if(isset($_SESSION['book_id'])) {
		unset($_SESSION['book_id']);
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Bibliophile: See Review & Borrow Books Online</title>
		<script src="libs/jquery/dist/jquery.min.js"></script>
		<script src="libs/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="src/js/script.js"></script>
		<link rel="stylesheet" type="text/css" href="libs/bootstrap/dist/css/bootstrap.min.css">
		<link href="https://fonts.googleapis.com/css?family=Lato|Montserrat|Oswald|Righteous" rel="stylesheet">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
		<link rel="stylesheet" href="src/css/style.css">
   	</head>
    
    <body class="page">		
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
			            	if(!(isset($_SESSION['login_session']))) {
			            		echo '
						          <li><a class="btn btn-default btn-outline btn-circle"  data-toggle="collapse" href="#nav-collapse2" aria-expanded="false" aria-controls="nav-collapse2">Sign in</a></li>
						          </ul>
						          <div class="collapse nav navbar-nav nav-collapse" id="nav-collapse2">
						            <form class="navbar-form navbar-right form-inline" method="POST" action="services/login.php">
						              <div class="input-group">
							                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							                <input id="username" class="form-control" name="username" value="" placeholder="Username" required>
							            </div>
							            <div class="input-group">
							                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							                <input id="password" type="password" class="form-control" name="password" value="" placeholder="Password" required>                                        
							            </div>
							            <button type="submit" class="btn" id="login">Login</button>
						        	</form>
						          </div>';
			            	} else {
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
			            	}
			            ?>
			    </div><!-- /.navbar-collapse -->
			</div><!-- /.container -->
	    </nav><!-- /.navbar -->	    
		<div class="header">
			<div class="container">
				<div class="jumbotron">
					<h2>Personal Library</h2>
					<h4>See Review & Borrow Books Online</h4>		
				</div>
			</div>
		</div>
		<div class="container" id="book-list">
			<div class="book-list-header text-center">
				<h1><span>Book List</span></h1>
				<h5>Click to see details</h5>
				<?php
					if (isset($_SESSION['status_pinjam'])) {
						$status = $_SESSION['status_pinjam'];
						echo "<br><div class='alert alert-info alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>$status</div>";
						unset($_SESSION['status_pinjam']);
					}
				?>
			</div>
			<?php
				$conn = connectDB();
				$sql = 'SELECT book_id, img_path, title, author, publisher, quantity FROM book';
				$result = $conn->query($sql);
				$x = 0;
				while ($row = $result->fetch_assoc()) {
					$print = '<div class="col-lg-3"><div id="parent">
					<img src="'. $row['img_path'] .'" alt="cover" height="300" width="200"/><br>
					<span class="detail">
						<h5>' . $row['title']. '</h5><strong>
						Author:</strong> ' . $row['author']. '<strong><br>
						Publisher:</strong> ' . $row['publisher']. '<strong><br>
						Quantity:</strong> ' . $row['quantity']. '<br><br>
					<a href="detail.php?id='.$row['book_id'].'" type="submit" class="btn">Detail</a>
					</span></div></div>';
					
					$printlogin =  '<div class="col-lg-3"><div id="parent">
					<img src="'. $row['img_path'] .'" height="300" width="200"/><br>
					<span class="detail">
						<h5>' . $row['title']. '</h5><strong>
						Author:</strong> ' . $row['author']. '<strong><br>
						Publisher:</strong> ' . $row['publisher']. '<strong><br>
						Quantity:</strong> ' . $row['quantity']. '<br><br>
						<a href="detail.php?id='.$row['book_id'].'" type="submit" class="btn">Detail</a>
						<a href="services/borrow.php?id='.$row['book_id'].'&qnt='.$row['quantity'].'&page=home" type="submit" class="btn">Borrow</a>
						</span></div></div>
					';
					if ($x ==0) {
						if (isset($_SESSION['login_session'])) {
							if($_SESSION['login_session']['role']==='user' && $row['quantity'] > 0){
								$print = $printlogin;		
							}
						}
						echo '<div class="row slideanim">' . $print;
						$x++;
					}
					else if ($x == 3) {
						if (isset($_SESSION['login_session'])) {
							if($_SESSION['login_session']['role']==='user' && $row['quantity'] > 0){
								$print = $printlogin;		
							}
						}
						echo $print . "</div>";
						$x = 0;									
					}else {
						if (isset($_SESSION['login_session'])) {
							if($_SESSION['login_session']['role']==='user' && $row['quantity'] > 0){
								$print = $printlogin;		
							}
						}
						echo $print;
						$x++;
					}
				}
			?>		
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