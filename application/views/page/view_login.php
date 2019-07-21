<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if ($this->session->userdata('level')) {
  redirect('home');
}
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Schedule - Auth</title>
  <link rel="icon" href="<?=base_url();?>assets/img/favicon.png" type="image/x-icon">
  <link href="<?=base_url();?>assets/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?=base_url();?>assets/css/auth.css" rel="stylesheet">

</head>

<body>

<div class="pen-title">
  <h1>Penjadwalan</h1>
  <span>SMA N 2 Sleman</span>
</div>

<!-- Form Module-->
<div class="module form-module">
<div></div>
  <div class="form">
    <div class="alert alert-warning" <?=$display?>>
      <?=$notif_message?>
    </div>
    <h2>Masuk dengan akun anda</h2><hr>
    <form method="post" action="<?=base_url('auth/cek_login');?>" role="login">
      <input class="input-md" type="text" placeholder="Username" name="username" required="required" autofocus/>
      <input class="input-md" type="password" placeholder="Password" name="password" required="required"/>
      <button type="submit">Masuk</button>
    </form>
  </div>
  <div class="cta"><a href="#"></a></div>
</div>
<!-- Form Module-->

  <script src="<?=base_url();?>assets/js/jquery.min.js"></script>
  <script src="<?=base_url();?>assets/js/auth.js"></script>

</body>
</html>
