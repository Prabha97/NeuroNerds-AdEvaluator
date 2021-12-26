<?php

include("config.php");
 
//========================================= UPLOAD USER SCORES =============================================================
if(isset($_POST['but_upload_user_score'])){
   
  if(!empty($_POST['user_id']) && !empty($_POST['user_name']) && !empty($_POST['movie_id']) && !empty($_POST['movie_name']) && !empty($_POST['attention_score']) && !empty($_POST['emotion_score']) && !empty($_POST['enjoyment_score'])){

    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $movie_id = $_POST['movie_id'];
    $movie_name = $_POST['movie_name'];
    $attention_score = $_POST['attention_score'];
    $emotion_score = $_POST['emotion_score'];
    $enjoyment_score = $_POST['enjoyment_score'];

    $r_min = 0;
    $r_max = 100;

    $t_min = 1;
    $t_max = 5;

    $m_tot = ($attention_score + $emotion_score + $enjoyment_score);

    $m = $m_tot/3;

    $final_score = (($m - $r_min) / ($r_max - $r_min)) * ($t_max - $t_min) + $t_min;

    $query = "INSERT INTO userscores(user_id,user_name,movie_id,movie_name,attention_score,emotion_score,enjoyment_score,final_score) VALUES('".$user_id."','".$user_name."','".$movie_id."','".$movie_name."','".$attention_score."','".$emotion_score."','".$enjoyment_score."','".$final_score."')";
    $query1 = "INSERT INTO ratings1(userId,movieId,rating) VALUES('".$user_id."','".$movie_id."','".$final_score."')";
               
    $run = mysqli_query($con,$query) or die(mysqli_error());
    $run1 = mysqli_query($con,$query1) or die(mysqli_error());

    if($run){
      echo "Form Submitted Successfully";
    }else{
      echo "Form not Submitted";
    }

  }else{
    echo "All Fields Requiered";
  }


   header('location: userScores.php');
   exit;
} 

