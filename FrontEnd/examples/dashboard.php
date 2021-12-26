<?php
include("config.php");

//========================================= ADD USER RECORDS =============================================================
/*if(isset($_POST['but_add_user'])){
   
  if(!empty($_POST['movie_name'])){

    $add_user_name = $_POST['movie_name'];
    
    $queryAddUser = "INSERT INTO userrecords(username,movie_name,csvFilePath) VALUES('".$username."','".$add_user_name."','".$csvFile."')";

    $queryAddUser12 = "INSERT INTO atten_emo_enjoy(username,movie_name) VALUES('".$username."','".$add_user_name."')";

    //$queryAddUser = "INSERT INTO userrecords1(username,movie_name,csvFilePath) VALUES('".$username."','".$add_user_name."','". mysqli_real_escape_string (file_get_contents ('$csvFile'), $con) ."')";
    
    //==============================================
    //$result = mysqli_query ($con, 'INSERT INTO table (data) VALUES (\'' . mysqli_real_escape_string (file_get_contents ('/path/to/the/file/to/store.pdf'), $con) . '\');');
    //==============================================

    $run = mysqli_query($con,$queryAddUser) or die(mysqli_error($con));

    $run2 = mysqli_query($con,$queryAddUser12) or die(mysqli_error($con));

    if($run){
      echo "Form Submitted Successfully";
    }else{
      echo "Form not Submitted";
    }

  }else{
    echo "All Fields Requiered";
  }


   header('location: viewResults.php');
   exit;
} */

