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
        border: none;
        text-align: center;
        font-size: 48px;
        color: #000;
      }
      .big {
        font-size: 54px;
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
        <h1>Hi {% name %}!</h1>
        <p>We've sent you activation code. Can you use it now?</p>
        <input type="text" class="form-control" id="activationCode" placeholder="0000">
      </div>
      <div class="error text-center" style="display:none">
        <h1 class="text-red big">&#10007;</h1>
        <p>Code is not valid. Would you like to try again?</p>
      </div>
      <div class="success text-center" style="display:none">
        <h1 class="text-green big">&#10003;</h1>
        <p>Your account has been activated successfully. Have fun!</p>
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
      $('#activationCode').on('input', function() {
        var val = $(this).val();
        if (val.length > 4) {
          $(this).val(val.substring(0,4));
        }

        if (val.length >= 4 && !document.getElementsByTagName('button')[0]) {
          $('<button class="btn btn-primary" onclick="checkCode()">Check</button>').insertAfter($(this));
        }
        else if (val.length < 4) {
          $('button').remove();
        }
      });

      function checkCode() {
        $.post('/user/activate', {'code': $('#activationCode').val()}, function(res) {
          $('.activation-wrapper').fadeOut(200);
          console.log(res);
          if (res == 'true') {
            $('.success').delay(200).fadeIn(500);

            setTimeout(function() {
              window.location.reload();
            }, 3000);
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
