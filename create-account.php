<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8" />
 
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
 
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
 
  <title>EventsWave</title>
 
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />

  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-login-form.min.css" />

</head>

<body>

  <section class="vh-100" style="background-image: url('assets/images/login_request/cover.png');">
    
    <div class="container py-5 h-100">

      <div class="row d-flex justify-content-center align-items-center h-100">

        <div class="col col-xl-10">

          <div class="card" style="border-radius: 1rem;">

            <div class="row g-0">

              <div class="col-md-6 col-lg-5 d-none d-md-block">
                
                <img src="assets/images/login_request/signup_img.jpg"alt="login form"class="img-fluid" style="border-radius: 1rem 0 0 1rem;"/>

              </div>

              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                
                <div class="card-body p-4 p-lg-5 text-black">

                  <form method="post" id="signup_form" action="signup_process.php">

                    <div class="d-flex justify-content-center">
                      <img class="mb-4" src="assets/images/login_request/small_logo.png" height="45">
                    </div>

                    <h6 class="mt-2 mb-3 text-center text-muted"><b>Join With Us</b></h6>

                    <?php if(isset($_GET['error_message'])){ ?>
                      <p class="text-center alert alert-danger"><?php echo $_GET['error_message'];?></p>
                    <?php }?>

                    <?php if(isset($_GET['sucess_message'])){ ?>
                      <p class="text-center alert alert-success"><?php echo $_GET['sucess_message'];?></p>
                    <?php }?>

                    <!-- Email -->
                    <div class="form-outline mb-3">
                      <input type="email" class="form-control form-control-lg" name="email" required />
                      <label class="form-label">Email address</label>
                    </div>

                    <!-- Password -->
                    <div class="form-outline mb-3">
                      <input type="password" class="form-control form-control-lg" name="password" required />
                      <label class="form-label">Password</label>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-outline mb-3">
                      <input type="password" class="form-control form-control-lg" name="confirm_password" required />
                      <label class="form-label">Confirm Password</label>
                    </div>

                    <div class="pt-1 mb-3">
                      <button class="btn btn-dark btn-lg btn-block w-100" type="submit" name="signup_btn">
                        SIGN UP
                      </button>
                    </div>

                    <p class="text-center">
                      Have an account? <a href="login.php">Sign In</a>
                    </p>

                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script type="text/javascript" src="assets/js/mdb.min.js"></script>

</body>

</html>