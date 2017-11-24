$('#newAccount').on('click', function(){
  $('.login-box').fadeOut(200);

  setTimeout(function(){
    $('.container-fluid').removeClass('d-flex');
  }, 200);

  $('.register-box').delay(300).fadeIn(200);
});

$('#goBack').on('click', function(){
  $('.register-box').fadeOut(200);

  setTimeout(function(){
    $('.container-fluid').addClass('d-flex');
  }, 200);

  $('.login-box').delay(300).fadeIn(200);
});