//========================================= ADD USER =============================================================
if(isset($_POST['but_add_user'])){
   
  if(!empty($_POST['add_user_name'])){

    $add_user_name = $_POST['add_user_name'];

    $queryAddUser = "INSERT INTO users(username) VALUES('".$add_user_name."')";
               
    $run = mysqli_query($con,$queryAddUser) or die(mysqli_error());

    if($run){
      echo "Form Submitted Successfully";
    }else{
      echo "Form not Submitted";
    }

  }else{
    echo "All Fields Requiered";
  }


   header('location: userScores.php');
   exit;
} 



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    EEG Based Ad Evaluator
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="green" data-background-color="white" data-image="../assets/img/muse2-7.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo">
        <a class="simple-text logo-normal">
          <img src="../assets/img/logo.PNG" alt="logo" width="100" height="100">
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item ">
            <a class="nav-link" href="./Guide.html">
              <i class="material-icons">library_books</i>
              <p>Guide</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./uploadVideos.php">
              <i class="material-icons">file_upload</i>
              <p>Upload Video</p>
            </a>
          </li>
          <li class="nav-item  ">
            <a class="nav-link" href="./videos.php">
              <i class="material-icons">ondemand_video</i>
              <p>Videos</p>
            </a>
          </li>
          <li class="nav-item  ">
            <a class="nav-link" href="./dashboard.php">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item active ">
            <a class="nav-link" href="./Attention.php">
              <i class="material-icons">content_paste</i>
              <p>Analysis</p>
            </a>
          </li>
          
        </ul>
      </div>
    </div>

    <div class="main-panel">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success">
                  <h4 class="card-title">Add User</h4>
                  <p class="card-category">Add User Details</p>
                </div>
                <div class="card-body">
                
                  <form method="post" action="" enctype='multipart/form-data'>
                  
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">User Name</label>
                          <input type="text" name="add_user_name" class="form-control">
                        </div>
                      </div>
                    </div>
                    
                    <button style="background-color: #27ae60 " type="submit" name="but_add_user" value="Upload" class="btn btn-primary pull-right">Add User</button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
              <br>
              <div class="card">
                <div class="card-header card-header-success">
                  <h4 class="card-title">Enter User Score Details</h4>
                  <p class="card-category">Enter Attention, Emotion and Enjoyment Scores</p>
                </div>
                <div class="card-body">
                
                  <form method="post" action="" enctype='multipart/form-data'>
                  
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">User ID</label>
                          <input type="text" name="user_id" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">User Name</label>
                          <input type="text" name="user_name" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Movie ID</label>
                          <input type="text" name="movie_id" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Movie Name</label>
                          <input type="text" name="movie_name" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Attention Score</label>
                          <input type="text" name="attention_score" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Emotion Score</label>
                          <input type="text" name="emotion_score" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Enjoyment Score</label>
                          <input type="text" name="enjoyment_score" class="form-control">
                        </div>
                      </div>
                      <!--<input type="file" name="file">-->
                      <!--<input type='submit' value='Upload' name='but_upload'>-->
                    </div>
                    <!--<input type="file" name="file">-->
                    <!--<input type='submit' value='Upload' name='but_upload'>-->
                    <!--<input type='submit' value='Upload' name='but_upload'>-->
                    
                    <button style="background-color: #27ae60 " type="submit" name="but_upload_user_score" value="Upload" class="btn btn-primary pull-right">Upload User Scores</button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
              <br>
              <br>
              <div class="card">
                <div class="card-header card-header-success">
                  <h4 class="card-title ">Individual User Scores</h4>
                  <p class="card-category">Attention, Emotion & Enjoyment Scores for each users</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">

                  <table class="table">
                      <thead class=" text-primary">
                        <th>
                          User ID
                        </th>
                        <th>
                          User Name
                        </th>
                        <th>
                          Movie Name
                        </th>
                        <th>
                          Attention Score
                        </th>
                        <th>
                          Emotion Score
                        </th>
                        <th>
                          Enjoyment Score
                        </th>
                        <th>
                          Final Score
                        </th>
                      </thead>

                    <?php

                      $query1 = "SELECT * FROM userscores";
                      $query_run = mysqli_query($con,$query1);

                      if(!$query_run){
                        die ('Error..'.mysqli_error($con));
                      }

                        while ($row = mysqli_fetch_array($query_run)){
                          echo "<tr>";
                          echo "<td> <a href='recommendations.php?userId=" . $row['user_id'] . "' target='_blank' >" . $row['user_id'] . "</a></td>";
                          echo "<td>" . $row['user_name'] . "</td>";
                          echo "<td>" . $row['movie_name'] . "</td>";
                          echo "<td>" . $row['attention_score'] . "</td>";
                          echo "<td>" . $row['emotion_score'] . "</td>";
                          echo "<td>" . $row['enjoyment_score'] . "</td>";
                          echo "<td>" . $row['final_score'] . "</td>";
                          echo "</tr>";
                        }
                        
                    ?>
                  </table>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
      
    </div>
  </div>
  <div class="fixed-plugin">
    <div class="dropdown show-dropdown">
      <a href="#" data-toggle="dropdown">
        <i class="fa fa-cog fa-2x"> </i>
      </a>
      <ul class="dropdown-menu">
        <li class="header-title"> Sidebar Filters</li>
        <li class="adjustments-line">
          <a href="javascript:void(0)" class="switch-trigger active-color">
            <div class="badge-colors ml-auto mr-auto">
              <span class="badge filter badge-purple" data-color="purple"></span>
              <span class="badge filter badge-azure" data-color="azure"></span>
              <span class="badge filter badge-green" data-color="green"></span>
              <span class="badge filter badge-warning" data-color="orange"></span>
              <span class="badge filter badge-danger" data-color="danger"></span>
              <span class="badge filter badge-rose active" data-color="rose"></span>
            </div>
            <div class="clearfix"></div>
          </a>
        </li>
        <li class="header-title">Images</li>
        <li class="active">
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="../assets/img/sidebar-1.jpg" alt="">
          </a>
        </li>
        <li>
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="../assets/img/sidebar-2.jpg" alt="">
          </a>
        </li>
        <li>
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="../assets/img/sidebar-3.jpg" alt="">
          </a>
        </li>
        <li>
          <a class="img-holder switch-trigger" href="javascript:void(0)">
            <img src="../assets/img/sidebar-4.jpg" alt="">
          </a>
        </li>
        <li class="button-container">
          <a href="https://www.creative-tim.com/product/material-dashboard" target="_blank" class="btn btn-primary btn-block">Free Download</a>
        </li>
        <!-- <li class="header-title">Want more components?</li>
            <li class="button-container">
                <a href="https://www.creative-tim.com/product/material-dashboard-pro" target="_blank" class="btn btn-warning btn-block">
                  Get the pro version
                </a>
            </li> -->
        <li class="button-container">
          <a href="https://demos.creative-tim.com/material-dashboard/docs/2.1/getting-started/introduction.html" target="_blank" class="btn btn-default btn-block">
            View Documentation
          </a>
        </li>
        <li class="button-container github-star">
          <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star ntkme/github-buttons on GitHub">Star</a>
        </li>
        <li class="header-title">Thank you for 95 shares!</li>
        <li class="button-container text-center">
          <button id="twitter" class="btn btn-round btn-twitter"><i class="fa fa-twitter"></i> &middot; 45</button>
          <button id="facebook" class="btn btn-round btn-facebook"><i class="fa fa-facebook-f"></i> &middot; 50</button>
          <br>
          <br>
        </li>
      </ul>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="../assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="../assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="../assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="../assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="../assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="../assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="../assets/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="../assets/js/plugins/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="../assets/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="../assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="../assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="../assets/js/plugins/arrive.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chartist JS -->
  <script src="../assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="../assets/demo/demo.js"></script>
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
</body>

</html>
