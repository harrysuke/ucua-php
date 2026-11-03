<?php 
include('testFunctions.php');

function funDepartment(){
    global $db;
    $option = '';
    $sql1 = "SELECT * FROM dept ORDER BY deptName";
    $result1 = $db->query($sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $dept = $row1['dept'];
        $deptName = $row1['deptName'];
        $option .= "<option value='$dept'>$deptName ($dept)</option>";
    }
    return $option;
}

function funLoggedUser($staffid){
   global $db;
   $sql = "SELECT *, userroles.rolename FROM user LEFT JOIN userroles ON userroles.id = user.AccessLevel WHERE staffId ='".$staffid."' LIMIT 1";
   $result = mysqli_query($db, $sql);
   $row = mysqli_fetch_array($result);
   $_SESSION['user'] = $row;
   echo '<meta http-equiv="refresh" content="0; URL=default.php?page=dashboard" />';
	exit;
}

if (isset($_POST['register_btn'])) {
	$staffId = $_POST['staffId'];
	$name = $_POST['name'];
	$password1 = md5($_POST['password1']);
	$password2 = md5($_POST['password2']);
	$email = $_POST['email'];
	$phoneno = $_POST['phoneno'];
	$Department = $_POST['Department'];
	$designation = $_POST['designation'];
	$AccessLevel = $_POST['AccessLevel'];
	$status = $_POST['status'];

	$email_query = "SELECT * FROM user WHERE Email = '$email' ";
	$email_query_run = mysqli_query($db, $email_query);

	if (mysqli_num_rows($email_query_run)>0){
		array_push($errors, "Email has been registered. Please use another email");
		echo"<script>alert('$staffId Email has been registered. Please use another email.')</script>";
		echo '<meta http-equiv="refresh" content="0; URL=registerMini.php" />';
		exit;
	}
	else {
	   if ($password1 == $password2) {
		   $createdate = date("Y-m-d h:i:s");
		   $query ="INSERT INTO `user`(`staffId`, `Password`, `Password1`, `Name`, `PhoneNo`, `Department`, `Designation`, `AccessLevel`, `Email`, `createdate`) VALUES ('$staffId','$password1', '$password2','$name', '$phoneno','$Department','$designation', '$AccessLevel','$email', '$createdate')";
		   $query_run= mysqli_query($db, $query);

         //echo $query;

		      if ($query_run){
               array_push($errors, "Your account has been succesfully created! Please Sign in");
		         echo"<script>alert('$staffId successfully created! ')</script>";
               funLoggedUser($staffId);
		         //echo '<meta http-equiv="refresh" content="0; URL=default.php?page=dashboard" />';
		         //exit;
		      } 
		      else
		      {
               array_push($errors, "Registration Failed");
               echo"<script>alert('$staffId Registration Failed ')</script>";
		         echo '<meta http-equiv="refresh" content="0; URL=default.php?page=registerMini" />';
		         exit;
	         }
      } else {
         array_push($errors, "Password and Confirmed Password are not matched");
         echo"<script>alert('$staffId Password and Confirmed Password are not matched ')</script>";
	      echo '<meta http-equiv="refresh" content="0; URL=default.php?page=registerMini" />';
	      exit;
	   }
   }
}
?>
<!DOCTYPE html>
<!-- saved from url=(0059)https://html.nioboard.themenio.com/auths/auth-register.html -->
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="author" content="Softnio">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="UCUA campaigns are intensified to encourage employees to identify hazards, intervene and report any unsafe act and unsafe conditions.">
      <title>Auth Register - UCUA</title>
      <link rel="shortcut icon" href="http://apps.johorport.com.my/img/Capture.png">
      <link rel="icon" type="image/png" sizes="16x16" href="img/Capture.png">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <!-- Icons Css -->
      <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
      <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@700&family=Roboto&display=swap" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                                    <h3 class="nk-block-title mb-1">Create New Account</h3>
                                    <p class="small">Use your remail email continue with UCUA (it's free)! </p>
                                    <p><?php echo display_error(); ?></p>
                                 </div>
                              </div>
                              <form action="default.php?page=registerMini" class="signin-form" method="POST">
                                 <div class="row gy-3">
                                    <div class="col-12">
                                       <div class="form-group">
                                          <label for="username" class="form-label">Staff ID</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="staffId" name="staffId" placeholder="Enter staff ID" value="<?php echo $_GET['staffid'];?>">
                                            </div>
                                       </div>
                                    </div>
                                    <div class="col-12">
                                       <div class="form-group">
                                          <label for="email" class="form-label">Staff Name</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                            </div>
                                       </div>
                                    </div>
                                    
                                    <div class="col-6">
                                       <div class="form-group">
                                          <label for="password" class="form-label">Email</label>
                                            <div class="form-control-wrap">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                                            </div>
                                       </div>
                                    </div>
                                    <div class="col-6">
                                       <div class="form-group">
                                          <label for="password" class="form-label">Phone Number</label>
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" id="phoneno" name="phoneno" placeholder="Enter phone number">
                                            </div>
                                       </div>
                                    </div>
                                    <div class="col-12">
                                       <div class="form-group">
                                          <label for="password" class="form-label">Department</label>
                                            <div class="form-control-wrap">
                                                <select id="Department" name="Department" class="form-control js-select" data-search="true" data-sort="true" required>
                                                    <?php echo funDepartment();?>
                                                </select>
                                            </div>
                                       </div>
                                    </div>
                                    
                                    
                                    <div class="col-12">
                                       <div class="d-grid">
                                          <button type="submit" name="register_btn" class="btn btn-primary" type="submit">Next &nbsp; <i class="fa-solid fa-angle-right"></i></button>
                                       </div>
                                    </div>
                                 </div>
                                 <input type="hidden"  name="AccessLevel" class="form-control" value="1" readonly>
                                 <input type="hidden"  name="status" class="form-control" value="1" readonly>
                                 <input type="hidden" class="form-control" id="password1" name="password1" value="qwe!@#123">
                                 <input type="hidden" class="form-control" id="password2" name="password2" value="qwe!@#123">
                                 <input type="hidden" class="form-control" id="designation" name="designation" value="User">
                              </form>
                              <div class="my-3 text-center">
                                 
                              </div>
                              <div class="row g-2">
                                 
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
                                 <a href="default.php?page=lockScreen" class="small mt-2 link-light"><i class="fa-solid fa-arrow-left"></i> Back to lock screen</a>
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
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script>
        $(document).ready(function() {
            $('.js-select').select2();
        });
    </script>
   </body>
</html>