<?php
include('testFunctions-md5.php');

// For All Mobile Devices
if(! empty($_SERVER['HTTP_USER_AGENT'])){
   $useragent = $_SERVER['HTTP_USER_AGENT'];
   if( preg_match('@(iPad|iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)@', $useragent) ){
      header('Location: ../mucua/index.php');
      exit;
   }
}
?>
<!DOCTYPE html>
<!-- saved from url=(0056)https://html.nioboard.themenio.com/auths/auth-login.html -->
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="author" content="Softnio">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="UCUA campaigns are intensified to encourage employees to identify hazards, intervene and report any unsafe act and unsafe conditions.">
      <title>Auth Login - UCUA</title>
      <link rel="shortcut icon" href="http://apps.johorport.com.my/img/Capture.png">
      <link rel="icon" type="image/png" sizes="16x16" href="img/Capture.png">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@700&family=Roboto&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="css/style.css">
   </head>
   <body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg" data-new-gr-c-s-check-loaded="14.1027.0" data-gr-ext-installed="">
      <div class="nk-app-root">
         <div class="nk-main">
            <div class="nk-wrap align-items-center justify-content-center">
               <div class="container p-2 p-sm-4">
                  <div class="card overflow-hidden card-gutter-lg rounded-4 card-auth card-auth-mh">
                     
                     <div class="row g-0 flex-lg-row-reverse">
                        <div class="col-lg-5">
                           <div class="card-body h-100 d-flex flex-column justify-content-center">
                              <div class="nk-block-head text-center">
                                 <div class="nk-block-head-content">
                                    <h3 class="nk-block-title mb-1">Login to Account</h3>
                                    <p class="small">Please sign-in to your account and start the adventure.</p>
									<p><?php echo display_error(); ?></p>
                                 </div>
                              </div>
                              <form action="default.php?page=loginmd5" class="signin-form" method="POST">
                                 <div class="row gy-3">
                                    <div class="col-12">
                                       <div class="form-group">
                                          <label for="staffId" class="form-label">Staff ID</label>
                                          <div class="form-control-wrap">
                                             <input type="text" class="form-control" id="staffId" name="staffId" placeholder="Enter your staff ID" onkeypress="return blockSpecialChar(event)">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-12">
                                       <div class="form-group">
                                          <label for="password" class="form-label">Password</label>
                                          <div class="form-control-wrap"><input type="password" class="form-control" id="password" name="password" placeholder="Enter password"></div>
                                       </div>
                                    </div>
                                    <div class="col-12">
                                       <div class="d-flex flex-wrap justify-content-between">
                                          <div class="form-check form-check-sm"><input class="form-check-input" type="checkbox" value="" id="rememberMe"><label class="form-check-label" for="rememberMe"> Remember Me </label></div>
                                          <a href="index.php?page=forgot" class="small">Forgot Password?</a>
                                       </div>
                                    </div>
                                    <div class="col-12">
                                       <div class="d-grid"><button class="btn btn-primary" type="submit" name="login_btn">Login to account</button></div>
                                    </div>
                                 </div>
                              </form>
                              <div class="my-3 text-center">
                                 
                              </div>
                              <div class="row g-2">
                                 
                              </div>
                              <div class="text-center mt-4">
                                 <p class="small">Don't have an account? <a href="index.php?page=register">Register</a></p>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-7">
                           <div class="card-body bg-info is-theme has-mask has-mask-1 h-100 d-flex flex-column justify-content-end">
                              <div class="mask mask-1"></div>
                              <div class="brand-logo">
                                 <a href="#" class="logo-link">
                                    <div class="logo-wrap">
                                        <img src="img/LOGO_JPB_PNG.webp" width="300" height="113">
                                    </div>
                                 </a>
                              </div>
                              <div class="row">
                                 <div class="col-sm-11">
                                    <div class="mt-4">
                                       <div class="h1 title mb-3">Welcome back to UCUA</div>
                                       <p>UCUA campaigns are intensified to encourage employees to identify hazards, intervene and report any unsafe act and unsafe conditions.</p>
                                    </div>
                                 </div>
                              </div>
                              <div class="mt-5">
                                 <a href="index.php?page=resources" class="small mt-2 link-light"><i class="fa-solid fa-arrow-left"></i> Back to landing page</a>
                              </div>
                           </div>   
                        </div>
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script src="js/bundle.js"></script>
      <script src="js/scripts.js"></script>
      <script>
      function blockSpecialChar(e){
        var k;
        document.all ? k = e.keyCode : k = e.which;
        return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
      }
      </script>
   </body>
</html>