<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>(3) SocBox</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
  </head>
  <body>
    <div class="col top-col">
      <div class="top-bar">
        <div class="row">
          <div class="col d-lg-flex col-lg-4" onclick="window.location.href='/'" style="cursor: pointer">
            <span class="logo">SocBox</span>
          </div>
          <div class="col d-lg-flex col-lg-4 justify-content-center">
            <form>
              <div class="input-group">
                <input type="text" class="form-control" id="search" placeholder="Search SocBox">
                <div class="input-group-addon">
                  <button type="submit" class="btn"><i class="material-icons">search</i></button>
                </div>
              </div>
            </form>
          </div>
          <div class="col col-lg-4 d-lg-flex justify-content-end profile-ddown">
            <div class="top-notifications">
              <div class="dropdown">
                <a href="#" class="dropdown-toggle has-notify" role="button" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons">notifications_active</i><span class="notifications-count">3</span></a>

                <div class="dropdown-menu" aria-labelledby="notificationDropdown">
                  <a class="dropdown-item" href="#">
                    <div class="media">
                      <img class="d-flex align-self-start mr-3" src="https://sslb.ulximg.com/image/405x405/artist/1346353449_4159240d68a922ee4ecdfd8e85d179c6.jpg/e96a72d63f272127d0b6d70c76fd3f61/1346353449_eminem.jpg" alt="Generic placeholder image">
                      <div class="media-body">
                        <h6 class="mt-0">Eminem <small class="nickname">@iameminem</small> <span class="date-ago text-right">15m</span></h6>
                        <p>is now following you!</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="#">
                    <div class="media">
                      <img class="d-flex align-self-start mr-3" src="https://media.nngroup.com/media/people/photos/gibbons_sarah-800px.jpg.400x400_q95_autocrop_crop_upscale.jpg" alt="Generic placeholder image">
                      <div class="media-body">
                        <h6 class="mt-0">Sarah Anderson <small class="nickname">@saraha</small> <span class="date-ago text-right">12h</span></h6>
                        <p>has recated to your shout!</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="#">
                    <div class="media">
                      <img class="d-flex align-self-start mr-3" src="data:image/jpeg;base64,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" alt="Generic placeholder image">
                      <div class="media-body">
                        <h6 class="mt-0">John Doe <small class="nickname">@jjjohnD</small> <span class="date-ago text-right">1w</span></h6>
                        <p>mentioned you!</p>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="dropdown">
              <div class="media">
                <img class="d-flex align-self-center mr-3" src="/assets/img/{% avatar %}" alt="{% firstName %}'s profile pic">
                <div class="media-body">
                    <a href="#" class="dropdown-toggle" role="button" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{% firstName %} <i class="material-icons">keyboard_arrow_down</i></a>

                    <div class="dropdown-menu" aria-labelledby="profileDropdown">
                      <a class="dropdown-item" href="/user/settings">Settings <i class="material-icons text-right">settings</i></a>
                      <a class="dropdown-item" href="/logout">Sign out <i class="material-icons text-right">arrow_forward</i></a>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid" id="mainWrapper">
      <div class="row">
		      <div class="col col-lg-2 shortcuts-col">
            <div class="col-12 profile d-flex justify-content-center" style="cursor: pointer">
              <div class="user-big">
                <img class="img-fluid" src="/assets/img/{% avatar %}" alt="Adrian's profile pic" style="width: 100%; height: auto;">
                <div class="user-big-name text-center mt-3">
                  <h4>{% firstName %} {% lastName %}</h4>
                  <?php if (isset($username)) : ?>
                    <form class="form" action="/user/follow/{% username %}" method="get">
                      <?php if (!$following) : ?>
                        <button type="submit" role="button" class="btn btn-primary">Follow</button>
                      <?php else : ?>
                        <button type="submit" role="button" class="btn btn-primary">Unfollow</button>
                      <?php endif; ?>
                    </form>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col col-lg-4 feed-col">
            <?php foreach($posts as $post) : ?>
            <div class="col-12 feed-box">
               <div class="feed-box-group">
                 <div class="feed-box-author">
                   <div class="media">
                     <img class="d-flex align-self-start mr-3" src="/assets/img/<?php echo $post['avatar'] ?>" alt="Generic placeholder image">
                     <div class="media-body">
                       <h6 class="mt-0"><?php echo $post['ufname'] ?> <?php echo $post['ulname'] ?> <small class="nickname" onclick="returnUrl(this)" data-href="/user/profile/<?php echo $post['uname'] ?>">@<?php echo $post['uname'] ?></small> <?php if ($post['canEdit']) : ?><a href="#" class="float-right"><i class="material-icons">mode_edit</i></a><?php endif; ?></h6>
                       <span class="date-ago text-right"><?php echo $post['created'] ?></span>
                     </div>
                   </div>
                 </div>
                 <div class="feed-box-toggler justify-content-center align-items-center">
                   <a href="#" class="close-comment"><i class="material-icons">close</i></a>
                 </div>
               </div>
               <div class="feed-box-group">
                 <div class="feed-box-content">
                   <p><?php echo $post['body'] ?></p>
                 </div>
                 <div class="feed-box-util">
                   <p><i class="material-icons">thumb_up</i> <small>0</small> | <i class="material-icons">thumb_down</i> <small>0</small></p>
                   <p class="text-right">1 Comments</p>
                   <hr>
                   <a href="#" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="<a href='#' class='rate-thumb-up'><i class='material-icons'>thumb_up</i></a> <small>or</small> <a href='#' class='rate-thumb-down'><i class='material-icons'>thumb_down</i></a>"><i class="material-icons">thumb_up</i> Rate</a> |
                   <a href="#" class="send-comment"><i class="material-icons">chat_bubble</i> Comment</a>
                 </div>
                 <div class="feed-box-comments">
                   <div class="comment-form-wrapper">
                     <form class="add-comment-form" id="commentForm">
                       <div class="form-group">
                         <textarea class="form-control" rows="1" placeholder="What would you like to say?"></textarea>
                         <a href="#" class="send-btn"><i class="material-icons">send</i></a>
                       </div>
                     </form>
                   </div>
                   <div class="comments">
                     <div class="comment">
                       <div class="media">
                         <img class="d-flex align-self-start mr-3" src="https://media.nngroup.com/media/people/photos/gibbons_sarah-800px.jpg.400x400_q95_autocrop_crop_upscale.jpg" alt="Generic placeholder image">
                         <div class="media-body">
                           <h6 class="mt-0">Sarah Anderson <small class="nickname">@saraha</small> <span class="date-ago text-right">15m</span></h6>
                           <p>Mhm ;)</p>
                         </div>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
           </div>
         <?php endforeach; ?>
          </div>
      </div>
    </div>
    <footer>
      <hr>
      <p class="text-center">Copyright &copy; Adrian Kluska 2017</p>
    </footer>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="/assets/js/jquery.slim.min.js"></script>
    <script src="/assets/js/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
  </body>
</html>
