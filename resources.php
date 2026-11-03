<?php
ob_start();
ini_set('date.timezone', 'Asia/Kuala_Lumpur');
date_default_timezone_set('Asia/Kuala_Lumpur');
include('testFunctions.php');

function funShowVideo()
{
    global $db;
    $option = '';
    $sql1 = "SELECT id, title, url, createdate FROM tutorials WHERE type = 'vimeo' ORDER BY id LIMIT 6";
    $result1 = $db->query($sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $option .= '<div class="col-lg-4 col-md-6">
      <div class="appie-blog-item mt-30 wow animated fadeInUp animated" data-wow-duration="3000ms"
          data-wow-delay="200ms"
          style="visibility: visible; animation-duration: 3000ms; animation-delay: 200ms; animation-name: fadeInUp;">
          <div class="thumb">
              <iframe class="embed-responsive-item" src="' . $row1['url'] . '"
                  title="YouTube video player" frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen></iframe>
          </div>
          <div class="content">
              <div class="blog-meta">
                  <ul>
                      <li>' . date('M d, Y', strtotime($row1['createdate'])) . '</li>
                      <li><a href="#">#tutorial</a></li>
                  </ul>
              </div>
              <h3 class="title"><a href="#">' . $row1['title'] . '</a></h3>
          </div>
      </div>
  </div>';
    }
    return $option;
}
?>
<!DOCTYPE html>
<html
    class=" sizes customelements history pointerevents postmessage webgl websockets cssanimations csscolumns csscolumns-width csscolumns-span csscolumns-fill csscolumns-gap csscolumns-rule csscolumns-rulecolor csscolumns-rulestyle csscolumns-rulewidth no-csscolumns-breakbefore no-csscolumns-breakafter no-csscolumns-breakinside flexbox picture srcset webworkers"
    lang="en">

