<?php

//index.php

require 'config.php';

$facebook_output = '';

$facebook_helper = $facebook->getRedirectLoginHelper();

/*jika terdapat request get 'code', berarti user berhasil login
menggunakan facebook*/
if(isset($_GET['code'])){
  // cek apakahah sudah ada session[user_token] 
  if(isset($_SESSION['access_token'])){
    $access_token = $_SESSION['access_token'];
  
  // jika belum ada maka 
  }else{
    // get access token
    $access_token = $facebook_helper->getAccessToken();
    // masukkan ke session
    $_SESSION['access_token'] = $access_token;
    // set default access token
    $facebook->setDefaultAccessToken($_SESSION['access_token']);

    /*Access token digunakan untuk mengambil data2 dari user yang login, contohnya
    nama, email, profile picture, dll*/
  }

  $_SESSION['user_id'] = '';
  $_SESSION['user_name'] = '';
  $_SESSION['user_email_address'] = '';
  $_SESSION['user_image'] = '';

  /*ambil data user, disini kita hanya mengambil name, dan email berserta id yg
  sudah otomatis terambil, id digunakan untuk mengambil profile picture dari user yg login
  menggunakan facebook*/
  $graph_response = $facebook->get("/me?fields=name,email", $access_token);
  $facebook_user_info = $graph_response->getGraphUser();

  // ambil data2 user, dan letakkan di session
  if(!empty($facebook_user_info['id'])){
    $_SESSION['user_image'] = 'http://graph.facebook.com/'.$facebook_user_info['id'].'/picture';
  }

  if(!empty($facebook_user_info['name'])){
    $_SESSION['user_name'] = $facebook_user_info['name'];
  }

  if(!empty($facebook_user_info['email'])){
    $_SESSION['user_email_address'] = $facebook_user_info['email'];
  }

}else{
// jika tidak ada request method get['code'] brrti user belum login, maka get login url
  $facebook_permissions = ['email']; // Optional permissions
  $facebook_login_url = $facebook_helper->getLoginUrl('http://localhost/php/fb-login2/', $facebook_permissions);
  // Render Facebook login button
  $facebook_login_url = '<div align="center"><a href="'.$facebook_login_url.'"><img src="img/login.png" class="img-responsive"/></a></div>';
  }



  ?>
  <html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PHP Login using Google Account</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />

  </head>
  <body>
    <div class="container">
     <br />
     <h2 align="center">PHP Login using Google Account</h2>
     <br />
     <div class="panel panel-default">
      <?php if(isset($facebook_login_url)): ?>
        <?= $facebook_login_url; ?>
      <?php else: ?>
       <div class="panel-heading">Welcome User</div><div class="panel-body">
       <img src="<?= $_SESSION['user_image']; ?>" class="img-circle text-center" />
       <h3><b>Name :</b> <?= $_SESSION['user_name']; ?></h3>
       <h3><b>Email :</b> <?= $_SESSION['user_email_address']; ?></h3>
       <h3><a href="logout.php">Logout</h3></div>
     <?php endif; ?>
   </div>
 </div>
</body>
</html>