//================================================================================================================
if(isset($_GET['but_add_user'])){
   
  if(!empty($_GET['movie_name'])){

    $add_user_name = $_GET['movie_name'];

    //echo "'".$add_user_name."'";

    //echo "Movie Name ". $add_user_name. "<br />";

    $queryAddUser = "INSERT INTO dashboard(movie_name_dash) VALUES('".$add_user_name."')";

    $run = mysqli_query($con,$queryAddUser) or die(mysqli_error($con));

    /*$run2 = mysqli_query($con,$queryAddUser12) or die(mysqli_error($con));*/

    if($run){
      echo "Form Submitted Successfully";
    }else{
      echo "Form not Submitted";
    }

  }else{
    echo "All Fields Requiered";
  }


   header('location: dashboard.php');
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


  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!------ Include the above in your HEAD tag ---------->

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="green" data-background-color="white" data-image="../assets/img/muse2-6.1.jpg">
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
          <li class="nav-item">
            <a class="nav-link" href="./videos.php">
              <i class="material-icons">ondemand_video</i>
              <p>Videos</p>
            </a>
          </li>
          <li class="nav-item active  ">
            <a class="nav-link" href="./dashboard.html">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item ">
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

        
      <div class="card">
        
        <div class="card-body" >

          <form method="GET" action="<?php $_PHP_SELF ?>" enctype='multipart/form-data'>
            <div class="col-md-12">
              <div class="form-group">
                <label>Movie Name</label>
                <select class="form-control select2" name="movie_name">
                  <?php
                  $records = mysqli_query($con, "SELECT movie_name From movies");  // Use select query here 

                  while($data = mysqli_fetch_array($records))
                  {
                      echo "<option value='". $data['movie_name'] ."'>" .$data['movie_name'] ."</option>";  // displaying data in option menu
                                                  
                  }	
                  //$movie_name_1 = $data["movie_name"];   
                  ?>   
                  </select>
                  </div>
                  <script>
                  $('.select2').select2();
                  </script>

              </div>
              
            </div>
            <button style="background-color: #27ae60; width:500px; margin-left:400px;" type="submit" name="but_add_user" value="Upload" class="btn btn-primary pull-right">View Data</button>
            
          </form>          
    
        </div>
      </div>

        <div class="row">
          <div class="card" style="width:650px; margin-left:300px">

          <div style="margin-left:10px">  
            <h3>--- Movie Details ---</h3>
          </div>

            <div class="card-body" >

              <?php
                //read the latest record
                $resultAll = mysqli_query($con, "SELECT movie_name_dash FROM dashboard ORDER BY id DESC LIMIT 1");
                if(!$resultAll){
                  die(mysqli_error($con));
                }

                if (mysqli_num_rows($resultAll) > 0) {
                  while($rowData = mysqli_fetch_array($resultAll)){
                      $movie_name_dash = $rowData["movie_name_dash"];
                  }
                }
                //echo "<h2>" . $username . "</h2>";

                $query11 = "SELECT movieId FROM movies WHERE movie_name = '".$movie_name_dash."'";
                $result11 = mysqli_query($con, $query11);
                $row = mysqli_fetch_assoc($result11);
                $movieId =  $row['movieId'];
                
                $query_atten = "SELECT AVG(attention) as avg_attention FROM atten_emo_enjoy WHERE movie_name = '".$movie_name_dash."'";
                $result_atten = mysqli_query($con, $query_atten);
                $row = mysqli_fetch_assoc($result_atten);
                $attention =  $row['avg_attention'];
                $attention = round($attention,2);
                //$attention = parseFloat(Satisfying).toFixed(2)

                $query_emo = "SELECT AVG(emotion) as avg_emotion FROM atten_emo_enjoy WHERE movie_name = '".$movie_name_dash."'";
                $result_emo = mysqli_query($con, $query_emo);
                $row = mysqli_fetch_assoc($result_emo);
                $emotion =  $row['avg_emotion'];
                $emotion = round($emotion,2);

                $query_enjoy = "SELECT AVG(enjoyment) as avg_enjoyment FROM atten_emo_enjoy WHERE movie_name = '".$movie_name_dash."'";
                $result_enjoy = mysqli_query($con, $query_enjoy);
                $row = mysqli_fetch_assoc($result_enjoy);
                $enjoyment =  $row['avg_enjoyment'];
                $enjoyment = round($enjoyment,2);

                $query_count = "SELECT COUNT(movie_name) as sample_audi FROM atten_emo_enjoy WHERE movie_name = '".$movie_name_dash."'";
                $result_count = mysqli_query($con, $query_count);
                $row = mysqli_fetch_assoc($result_count);
                $sample_audi =  $row['sample_audi'];

                //====================== attention =============================
                if( $attention >= 75.00){
                  $atten_status = 'Good';
                }
                else if (($attention > 50.00) && ($attention < 75.00)){
                  $atten_status = 'Average';
                }
                else{
                  $atten_status = 'Bad';
                }

                //====================== emotion =============================
                if( $emotion >= 75.00){
                  $emotion_status = 'Good';
                }
                else if (($emotion > 50.00) && ($emotion < 75.00)){
                  $emotion_status = 'Average';
                }
                else{
                  $emotion_status = 'Bad';
                }

                //====================== enjoyment =============================
                if( $enjoyment >= 75.00){
                  $enjoyment_status = 'Good';
                }
                else if (($enjoyment > 50.00) && ($enjoyment < 75.00)){
                  $enjoyment_status = 'Average';
                }
                else{
                  $enjoyment_status = 'Bad';
                }

                //====================== overall status =============================

                if (($atten_status=='Good') && ($emotion_status=='Good') && ($enjoyment_status=='Good')){
                  $overall_status = "Better";
                }

                if (($atten_status=='Average') && ($emotion_status=='Good') && ($enjoyment_status=='Good')){
                  $overall_status = "Good";
                }
                if (($atten_status=='Good') && ($emotion_status=='Average') && ($enjoyment_status=='Good')){
                  $overall_status = "Good";
                }
                if (($atten_status=='Good') && ($emotion_status=='Good') && ($enjoyment_status=='Average')){
                  $overall_status = "Good";
                }

                if (($atten_status=='Average') && ($emotion_status=='Average') && ($enjoyment_status=='Good')){
                  $overall_status = "Average";
                }
                if (($atten_status=='Average') && ($emotion_status=='Good') && ($enjoyment_status=='Average')){
                  $overall_status = "Average";
                }
                if (($atten_status=='Good') && ($emotion_status=='Average') && ($enjoyment_status=='Average')){
                  $overall_status = "Average";
                }
                if (($atten_status=='Average') && ($emotion_status=='Average') && ($enjoyment_status=='Average')){
                  $overall_status = "Average";
                }
                
            


              ?>

            <table style="width: 1000px">
              <tbody>
                <!--<tr>
                    <td>Advertisement :</td>
                    <td><input id="Advertisement" type="text"  style="border-style: none" size="25"
                                ></td>

                </tr>
                <tr>
                    <td></td>
                    <td><input id="btn_read_HTML_Table" type="button" value="Final Evaluation" /> </td>
                </tr>-->
                <tr>

                    <td>Movie Name :</td>
                    <td><input id="Audience" type="text" style="border-style: none" size="50" value="<?php echo (isset($movie_name_dash)) ? $movie_name_dash: ''?>"
                                disabled></td>
                
                </tr>
                <tr></tr>
                <td height='15px'></td>
                <tr>

                    <td>Sample Audience :</td>
                    <td><input id="Audience" type="text" style="border-style: none" size="50" value="<?php echo (isset($sample_audi)) ? $sample_audi: ''?>"
                                disabled></td>
                
                </tr>
                <tr></tr>
                <td height='15px'></td>
                <tr>

                    <td>Average Attention :</td>
                    <td><input id="attention" type="text" style="border-style: none" size="50" value="<?php echo (isset($sample_audi)) ? $attention: ''?>"
                                disabled></td>
                </tr>
                <tr></tr>
                <tr>

                    <td>Average Attention Status:</td>
                    <td><input id="attention" type="text" style="border-style: none" size="50" value="<?php echo (isset($atten_status)) ? $atten_status: ''?>"
                                disabled></td>
                </tr>
                <tr></tr>
                <td height='15px'></td>
                <tr>
                    <td>Average Emotion :</td>
                    <td><input id="attention_s" type="text" style="border-style: none" size="50" value="<?php echo (isset($sample_audi)) ? $emotion: ''?>"
                                disabled></td>
                </tr>
                <tr></tr>
                <tr>

                    <td>Average Emotion Status:</td>
                    <td><input id="attention" type="text" style="border-style: none" size="50" value="<?php echo (isset($emotion_status)) ? $emotion_status: ''?>"
                                disabled></td>
                </tr>
                <tr></tr>
                <td height='15px'></td>
                <tr>
                    <td>Average Enjoyment :</td>
                    <td><input id="emotion" type="text" style="border-style: none" size="50" value="<?php echo (isset($sample_audi)) ? $enjoyment: ''?>"
                                disabled></td>
                </tr>
                <tr></tr>
                <tr>

                    <td>Average Enjoyment Status:</td>
                    <td><input id="attention" type="text" style="border-style: none" size="50" value="<?php echo (isset($enjoyment_status)) ? $enjoyment_status: ''?>"
                                disabled></td>
                </tr>
                <tr></tr>
                <td height='15px'></td>
                <!--<tr>
                    <td>Overall Status :</td>
                    <td><input id="emotion" type="text" style="border-style: none" size="50" value="<?php echo (isset($overall_status)) ? $overall_status: ''?>"
                                disabled></td>
                </tr>-->
                <tr></tr>
                
              </tbody>
            </table>


              <!--<?php
                echo "Latest Movie:";
                echo "<h4>  " . $movie_name_dash . " </h4>";
                echo "Sample Audiance:";
                echo "<h4>  " . $sample_audi . " </h4>";
                echo "Avg Attention:";
                echo "<h4>  " . $attention . " </h4>";
                echo "Avg Emotion:";
                echo "<h4>  " . $emotion . " </h4>";
                echo "Avg Enjoyment:";
                echo "<h4>  " . $enjoyment . " </h4>";
              ?>-->

                      
        
        
            </div>
          </div>
          
          <!--<div class="card" style="width:650px; margin-left:20px">
          
            <div style="margin-left:10px">  
              <h3>--- Movie Evaluation ---</h3>
            </div>

            <div class="card-body" >

            <?php 
            
                //====================== attention =============================
                if( $attention >= 75.00){
                  $atten_status = 'Good';
                }
                else if (($attention > 50.00) && ($attention < 75.00)){
                  $atten_status = 'Average';
                }
                else{
                  $atten_status = 'Bad';
                }

                //====================== emotion =============================
                if( $emotion >= 75.00){
                  $emotion_status = 'Good';
                }
                else if (($emotion > 50.00) && ($emotion < 75.00)){
                  $emotion_status = 'Average';
                }
                else{
                  $emotion_status = 'Bad';
                }

                //====================== enjoyment =============================
                if( $enjoyment >= 75.00){
                  $enjoyment_status = 'Good';
                }
                else if (($enjoyment > 50.00) && ($enjoyment < 75.00)){
                  $enjoyment_status = 'Average';
                }
                else{
                  $enjoyment_status = 'Bad';
                }
            
            
            ?>



              <?php
                echo "Overall Attention Status:";
                echo "<h4>  " . $atten_status . " </h4>";

                echo "Overall Emotion Status:";
                echo "<h4>  " . $emotion_status . " </h4>";

                echo "Overall Enjoyment Status:";
                echo "<h4>  " . $enjoyment_status . " </h4>";
                
              ?>

                      
        
        
            </div>
          </div>-->



        </div>



    </div>
  </div>
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