<head>
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

    <!--====== Fonts css ======-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">

    <!--====== Fontawesome css ======-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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


    <style>
        .font-caveat {
            font-family: "Caveat", cursive;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .caveat-underline {
            text-decoration: underline;
        }
    </style>

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
                                <li><a href="#">Home</a></li>
                                <li><a href="../ucua300/index.php?page=login">Demo</a></li>
                                <li><a href="docs/UCUA_2.0_Guide_V2.pdf" target="_blank">Documentation</a></li>
                                <li><a href="#download">Mobile Download</a></li>
                                <li><a href="#download2">Desktop Download</a></li>
                                <!--li><a href="#learn">Learn</a></li-->
                                <li><a href="#about">About</a></li>
                                </li>
                                </li>
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
                                <li><a href="#"><i class="fa-solid fa-envelope"></i> icthelpdesk@johorport.com.my</a>
                                </li>
                                <li><a href="#"><i class="fa-solid fa-phone"></i> +6072535888 ext. 314</a></li>
                                <li><a href="#"><i class="fa-solid fa-location-pin"></i> Wisma Kontena, Ground Floor.
                                        Pasir Gudang. Johor. </a></li>
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
                            <ul class="d-md-none d-lg-block">
                                <li><a href="../ucua300/index.php?page=login">Demo</a></li>
                                <li class="menu-item-has-children active">
                                    <a href="#">More</a>
                                    <ul class="sub-menu">
                                        <li><a href="docs/UCUA_2.0_Guide_V2.pdf" target="_blank">Documentation</a></li>
                                        <li><a href="#download">Mobile Download</a></li>
                                        <li><a href="#download2">Desktop Download</a></li>
                                        <!--li><a href="#learn">Learn</a></li-->
                                        <li><a href="#about">About</a></li>
                                    </ul>
                                </li>
                            </ul>

                        </div>

                    </div>
                    <div class="col-lg-4 col-md-7 col-sm-6 col-6 order-2 order-sm-3">
                        <div class="appie-btn-box text-right">
                            <a class="login-btn" href="../ucua/login"><i class="fa-solid fa-user"></i> Login</a>
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
                <div class="col-lg-6">
                    <div class="appie-download-content">
                        <span><a id="download">Welcome to UCUA</a></span>
                        <h1 class="appie-title">UCUA for iOS & Android.</h1>
                        <p>You see, you act reporting app in the palm of your hand.</p>
                        <ul>
                            <li><a class="animated_btn animated_btn-ios" href="../apk/ucua.v012.alpha.apk">
                                    <i class="fab fa-google-play"></i> <span>Available on the <span>Google
                                            Play</span></span></a></li>
                            <li><a class="animated_btn animated_btn-ios"
                                    href="https://apps.apple.com/my/app/ucua/id1631982407"><i
                                        class="fa-brands fa-apple"></i> <span>Available on the <span>App
                                            Store</span></span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="appie-hero-thumb">
                        <div class="thumb">
                            <img src="landing_files/hero-thumb-1.png" alt="">
                        </div>
                        <div class="thumb-2">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-shape-1">
            <img src="landing_files/shape-2.png" alt="">
        </div>
        <div class="hero-shape-2">
            <img src="landing_files/shape-3.png" alt="">
        </div>
        <div class="hero-shape-3">
            <img src="landing_files/shape-4.png" alt="">
        </div>
    </section>

    <!--====== APPIE HERO PART ENDS ======-->

    <section class="appie-download-3-area pt-100" id="download2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="appie-section-title text-center">
                        <h3 class="appie-title">Download the Desktop app today!</h3>
                        <p>Download app for Desktop today — it's free.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="appie-download-3-box mt-30 mr-20 wow animated fadeInLeft" data-wow-duration="2000ms"
                        data-wow-delay="200ms">
                        <div class="content">
                            <h4 class="title">Windows</h4>
                            <p class="mb-3">Download app for Desktop today — it's free.</p>
                            <a class="btn btn-primary" href="download/ucua Setup 1.0.0.exe">
                                <i class="fa-brands fa-windows"></i> Download for Windows</a>
                        </div>
                        <div class="thumb text-center">
                            <img src="img/desktop.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="appie-download-3-box mt-30 ml-20 wow animated fadeInRight" data-wow-duration="2000ms"
                        data-wow-delay="400ms">
                        <div class="content">
                            <h4 class="title">Apple</h4>
                            <p class="mb-3">Download app for Apple today — it's free.</p>
                            <a class="btn btn-secondary" href="#"><i class="fab fa-apple"></i> Download for Mac</a>
                        </div>
                        <div class="thumb text-right">
                            <img src="img/desktop.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== APPIE FEATURES PART START ======-->

    <section class="appie-features-area pt-100" id="features">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <div class="appie-features-tabs-btn">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home"
                                role="tab" aria-controls="v-pills-home" aria-selected="false"><i class="fas fa-cog"></i>
                                Unsafe Condition</a>
                            <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile"
                                role="tab" aria-controls="v-pills-profile" aria-selected="false"><i
                                    class="fas fa-exclamation-triangle"></i> Unsafe Action</a>
                            <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages"
                                role="tab" aria-controls="v-pills-messages" aria-selected="false"><i
                                    class="fas fa-bell"></i> Report Condition</a>
                            <a class="nav-link " id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings"
                                role="tab" aria-controls="v-pills-settings" aria-selected="true"><i
                                    class="fas fa-lock"></i> Report Action</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="appie-features-thumb text-center animated fadeInUp"
                                        data-wow-duration="2000ms" data-wow-delay="200ms">
                                        <img src="landing_files/hero-thumb-3.png" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="appie-features-content animated fadeInRight" data-wow-duration="2000ms"
                                        data-wow-delay="600ms">
                                        <span>Unsafe Condition</span>
                                        <h3 class="title">What do you <br> want to report?</h3>
                                        <p>Any condition that could cause undue hazard to life, limb or health of any
                                            person authorized or expected to be on or about the premises.</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                            aria-labelledby="v-pills-profile-tab">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="appie-features-thumb text-center animated fadeInUp"
                                        data-wow-duration="2000ms" data-wow-delay="200ms">
                                        <img src="landing_files/hero-thumb-4.png" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="appie-features-content animated fadeInRight" data-wow-duration="2000ms"
                                        data-wow-delay="600ms">
                                        <span>Unsafe Action</span>
                                        <h3 class="title">What do you <br> want to report?</h3>
                                        <p>Any actions performed any time an employee fails to abide by safety rules and
                                            protocols. These actions include performing a job without the necessary
                                            safety equipment.</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                            aria-labelledby="v-pills-messages-tab">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="appie-features-thumb text-center animated fadeInUp"
                                        data-wow-duration="2000ms" data-wow-delay="200ms">
                                        <img src="landing_files/hero-thumb-1.png" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="appie-features-content animated fadeInRight" data-wow-duration="2000ms"
                                        data-wow-delay="600ms">
                                        <span>Report Condition</span>
                                        <h3 class="title">What do you <br> want to report?</h3>
                                        <p>Any condition that could cause undue hazard to life, limb or health of any
                                            person authorized or expected to be on or about the premises.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade " id="v-pills-settings" role="tabpanel"
                            aria-labelledby="v-pills-settings-tab">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <div class="appie-features-thumb text-center animated fadeInUp"
                                        data-wow-duration="2000ms" data-wow-delay="200ms">
                                        <img src="landing_files/hero-thumb-1.png" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="appie-features-content animated fadeInRight" data-wow-duration="2000ms"
                                        data-wow-delay="600ms">
                                        <span>Report Action</span>
                                        <h3 class="title">What do you <br> want to report?</h3>
                                        <p>Unsafe actions are performed any time an employee fails to abide by safety
                                            rules and protocols. These actions include fighting, horseplay, or
                                            performing a job without the necessary safety equipment.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="features-shape-1">
            <img src="landing_files/shape-6.png" alt="">
        </div>
        <div class="features-shape-2">
            <img src="landing_files/shape-7.png" alt="">
        </div>
        <div class="features-shape-3">
            <img src="landing_files/shape-8.png" alt="">
        </div>
    </section>

    <!--====== APPIE FEATURES PART ENDS ======-->

    <!--====== APPIE TRAFFIC PART START ======-->

    <section class="appie-traffic-area pt-140 pb-180">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="appie-traffic-title">
                        <span>Administration</span>
                        <h3 class="title">Administrative Features.</h3>
                        <p>Below is a list of few management modules available.</p>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="appie-traffic-service mb-30">
                                <div class="icon">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <h5 class="title">User Management</h5>
                                <p>Manage internal and external users.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="appie-traffic-service item-2 mb-30">
                                <div class="icon">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                                <h5 class="title">Roles Management</h5>
                                <p>Manage internal and external user roles.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="appie-traffic-service item-3">
                                <div class="icon">
                                    <i class="fa-solid fa-xmark"></i>
                                </div>
                                <h5 class="title">Offense Code</h5>
                                <p>Manage offense codes.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="appie-traffic-service item-4">
                                <div class="icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <h5 class="title">Location</h5>
                                <p>Manage location.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="traffic-thumb ">
            <img class="wow animated fadeInRight animated" data-wow-duration="2000ms" data-wow-delay="200ms"
                src="landing_files/traffic-thumb.png" alt=""
                style="visibility: visible; animation-duration: 2000ms; animation-delay: 200ms; animation-name: fadeInRight;">
        </div>
    </section>

    <!--====== APPIE TRAFFIC PART ENDS ======-->




    <!--====== APPIE BLOG PART START ======-->

    <!--section class="appie-blog-area pt-90 pb-95">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="appie-section-title text-center">
                        <h3 class="appie-title"><a id="learn">Learn</a></h3>
                        <p>Access all of UCUA's training and resources in one convenient place.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php echo funShowVideo(); ?>
            </div>
        </div>
    </section-->

    <!--====== APPIE BLOG PART ENDS ======-->

    <!-- cta start -->
    <!--section class="appie-blog-area pt-90 pb-95">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="appie-section-title text-center">
                        <h3 class="appie-title"><a id="about">Meet our Team</a></h3>
                        <p>The team behind UCUA.</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-12">

                    <div class="row featurette">
                        <div class="col-md-7 order-md-2">
                            <div class="appie-blog-item mt-30 wow animated fadeInUp animated" data-wow-duration="3000ms" data-wow-delay="600ms">
                                <h2 class="featurette-heading">Mohamad Taufik Abdul Razak</h2>
                                <p class="lead">Project Lead <a
                                        href="https://www.linkedin.com/in/mohamad-taufik-abdul-razak-3405a2197/"
                                        target="_blank"><i class="fa-brands fa-linkedin"></i></a></p>
                                <p class="lead">Taufik is an Experience project manager with substantial experience in
                                    leading all stages of technological projects. He is proficient in project management
                                    methodologies. Excels at motivating and managing team to obtain top-level
                                    performance, also a top communicator accustomed to interfacing with high-level
                                    executives.</p>
                            </div>
                        </div>
                        <div class="col-md-5 order-md-1">
                            <div class="appie-blog-item mt-30 wow animated fadeInUp animated" data-wow-duration="3000ms" data-wow-delay="600ms">
                                <div class="thumb">
                                    <img src="img/taufik.jpg" alt="image-demo" class="rounded img-fluid">
                                </div>
                            </div>
                        </div>
                    </div-->

    <!--div class="row featurette">
          <div class="col-md-7">
            <div class="appie-blog-item mt-30 wow animated fadeInUp animated" data-wow-duration="3000ms" data-wow-delay="600ms" style="visibility: visible; animation-duration: 3000ms; animation-delay: 600ms; animation-name: fadeInUp;">
                <h2 class="featurette-heading">Khairi Juri</h2>
                <p class="lead">Tech Lead <a href="#" target="_blank"><i class="fa-brands fa-linkedin"></i></a></p>
                <p class="lead">Khairi is a Full stack developer with a passion for developing great software. He like building things that delight and designing elegant solutions for complex problems. His mission is to build a product that makes stakeholder look and feel incredible.</p>
            </div>
          </div>
          <div class="col-md-5">
            <div class="appie-blog-item mt-30 wow animated fadeInUp animated" data-wow-duration="3000ms" data-wow-delay="600ms" style="visibility: visible; animation-duration: 3000ms; animation-delay: 600ms; animation-name: fadeInUp;">
                <img src="img/kj.jpg" alt="image-demo" class="rounded img-fluid">
            </div>
          </div>
        </div-->
    <!--/div>
                <div class="col-lg-12">
                    <div class="team-btn text-center mt-50">
                        <a class="main-btn" href="default.php?page=page&t=About">
                            View all Members <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section-->
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
                    <h4 class="title">Get In Touch</h4>
                    <a id="contact">Connect with us.</a>
                    <div class="footer-widget-info">
                        <ul>
                            <li><i class="fa-solid fa-envelope"></i> icthelpdesk@johorport.com.my</li>
                            <li><i class="fa-solid fa-phone"></i> +6072535888 ext. 314</li>
                            <li><i class="fa-solid fa-location-pin"></i> Wisma Kontena, Ground Floor. Pasir Gudang.
                            </li>
                        </ul>
                    </div>
                    <div class="footer-navigation">

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
                    <h4 class="title">Company</h4>
                    <div class="footer-navigation">

                        <ul>
                            <li><a href="#about">About</a></li>
                            <li><a href="#contact">Contact</a></li>
                            <li><a href="#">FAQs</a></li>
                            <li><a href="index.php?page=terms">Terms</a></li>
                            <li><a href="index.php?page=privacy">Privacy</a></li>

                        </ul>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer-copyright d-flex align-items-center justify-content-between pt-35">
                        <p class="copyright-text">Copyright &copy; 2022 UCUA. All rights reserved.</p>
                        <div class="apps-download-btn mt-0 float-end">
                            <ul>
                                <li><a class="animated_btn animated_btn-ios" href="../apk/ucua.v012.alpha.apk">
                                        <i class="fab fa-google-play"></i> <span>Download for <span>Android</span></span></a></li>
                                <li><a class="animated_btn animated_btn-ios"
                                        href="https://apps.apple.com/my/app/ucua/id1631982407">
                                        <i class="fa-brands fa-apple"></i> <span>Download for <span>iOS</span></span></a></li>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form method="post" action="default.php?page=search">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Search</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="test" name="search" class="form-control" placeholder="Enter IC number" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </form>
    </div>





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
    <script>
        function blockSpecialChar(e) {
            var k;
            document.all ? k = e.keyCode : k = e.which;
            return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
        }
    </script>
</body>

</html>
<?php ob_end_flush(); ?>