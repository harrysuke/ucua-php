<?php
include('testFunctions.php');

function funDepartment()
{
   global $db;
   $option = '';
   $sql1 = "SELECT dept, deptName FROM dept WHERE isdelete=0 ORDER BY deptName";
   $result1 = $db->query($sql1);
   while ($row1 = mysqli_fetch_array($result1)) {
      $option .= '<option value="' . $row1['dept'] . '">' . $row1['deptName'] . ' (' . $row1['dept'] . ')</option>';
   }
   return $option;
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

   $email_query = "SELECT * FROM user WHERE Email = ? ";
   $stmt = $db->prepare($email_query);
   $stmt->bind_param("s", $email);
   $stmt->execute();
   $email_query_run = $stmt->get_result();
   $row = $email_query_run->fetch_assoc();
   if ($row) {
      array_push($errors, "Email has been registered. Please use another email");
      echo "<script>alert('$staffId Email has been registered. Please use another email.')</script>";
      echo '<meta http-equiv="refresh" content="0; URL=default.php?page=register" />';
      exit;
   } else {
      if ($password1 != $password2) {
         array_push($errors, "Password and Confirmed Password are not matched");
         echo "<script>alert('$staffId Password and Confirmed Password are not matched ')</script>";
         echo '<meta http-equiv="refresh" content="0; URL=default.php?page=register" />';
         exit;
      }
      $createdate = date("Y-m-d h:i:s");
      $query = "INSERT INTO `user`(`staffId`, `Password`, `Password1`, `Name`, `PhoneNo`, `Department`, `Designation`, `AccessLevel`, `Email`, `createdate`, `Status`, `createby`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
      $stmt = $db->prepare($query);
      $stmt->bind_param("ssssssssssii", $staffId, $password1, $password2, $name, $phoneno, $Department, $designation, $AccessLevel, $email, $createdate, 3, $staffId);
      if ($stmt->execute()) {
         array_push($errors, "Your account has been succesfully created! Please Sign in");
         echo "<script>alert('$staffId successfully created! ')</script>";
         echo '<meta http-equiv="refresh" content="0; URL=default.php?page=register" />';
         exit;
      } else {
         array_push($errors, "Registration Failed");
         echo "<script>alert('$staffId Registration Failed ')</script>";
         echo '<meta http-equiv="refresh" content="0; URL=default.php?page=register" />';
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
   <!-- Select2 css -->
   <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
   <!-- choices css -->
   <link href="css/choices.min.css" rel="stylesheet" type="text/css" />
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
                                 <h3 class="nk-block-title mb-1">Hi, I’m Ucux! 👋</h3>
                                 <p class="small">Tell me about yourself.</p>
                                 <p><?php echo display_error(); ?></p>
                              </div>
                           </div>
                           <form name="form1" action="default.php?page=register" class="signin-form" method="POST" onsubmit="return CheckPassword()">
                              <div class="row gy-3">
                                 <div class="col-12">
                                    <div class="form-group">
                                       <label for="username" class="form-label">Staff ID</label>
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" id="staffId" name="staffId" placeholder="Enter staff ID" onkeypress="return blockSpecialChar(event)" required>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-12">
                                    <div class="form-group">
                                       <label for="email" class="form-label">Staff Name</label>
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" onkeypress="return onlyAlphabets(event,this);" required>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Password</label>
                                       <div class="form-control-wrap">
                                          <input type="password" class="form-control" id="password1" name="password1" placeholder="Enter password" required>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Confirm Password</label>
                                       <div class="form-control-wrap">
                                          <input type="password" class="form-control" id="password2" name="password2" placeholder="Confirm password" required>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Email</label>
                                       <div class="form-control-wrap">
                                          <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-6">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Phone Number</label>
                                       <div class="form-control-wrap">
                                          <input type="tel" class="form-control" id="phoneno" name="phoneno" placeholder="Enter phone number" onkeypress="return isNumber(event)" required>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-12">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Department</label>
                                       <div class="form-control-wrap">
                                          <select id="Department" name="Department" placeholder="This is a placeholder" class="form-control select2" data-search="true" data-sort="false" required>
                                             <?php echo funDepartment(); ?>
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-12">
                                    <div class="form-group">
                                       <label for="password" class="form-label">Designation</label>
                                       <div class="form-control-wrap">
                                          <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter designation" required>
                                       </div>
                                    </div>
                                 </div>
                                 <!--div class="col-12">
                                       <div class="form-check form-check-sm"><input class="form-check-input" type="checkbox" value="" id="iAgree"><label class="form-check-label" for="iAgree"> I agree to <a href="#">privacy policy</a> &amp; <a href="#">terms</a></label></div>
                                    </div-->
                                 <div class="col-12">
                                    <input type="hidden" name="AccessLevel" class="form-control" value="1">
                                    <input type="hidden" name="status" class="form-control" value="3">
                                    <div class="d-grid">
                                       <button type="submit" name="register_btn" class="btn btn-primary" type="submit">Sign up</button>
                                    </div>
                                 </div>
                              </div>
                           </form>
                           <div class="my-3 text-center">

                           </div>
                           <div class="row g-2">

                           </div>
                           <div class="text-center mt-4">
                              <p class="small">Already have an account? <a href="index.php?page=login">Sign in</a></p>
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
                                    <div class="h1 title mb-3">Welcome to UCUX</div>
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
   <!-- Select2 js -->
   <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   <!-- choices js -->
   <script src="js/choices.min.js"></script>
   <script>
      $(document).ready(function() {
         $('.select2').select2();
      });

      function isNumber(evt) {
         try {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
               return false;
            }
            return true;
         } catch (err) {
            console.error(err.Description);
         }
      }

      function onlyAlphabets(e, t) {
         try {
            if (window.event) {
               var charCode = window.event.keyCode;
            } else if (e) {
               var charCode = e.which;
            } else {
               return true;
            }
            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32)
               return true;
            else
               return false;
         } catch (err) {
            console.error(err.Description);
         }
      }

      function blockSpecialChar(e) {
         try {
            var k;
            document.all ? k = e.keyCode : k = e.which;
            return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
         } catch (err) {
            console.error(err.Description);
         }
      }

      function CheckPassword() {
         var inputtxt = document.form1.password1;
         var decimal = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?=.*[!@#\$%\^&\*]).{8,12}$/;
         if (inputtxt.value.match(decimal)) {
            console.log('Password accepted');
            return true;
         } else {
            alert('Password should be between 8 to 12 characters which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character');
            return false;
         }
      }
   </script>
</body>

</html>