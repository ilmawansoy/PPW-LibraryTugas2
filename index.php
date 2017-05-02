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
		<title>BiblioPhile: See Review & Borrow Books Online</title>
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
		<div class="home-header">
			<div class="container">
				<div class="jumbotron">
					<h1 id="home-h1">Read Book.</h1>
					<h3 id="home-h3">-Anonymous, 2016</h3>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row about text-center">
				<h1 style="font-family: Righteous; font-size: 70px; padding-bottom: 20px;">BiblioPhile</h1>
				<div class="col-sm-4">
	               <h3>Borrow</h3> 
	               <span class="fa fa-bookmark"></span>
	            </div>
	            <div class="col-sm-4">
	               <h3>Read</h3>
	               <span class="fa fa-book"></span>
	            </div>
	            <div class="col-sm-4">
	               <h3>Review</h3>
	               <span class="fa fa-comments-o"></span>                
	            </div>
			</div>
			<div class="row collection">
					<div class="text-center" style="padding:30px 0;">
						<a href="home.php" id="collection" style="font-size: 38px;color: black;">See Our Collection</a>
					</div>
				<?php
					$conn = connectDB();
					$sql = 'SELECT img_path FROM book LIMIT 6';
					$result = $conn->query($sql);
					while ($row = $result->fetch_assoc()) {
						echo '<div class="col-sm-2">
						<img src="'. $row['img_path'] .'" alt="cover" height="190" width="120"/></div>';
					}
				?>
			</div>
			<!-- sumber: http://bootsnipp.com/snippets/featured/responsive-quote-carousel -->
			<div class="container">
				<div class="row">
					<div class='col-md-offset-2 col-md-8 text-center'>
						<h1>Why Read Books?</h1>
					</div>
				</div>
				<div class='row'>
					<div class='col-md-offset-2 col-md-8'>
						<div class="carousel slide" data-ride="carousel" id="quote-carousel">
							<!-- Bottom Carousel Indicators -->
							<ol class="carousel-indicators">
								<li data-target="#quote-carousel" data-slide-to="0" class="active"></li>
								<li data-target="#quote-carousel" data-slide-to="1"></li>
								<li data-target="#quote-carousel" data-slide-to="2"></li>
							</ol>

							<!-- Carousel Slides / Quotes -->
							<div class="carousel-inner">

								<!-- Quote 1 -->
								<div class="item active">
									<blockquote>
										<div class="row">
											<div class="col-sm-3 text-center">
												<img class="img-circle" src="http://pixel.nymag.com/imgs/daily/vulture/2015/11/22/22-grrm-syria.w529.h529.jpg " alt="george" style="width: 100px;height:100px;">
											</div>
											<div class="col-sm-9">
												<p>A reader lives a thousand lives before he dies, said Jojen. The man who never reads lives only one</p>
												<small>George R.R. Martin, A Dance with Dragons</small>
											</div>
										</div>
									</blockquote>
								</div>
								<!-- Quote 2 -->
								<div class="item">
									<blockquote>
										<div class="row">
											<div class="col-sm-3 text-center">
												<img class="img-circle" src="http://a3.files.biography.com/image/upload/c_fit,cs_srgb,dpr_1.0,h_1200,q_80,w_1200/MTIwNjA4NjMzODc0NTE1NDY4.jpg" alt="stephen" style="width: 100px;height:100px;">
											</div>
											<div class="col-sm-9">
												<p>Books are a uniquely portable magic.</p>
												<small>Stephen King, On Writing: A Memoir of the Craft</small>
											</div>
										</div>
									</blockquote>
								</div>
								<!-- Quote 3 -->
								<div class="item">
									<blockquote>
										<div class="row">
											<div class="col-sm-3 text-center">
												<img class="img-circle" src="https://pbs.twimg.com/profile_images/695563324444921856/5kJZz_ha.jpg" alt="neil" style="width: 100px;height:100px;">
											</div>
											<div class="col-sm-9">
												<p>A book is a dream that you hold in your hand.</p>
												<small>Neil Gaiman</small>
											</div>
										</div>
									</blockquote>
								</div>
							</div>

							<!-- Carousel Buttons Next/Prev -->
							<a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
							<a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
						</div>                          
					</div>
				</div>
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
	    </footer>
	</body>
</html>