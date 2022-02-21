<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="<?php echo base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet" crossorigin="anonymous"/>
<script src="<?php echo base_url('public/js/bootstrap.min.js'); ?>" crossorigin="anonymous"></script>
<link href="<?php echo base_url('public/fontawesome/css/all.css'); ?>" rel="stylesheet" crossorigin="anonymous"/>
<script src="<?php echo base_url('public/fontawesome/js/all.js'); ?>" crossorigin="anonymous"></script>
<title>தமிழ் Coders</title>
<style type="text/css">
  @media screen and (max-width: 600px) {
  .navbar a {
    float: none;
    width: 100%;
  }
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-xl navbar-dark bg-dark" style="position:fixed; top:0%; width: 100%; z-index:100;">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?php echo base_url('index.php'); ?>">முகப்பு</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('index.php/login'); ?>">உள்நுழைய</a>
        </li>
      </ul>
    </div>
  </div>
</nav>