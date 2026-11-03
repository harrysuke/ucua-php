<?php
include('testFunctions.php');
$title = 'UCUA';

function showName($n)
{
  global $db;
  $sql = "SELECT Name, Email FROM user WHERE staffId = '" . $n . "'";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
    return $row['Name'];
  } else {
    return $n;
  }
}

if(isset($_GET['lock'])){
	unset($_SESSION['user']);
}

if(isset($_POST['submit'])){
  $sql = "SELECT *, userroles.rolename FROM user LEFT JOIN userroles ON userroles.id = user.AccessLevel WHERE staffId ='".$_POST['staffid']."' LIMIT 1";
  $result = mysqli_query($db, $sql);
  $row = mysqli_fetch_array($result);
  if($row){
    $_SESSION['user'] = $row;
    echo '<meta http-equiv="refresh" content="0; URL=default.php?page=dashboard" />';
    exit;
  }else{
    echo '<meta http-equiv="refresh" content="0; URL=default.php?page=registerMini&staffid='.$_POST['staffid'].'" />';
	exit;
  }
}
?>
<!DOCTYPE html>
<!-- saved from url=(0059)https://themesbrand.com/minia/layouts/pages-comingsoon.html -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        
        <title><?php echo $title;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin &amp; Dashboard Template" name="description">
        <meta content="Themesbrand" name="author">
        <!-- App favicon -->
        <link rel="shortcut icon" href="https://themesbrand.com/minia/layouts/assets/images/favicon.ico">
        
        <!-- swiper css -->
        <link rel="stylesheet" href="./simple_files/swiper-bundle.min.css">

        <!-- preloader css -->
        <!--link rel="stylesheet" href="./simple_files/preloader.min.css" type="text/css"-->

        <!-- Bootstrap Css -->
        <link href="./simple_files/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css">
        <!-- Icons Css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="./simple_files/icons.min.css" rel="stylesheet" type="text/css">
        <!-- App Css-->
        <link href="./simple_files/app.min.css" id="app-style" rel="stylesheet" type="text/css">

    </head>

    <body class="pace-done " data-new-gr-c-s-check-loaded="14.1030.0" data-gr-ext-installed=""><div class="pace pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

    <!-- <body data-layout="horizontal"> -->
        <div class="preview-img">
            <div class="swiper-container preview-thumb swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">
                <div class="swiper-wrapper" id="swiper-wrapper-6e81fe3e12f104743" aria-live="polite">
                    <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 3" style="width: 1440px;">
                        <div class="slide-bg" style="background-image: url(./img/JPB20-1.jpg);"></div>
                    </div>
                    <div class="swiper-slide swiper-slide-next" role="group" aria-label="2 / 3" style="width: 1440px;">
                        <div class="slide-bg" style="background-image: url(./img/JPB20-2.jpg);"></div>
                    </div>
                    <div class="swiper-slide" role="group" aria-label="3 / 3" style="width: 1440px;">
                        <div class="slide-bg" style="background-image: url(./img/JPB20-3.jpg);"></div>
                    </div>
                </div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <!-- preview-thumb -->
            <div class="swiper-container preview-thumbsnav swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events swiper-container-thumbs">
                <div class="swiper-wrapper" id="swiper-wrapper-7d1182ec4bddad5f" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
                    <div class="swiper-slide swiper-slide-thumb-active swiper-slide-visible swiper-slide-active" role="group" aria-label="1 / 3" style="width: 27.3333px; margin-right: 14px;">
                        <div>
                            <img src="./img/JPB20-1.jpg" alt="" class="avatar-sm nav-img rounded-circle">
                        </div>
                    </div>
                    <div class="swiper-slide swiper-slide-visible swiper-slide-next" role="group" aria-label="2 / 3" style="width: 27.3333px; margin-right: 14px;">
                        <div>
                            <img src="./img/JPB20-2.jpg" alt="" class="avatar-sm nav-img rounded-circle">
                        </div>
                    </div>
                    <div class="swiper-slide swiper-slide-visible" role="group" aria-label="3 / 3" style="width: 27.3333px; margin-right: 14px;">
                        <div>
                            <img src="./img/JPB20-3.jpg" alt="" class="avatar-sm nav-img rounded-circle">
                        </div>
                    </div>
                </div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            <!-- preview-thumb -->
        </div>
        <!-- preview bg -->

        <div class="coming-content min-vh-100 py-4 px-3 py-sm-5">
            <div class="bg-overlay bg-primary"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center py-4 py-sm-5">

                            <div class="mb-5">
                                <a href="#">
                                    <img src="./img/logo-sm.svg" alt="" height="30" class="me-1"><span class="logo-txt text-white font-size-22">UCUA</span>
                                </a>
                            </div>
                            <h3 class="text-white mt-5">Enter your password to unlock the screen.</h3>
                            <p class="text-white-50 font-size-16"></p>
                            
                            
                        
                            <form class="app-search mt-5 mx-auto" method="post" action="default.php?page=lockScreen">
                                <div class="position-relative">
                                    <input type="password" name="staffid" class="form-control" placeholder="Enter your staff ID">
                                    <button class="btn btn-primary" type="submit" name="submit"><i class="fa-solid fa-right-to-bracket"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- coming-content -->

        <!-- JAVASCRIPT -->
        <script src="./simple_files/jquery.min.js.download"></script>
        <script src="./simple_files/bootstrap.bundle.min.js.download"></script>
        <script src="./simple_files/metisMenu.min.js.download"></script>
        <script src="./simple_files/simplebar.min.js.download"></script>
        <script src="./simple_files/waves.min.js.download"></script>
        <script src="./simple_files/feather.min.js.download"></script>
        <!-- pace js -->
        <script src="./simple_files/pace.min.js.download"></script>
        <!-- swiper js -->
        <script src="./simple_files/swiper-bundle.min.js.download"></script>
        <!-- Plugins js-->
        <script src="./simple_files/jquery.countdown.min.js.download"></script>

        <!-- Countdown js -->
        <script src="./simple_files/coming-soon.init.js.download"></script>
    

</body>
</html>