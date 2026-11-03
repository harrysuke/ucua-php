<?php
include('functions.php');
ini_set('date.timezone','Asia/Kuala_Lumpur');
date_default_timezone_set('Asia/Kuala_Lumpur');
?>
<!DOCTYPE html>
<html class=" sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth no-csscolumns-breakbefore no-csscolumns-breakafter no-csscolumns-breakinside flexbox picture srcset webworkers" lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
   
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!--====== Title ======-->
    <title>UCUA Landing</title>
    
    <!--====== Favicon Icon ======-->
    <link rel="icon" type="image/png" sizes="16x16" href="img/Capture.png">
	
    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="landing_files/bootstrap.css">
    
    <!--====== Fontawesome css ======-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!--====== Magnific Popup css ======-->
    <link rel="stylesheet" href="landing_files/animate.css">
    
    <!--====== Magnific Popup css ======-->
    <link rel="stylesheet" href="landing_files/magnific-popup.css">
    
    <!--====== Slick css ======-->
    <link rel="stylesheet" href="landing_files/slick.css">
    
    <!--====== Default css ======-->
    <link rel="stylesheet" href="landing_files/custom-animation.css">
    <link rel="stylesheet" href="landing_files/default.css">
    
    <!--====== Style css ======-->
    <link rel="stylesheet" href="landing_files/style.css">

    <!-- Responsive CSS -->
    <link rel="stylesheet" href="landing_files/responsive.css">
  


  
</head>

