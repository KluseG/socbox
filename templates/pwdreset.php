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
    <style>
      .form-control {
        border-top: 0;
        border-left: 0;
        border-right: 0;
        border-bottom: 2px solid #000;
        border-radius: 0;
        text-align: center;
        font-size: 32px;
        color: #000;
      }
      .big {
        font-size: 54px;
      }
      button {
        margin-top: 15px;
      }
    </style>
  </head>
  <body>
    <div class="col top-col">
      <div class="d-flex top-bar justify-content-start">
        <span class="logo">SocBox</span>
      </div>
    </div>
    <div class="container-fluid d-flex justify-content-center align-items-center">
      <div class="activation-wrapper text-center">
        <h1>What's your email address?</h1>
        <input type="email" class="form-control" id="emailAddress">
        <button class="btn btn-primary" onclick="checkCode()">Send</button>
      </div>
      <div class="error text-center" style="display:none">
        <h1 class="text-red big">&#10007;</h1>
        <p>We can't find your account. Would you like to try again?</p>
      </div>
      <div class="success text-center" style="display:none">
        <h1 class="text-green big">&#10003;</h1>
        <p>Password reset link was sent! Check your inbox.</p>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS --><script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

    <script type="text/javascript">
      function checkCode() {
        $.post('/password/send', {'email': $('#emailAddress').val()}, function(res) {
          $('.activation-wrapper').fadeOut(200);
          console.log(res);
          if (res == 'true') {
            $('.success').delay(200).fadeIn(500);
          }
          else {
            $('.error').delay(200).fadeIn(500).delay(2000).fadeOut(500);
            $('.activation-wrapper').delay(3200).fadeIn(200);
          }
        });
      }
    </script>
  </body>
</html>
