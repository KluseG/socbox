var day, month, year, passwordValid = false, ageValid = false, emailValid = false, usernameValid = false;

$('#registerEmail').on('change', function(){
  var that = this;
  $('#registerEmailInvalid').remove();

  if ($(that).val().length > 4 && $(that).val().indexOf('@') !== -1 && $(that).val().indexOf('.', $(that).val().indexOf('@')) > $(that).val().indexOf('@')) {
    $.post('/register/check', {
      'check': 'email',
      'value': $('#registerEmail').val()
    })
    .done(function(res) {
      if (res == 'false') {
        $(that).removeClass('invalid');
        $('#registerEmailInvalid').remove();
        emailValid = true;
      }
      else {
        $(that).addClass('invalid');
        $('<h6 class="text-red" id="registerEmailInvalid" style="margin-top: 15px; margin-bottom: 0;">This email address is already in use</h6>').insertAfter(that);
      }
    });
  }
  else {
    $('<h6 class="text-red" id="registerEmailInvalid" style="margin-top: 15px; margin-bottom: 0;">Please, provide real email address</h6>').insertAfter(that);
  }
});

$('#registerUsername').on('change', function(){
  var that = this;
  $('#registerUsernameInvalid').remove();

  if ($(that).val().length > 4) {
    $.post('/register/check', {
      'check': 'username',
      'value': $('#registerUsername').val()
    })
    .done(function(res) {
      if (res == 'false') {
        $(that).removeClass('invalid');
        $('#registerUsernameInvalid').remove();
        usernameValid = true;
      }
      else {
        $(that).addClass('invalid');
        $('<h6 class="text-red" id="registerUsernameInvalid" style="margin-top: 15px; margin-bottom: 0;">This username is already in use</h6>').insertAfter(that);
      }
    });
  }
  else {
    $('<h6 class="text-red" id="registerUsernameInvalid" style="margin-top: 15px; margin-bottom: 0;">Please, provide username that is at least 5 characters long</h6>').insertAfter(that);
  }
});

$('#registerPassword').on('input', function() {
  var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
  if ($(this).val().match(passw))   {
    $(this).addClass('valid');
    $('#registerPasswordValid').removeClass('text-red');
    $('#registerPasswordValid').addClass('text-green');
    $('#registerPasswordValid > span')[0].innerHTML = 'good';
    passwordValid = true;
  }
  else  {
    $(this).removeClass('valid');
    $('#registerPasswordValid').removeClass('text-green');
    $('#registerPasswordValid').addClass('text-red');
    $('#registerPasswordValid > span')[0].innerHTML = 'poor';
    passwordValid = false;
  }
});

$('.date-control').on('input', function(){
  day = parseInt($('#day').val());
  month = parseInt($('#month').val());
  year = parseInt($('#year').val());

  if (day &&
      month &&
      year > 1900) {
      var date = new Date() - new Date(year, month, day);
      var yo = Math.floor(date / 1000 / 60 / 60 / 24 / 365);
      $('#yo').html('You are: ' + yo + ' years old');
      if (yo >= 13) {
        $('#registerDateValid').removeClass('text-red').addClass('text-green');
        $('#registerDateValid span').css({display: 'none'});
        ageValid = true;
      }
      else {
          $('#registerDateValid').removeClass('text-green').addClass('text-red');
          $('#registerDateValid span').css({display: 'inline'});
          ageValid = false;
      }
   }
   else {
     $('#yo').innerHTML = 'How old are you?';
     $('#registerDateValid').removeClass('text-green').addClass('text-red');
     $('#registerDateValid span').css({display: 'inline'});
     ageValid = false;
   }
});

$('#personalSubmit').on('click', function(e){
  e.preventDefault();

  var data = {
    birth: day+'-'+month+'-'+year
  };

  if (ageValid &&
      passwordValid &&
      emailValid &&
      usernameValid) {
        $('#registerDate').val(data.birth);
        $('#registerForm').attr('action', '/register');
        $('#registerForm').submit();
      }
});
