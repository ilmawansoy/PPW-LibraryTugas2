<?php
session_start();
	function connectDB() {
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "personal_library";
		
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
	
		if (!$conn) {
			die("Connection failed: " + mysqli_connect_error());
		}
		return $conn;
	}
?>
<!doctype html>
<html>
    <head>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Bibliophile</title>
		<script src="libs/jquery/dist/jquery.min.js"></script>
		<script src="libs/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="src/js/script.js"></script>
		<script type="text/javascript" src="src/js/ajax.js"></script>
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
	    <nav class="navbar navbar-default"></nav>
		<div class="container">
			<div class="row books">
				<div class="col-sm-4">
					<?php
						$conn = connectDB();
						$id = $_GET['id'];
						$sql = "SELECT img_path FROM book WHERE book_id=$id";
						$result = $conn->query($sql);
						
						while ($row = $result->fetch_assoc()) {
							echo '<img src="'. $row['img_path'] .'" alt="cover" height="400" width="300"/>';
						}
					?>
				</div>
				<div class="col-sm-8">
					<?php
						$conn = connectDB();
						$id = $_GET['id'];
						$sql = "SELECT title, author, publisher, description,quantity FROM book WHERE book_id=$id";
						$result = $conn->query($sql);
						$quantity = 0;
						$publisher;
						
						while ($row = $result->fetch_assoc()) {
							echo '<h3>'. $row['title'] .'</h3><h4>By '. $row['author'] .'</h4><p>'. $row['description'] .'</p>';
							$quantity = $row['quantity'];
							$publisher = $row['publisher'];
						}
						if (isset($_SESSION['status_pinjam'])) {
							$status = $_SESSION['status_pinjam'];
							echo "<br><div class='alert alert-info alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>$status</div>";
							unset($_SESSION['status_pinjam']);
						}
					?>
					<hr>
					<div class="col-sm-6">
						<h5>PUBLISHER</h5>
						<?php echo $publisher;?>
					</div>
					<div class="col-sm-6">
						<h5>QUANTITY</h5>
						<?php echo $quantity;?>
					</div>
					<div class="row">
						<div class="col-sm-12">
						<hr>
							<?php if (isset($_SESSION['login_session'])) {
								if ($quantity > 0 && $_SESSION['login_session']['role']==='user') {
									echo '<div class="tombol"><a href="services/borrow.php?id='. $id.'&qnt='.$quantity.'" type="submit" class="btn">Borrow</a></div>';
								}
								echo '
								<div class="tombol"><a data-toggle="collapse" href="#review" class="btn">Add Review</a></div>
								';
							}?>
						</div>
					</div>
					<div id="review" class="panel-collapse collapse">
						<?php
							if (isset($_SESSION['login_session'])) {
								echo '
									<div class="form-group">
									    <label for="comment">Your review:</label>
									    <textarea class="form-control" rows="4" name="content" id="content"></textarea><br>
										<button type="submit" class="btn" id="add-review">Submit</button>
									</div>';
							}
						?>
					</div>
				</div>
			</div>
			<h3>User Reviews</h3>
			<div class="row review">
				<input type="hidden" id="review-bookid" name="bookid" value="<?php echo $_GET['id']; ?>">
				<input type="hidden" id="review-userid" name="userid" value="<?php echo $_SESSION['login_session']['user_id']; ?>">
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