<body>    




 <!-- test preloader start -->
 <div id="preloader-wrap" style="display: none;">
      <div id="loading" style="display: none;">
             <span id="loading-img" style="display: none;">Loading...</span>
        </div>
      </div>

   <div id="preloading">
       <img src="landing_files/traffic-thumb.png" alt="">
   </div>

   <!-- test preloader end -->



    <!--====== OFFCANVAS MENU PART START ======-->

    <div class="off_canvars_overlay">
                
    </div>
    <div class="offcanvas_menu">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="offcanvas_menu_wrapper">
                        <div class="canvas_close">
                            <a href="javascript:void(0)"><i class="fa fa-times"></i></a>  
                        </div>
                        <div class="offcanvas-brand text-center mb-40">
                            <img src="landing_files/logo.png" alt="">
                        </div>
                        <div id="menu" class="text-left ">
                            <ul class="offcanvas_main_menu">
                                <li></li>
                            </ul>
                        </div>
                        <div class="offcanvas-social">
                            <ul class="text-center">
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="#"><i class="fab fa-dribbble"></i></a></li>
                            </ul>
                        </div>
                        <div class="footer-widget-info">
                            <ul>
                                <li><a href="#"><i class="fa-solid fa-envelope"></i> icthelpdesk@johorport.com.my</a></li>
								<li><a href="#"><i class="fa-solid fa-phone"></i> +6072535888 ext. 314</a></li>
								<li><a href="#"><i class="fa-solid fa-location-pin"></i> Wisma Kontena, Ground Floor. Pasir Gudang. Johor. </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--====== OFFCANVAS MENU PART ENDS ======-->
   
    <!--====== PART START ======-->
    
    <header class="appie-header-area appie-sticky">
        <div class="container">
            <div class="header-nav-box">
                <div class="row align-items-center">
                    <div class="col-lg-2 col-md-4 col-sm-5 col-6 order-1 order-sm-1">
                        <div class="appie-logo-box">
                            <a href="index">
                                UCUA
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-1 col-sm-1 order-3 order-sm-2">
                        <div class="appie-header-main-menu">
                            <ul>
								<li><a href="../ucua300/login">Demo</a></li>
                                <li class="menu-item-has-children active">
                                    <a href="#">More</a>
                                    <ul class="sub-menu">
                                        <li><a href="http://apps.johorport.com.my/ucua/docs.pdf" target="_blank">Documentation</a></li>
                                        <li><a href="#download">Download</a></li>
                                        <li><a href="#learn">Learn</a></li>
                                        <li><a href="#about">About</a></li>
                                    </ul>
                                </li>
                            </ul>
                            
                        </div>
                        
                    </div>
                    <div class="col-lg-4  col-md-7 col-sm-6 col-6 order-2 order-sm-3">
                        <div class="appie-btn-box text-right">
                            <a class="login-btn" href="login"><i class="fa-solid fa-user"></i> Login</a>
                            <a class=" animated_btn ml-30" href="register">Get Started</a>
                            <div class="toggle-btn ml-30 canvas_open d-lg-none d-block">
                                <i class="fa fa-bars"></i>
                            </div>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a class="login-btn" href="#" data-toggle="modal" data-target="#exampleModal"><i class="fa-solid fa-magnifying-glass"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </header>
  
    
    <!--====== PART ENDS ======-->

    <!--====== APPIE HERO PART START ======-->
    
    <section class="appie-hero-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h1>Violated action</h1>
                <table class="table table-striped">
                        <thdead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Report Date</th>
                                <th scope="col">Observer Name</th>
                                <th scope="col">Location</th>
                                <th scope="col">ViolatorIc</th>
                                <th scope="col">Status Log</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        global $db;
                        $result = mysqli_query($db,"SELECT uf.Id, uf.submitdate, u.Name, l.LocationName, uf.status6, uf.isverified, uf.isrejected, uf.isnotifysherep, uf.status, uf.ViolatorIc FROM ucuaform AS uf INNER JOIN location AS l ON uf.LocationId = l.LocationId INNER JOIN user AS u ON u.staffId = uf.staffId WHERE uf.ViolatorIc = '".$_POST['search']."' ORDER BY uf.Id DESC;" );
                        $tmpCount = 1; 
                        while($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td width='5%' align='center'><?php echo $tmpCount; ?></td>
                                <td width='15%' align='center'><?php echo $row['submitdate']; ?></td>
                                <td width='15%' align='center'><?php echo $row['Name']; ?></td>
                                <td width='15%' align='center'><?php echo $row['LocationName']; ?></td>
                                <td width='20%' align='center'><?php echo $row['ViolatorIc']; ?></td>
                                <td width='15%' align='center'><?php echo $row['status']; ?></td>
                            </tr>
                    <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
        <!-- cta end -->

    <!--====== APPIE FOOTER PART START ======-->
    
    <section class="appie-footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
					<div class="logo">
						<h4 class="title">UCUA</h4>
                    </div>
					<p>Scan the QR code below to access from mobile.</p>
                    <div class="footer-about-widget">
                        <img src="img/qr-code.png">
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
					<h4 class="title">Help</h4>
                    <div class="footer-widget-info">
                        <ul>
                            <li><i class="fa-solid fa-envelope"></i> icthelpdesk@johorport.com.my</li>
                            <li><i class="fa-solid fa-phone"></i> +6072535888 ext. 314</li>
                            <li><i class="fa-solid fa-location-pin"></i> Wisma Kontena, Ground Floor. Pasir Gudang. </li>
                        </ul>
                    </div>
					<div class="footer-navigation">
                        <p>Copyright &copy; 2022 UCUA. All rights reserved.</p>
						<div class="footer-about-widget">
							<div class="social">
                            <ul>
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            </ul>
							</div>
						</div>
                    </div>
                </div>
				<div class="col-lg-4 col-md-6">
					<h4 class="title">Download</h4>
					<p>Download our mobile app for your Android or iOS device</p>
					<div class="footer-about-widget">
						<div class="appie-hero-content">
							<ul>
								<li><a class="animated_btn animated_btn-ios" href="../apk/ucua.v012.alpha.apk"><i class="fa-brands fa-android"></i> Android</a></li>
								<li><a class="animated_btn animated_btn-ios" href="https://apps.apple.com/my/app/ucua/id1631982407"><i class="fa-brands fa-apple"></i>App Store</a></li>
							</ul>
						</div>
					</div>
                </div>
            </div>
			
        </div>
    </section>
    
    <!--====== APPIE FOOTER PART ENDS ======-->


    <!--====== APPIE BACK TO TOP PART ENDS ======-->
    <div class="back-to-top" style="display: none;">
        <a href="#"><i class="fa-solid fa-arrow-up"></i></a>
    </div>
    <!--====== APPIE BACK TO TOP PART ENDS ======-->





    <!-- Modal -->
    <form method="post" action="search">
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Search</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="test" name="search" class="form-control" placeholder="Enter IC number"/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" class="btn btn-primary">Search</button>
      </div>
    </div>
  </div>
</div>
</form>


    
    
    <script src="landing_files/jquery-3.js"></script>
    <!--====== jquery js ======-->
    
    <script src="landing_files/modernizr-3.js"></script>
    <script src="landing_files/jquery-1.js"></script>
    

    <!--====== Bootstrap js ======-->

    
    <script src="landing_files/bootstrap.js"></script>
    <script src="landing_files/popper.js"></script>

    <!--====== wow js ======-->
    <script src="landing_files/wow.js"></script>
    
    <!--====== Slick js ======-->

    <script src="landing_files/jquery.js"></script>
    <script src="landing_files/waypoints.js"></script>

    <!--====== TweenMax js ======-->
    <script src="landing_files/TweenMax.js"></script>

    <!--====== Slick js ======-->
    <script src="landing_files/slick.js"></script>
    
    <!--====== Magnific Popup js ======-->
    <script src="landing_files/jquery_002.js"></script>
    
    <!--====== Main js ======-->


    <script src="landing_files/main.js"></script>

</body>
</html>