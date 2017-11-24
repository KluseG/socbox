<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SocBox | Sign in or sign up</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/index.css">
  </head>
  <body>
    <div class="col top-col">
      <div class="d-flex top-bar justify-content-start">
        <span class="logo">SocBox</span>
      </div>
    </div>
    <div class="container-fluid d-flex justify-content-center align-items-center">
      <div class="col-xl-3 col-lg-4 col-md-5 col-sm-12 login-box">
        <img src="/assets/img/user-avatar.jpg" alt="user-avater" class="img-fluid" id="default-img">
        <form class="pretty-form" action="#" method="POST" id="loginForm">
          <div class="form-group" id="email-group">
            <input type="text" class="form-control" id="email" name="email" pattern=".*\S.*" required>
            <label id="label1">Email</label>
          </div>
          <div class="form-group" id="password-group">
            <input type="password" class="form-control" id="password" name="password" pattern=".*\S.*" required>
            <label id="label2">Password</label>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Proceed</button>
          </div>
          <div class="form-group links">
            <a href="/password/forgot">Forgot password?</a>
            <a href="#" id="newAccount">Don't have an account?</a>
          </div>
        </form>
      </div>
      <div class="col-12 register-box clearfix">
        <div class="col-12 col-md-12 col-lg-12 col-xl-10 ml-xl-auto mr-xl-auto  register-form">
          <h1 class="display-4">Create an Account</h1>
          <h3 class="dimmed display-6">It's free, forever</h3>
          <form class="form" action="#" method="POST" id="registerForm">
            <div class="row">
              <div class="col">
                <input type="text" class="form-control" placeholder="Forename" name="registerForename" id="registerForename" required>
              </div>
              <div class="col">
                <input type="text" class="form-control" placeholder="Surname" name="registerSurname" id="registerSurname">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <input type="text" class="form-control" placeholder="Email address" name="registerEmail" id="registerEmail">
              </div>
              <div class="col">
                <input type="text" class="form-control" placeholder="Username" name="registerUsername" id="registerUsername">
              </div>
            </div>
            <div class="row">
              <div class="col">
                <input type="password" class="form-control" placeholder="Create password" name="registerPassword" id="registerPassword">
              </div>
              <div class="col">
                <h6 class="text-red" id="registerPasswordValid">Password strength is <span>poor</span></h6>
                <p class="dimmed">Make your password memorable and use a mix of numbers, uppercase characters and lower-case characters</p>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <h3 class="dimmed display-6">Birthday</h3>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <input type="number" class="form-control date-control" placeholder="Day" min="1" max="31" id="day"><input type="number" class="form-control date-control" placeholder="Month" min="1" max="12" id="month"><input type="number" class="form-control date-control" placeholder="Year" min="1900" max="2100" id="year"><span class="form-control date-control-addon" id="yo">How old are you?</span>
                <input type="hidden" name="registerDate" id="registerDate">
              </div>
              <div class="col">
                <h6 class="text-red" id="registerDateValid">You are <span>not</span> old enough to join</h6>
                <p class="dimmed">We cannot control all of the content, so you have to be mature enough to scroll through it securely</p>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <select class="form-control gender" size="" required name="registerGender" id="registerGender">
                  <option value="" class="dimmed" disabled selected hidden>Select a gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div class="col">
                <p class="dimmed">From the dropdown list adjacent, please select a specific gender. We are truly sorry if your gender is not currently listed.</p>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <p class="dimmed">Through registering an account with SocBox - through the use of Create Account button - you agree to <a href="#">Terms</a> and confirm that you have read our <a href="#">Data Policy</a>, including our <a href="#">Cookie Use Policy</a>.</p>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <div class="row buttons-row">
                  <div class="col">
                    <button type="button" name="button" id="personalSubmit" class="btn btn-primary">Create Account</button>
                  </div>
                  <div class="col">
                    <button type="button" name="button" id="brandSubmit" class="btn btn-primary btn-outline">Create Brand</button>
                  </div>
                </div>
              </div>
              <div class="col">
                <p class="dimmed">If you don't intend to use SocBox for personal reasons, rather for a brand or business, then press Create Brand.</p>
              </div>
            </div>
          </form>
          <a href="#" id="goBack">Go back.</a>
        </div>
      </div>
      <div class="spinner">
        <div class="cube1"></div>
        <div class="cube2"></div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS --><script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="/assets/js/registerControl.js"></script>
    <script src="/assets/js/indexBehaviorControl.js"></script>

    <script type="text/javascript">
      var step = 0;

      $('button').on('click', function(e){
        e.preventDefault();

        if (step == 0) {
          var value = $('#email-group > input').val();

          $.post('/login/check', {'email': value}, function(res) {
            console.log(res);
            if (res == 'false') {
              $('#label1').css({color: "#FF2A2F"});
              $('#email-group > input').val('');
              setTimeout(function(){
                $('#label1').css({color: "#242424"});
              }, 500);
            }
            else {
              $('#email-group').fadeOut('fast');
              $('#password-group').delay(200).fadeIn('slow');
              $('#default-img').attr('src', '/assets/img/' + res);
              $('#password-group input').focus();

              step++;
            }
          });
        }
        else if (step == 1) {
            $('.login-box').hide();
            $('.spinner').fadeIn('fast');

            $('#loginForm').attr('action', '/login');
            $('#loginForm').submit();
        }
      });
    </script>
  </body>
</html>
