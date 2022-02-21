<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?php echo base_url('public/css/bootstrap.min.css'); ?>" rel="stylesheet" crossorigin="anonymous"/>
  <script src="<?php echo base_url('public/js/bootstrap.min.js'); ?>" crossorigin="anonymous"></script>
  <link href="<?php echo base_url('public/fontawesome/css/all.css'); ?>" rel="stylesheet" crossorigin="anonymous"/>
  <script src="<?php echo base_url('public/fontawesome/js/all.js'); ?>" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/css/globalStyle.css'); ?>"/>
  <script src="<?php echo base_url('public/js/jquery-3.6.0.js'); ?>" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
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
            <a class="nav-link active" aria-current="page" href="<?php echo base_url('index.php'); ?>"><i style="color:#ffffff" class="fas fa-home"></i>&nbsp;முகப்பு</a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSearch" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i style="color:#ffffff" class="fas fa-search"></i>&nbsp;தேடுக</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownSearch">
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/search/question'); ?>">கேள்வி</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/search/heading'); ?>">தலைப்பு</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/search/writer'); ?>">எழுத்தாளர்</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAddNew" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i style="color:#ffffff" class="far fa-plus-square"></i>&nbsp;சேர்க்க</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownAddNew">
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/addNew/toAddNewQuestion'); ?>">புதிய கேள்வி</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/addNew/toAddNewHeading'); ?>">புதிய தலைப்பு</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAboutYou" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i style="color:#ffffff" class="far fa-user-circle"></i>&nbsp;நீங்கள்</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownAboutYou">
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/profile/aboutYou'); ?>">உமது சுயவிபரம்</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/profile/yourQuestions'); ?>">உமது கேள்விகள்</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/profile/yourAnswers'); ?>">உமது பதில்கள்</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFavorite" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i style="color:#ffffff" class="far fa-heart"></i>&nbsp;பிடித்தவை</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownFavorite">
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/favorite/questions'); ?>">பிடித்த கேள்விகள்</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/favorite/answers'); ?>">பிடித்த பதில்கள்</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/favorite/writers'); ?>">பிடித்த நபர்கள்</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/favorite/headings'); ?>">பிடித்த தலைப்புகள்</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownFavorite" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i style="color:#ffffff" class="far fa-bell"></i>&nbsp;<span class="unseenNotifications" style="color:red"></span>&nbsp;அறிவிப்புகள்</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownFavorite">
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/notifications'); ?>"><span class="unseenNotifications" style="color:red"></span>&nbsp;உங்களுக்கானவை</a></li>
              <li><a class="dropdown-item" href="<?php echo base_url('index.php/notifications/settings'); ?>">அமைப்புகள்</a></li>
            </ul>
          </li>

          <li class="nav-item nav-link active text-danger" onclick="window.location.href='<?php echo base_url('index.php/logout'); ?>'">
            <i style="color:red" class="fas fa-power-off"></i>&nbsp;வெளியேற
          </li>

        </ul>
      </div>
    </div>
  </nav>
<script type="text/javascript">
  $(document).ready(function(){
    fetch("<?php echo base_url('index.php/notifications/unseennotificationscount'); ?>",{
              method:'GET',
              mode: 'no-cors',
              cache: 'no-cache'})
            .then(response => {
              if (response.status == 200) {
                return response.json();            
              }
              else {
                console.log(response);
              }
            })
            .then(data => {
              var notificationsCount=data.length;
              if (data.length>0) {
                $(".unseenNotifications").html(data.length);  
              }
            })
            .catch(() => {
              (console.log("Network connection error"));
            });
    
  });
</script>