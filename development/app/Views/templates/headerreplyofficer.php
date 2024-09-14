<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style> body { margin: 100px;} .required::after { content: ' *'; color: red;}</style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <link rel="stylesheet" href="/Development/development/public/asstes/style1.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <!-- <script src="<?php echo base_url('Development/development/public/ckeditor/ckeditor.js'); ?>"></script> -->
   <!-- <script src="<?php echo base_url('Development/development/public/ckeditor5/build/ckeditor.js'); ?>"></script> -->
   <title>CPAS System</title>
</head>
<body>
  <?php
    $uri = service('uri');
    ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
  <a class="navbar-brand" href="#">CPAS</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <?php if(session()->get('isLoggedIn')): ?>
      <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link <?= ($uri->getSegment(1)) == 'dashboard' ? 'active' :null ?>" href=<?= base_url("/Development/development/public/AuditReplying/".session()->get('EmpNo'))?> >Dashboard</a>
      </li>
      <li class="nav-item <?= ($uri->getSegment(1)) == 'profile' ? 'active' :null ?>">
        <a class="nav-link" href="/Development/development/public/profile">Profile</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>

    <div class="text-right">
        <ul class="navbar-nav my-2 my-lg-0" >
          <li class="nav-item">
              <a class="nav-link" href="/Development/development/public/logout">Logout</a>
          </li>
          <li class="nav-item ">
              <a class="nav-link" href="<?= base_url('/Development/development/public/AuditReplyingOfficerDashboard/'.session()->get('EmpNo'));?>">Home</a>
          </li>
        </ul>
    </div>
    <?php else: ?>
      <ul class="navbar-nav mr-auto">
      <li class="nav-item <?= ($uri->getSegment(1)) == 'public' ? 'active' :null ?>">
        <a class="nav-link" href="/Development/development/public/">login</a>
      </li>
      <li class="nav-item <?= ($uri->getSegment(1)) == 'register' ? 'active' :null ?>">
        <a class="nav-link" href="/Development/development/public/register">Register</a>
      </li>
    </ul>
    <?php endif ?>
  </div>
  </div>
</nav>
<!-- <Div><?= session()->get('EmpNo') ?></Div> -->
