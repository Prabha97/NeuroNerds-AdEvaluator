<?php

include("config.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>VAUED - Real Time Analytic</title>
    <link href="css/styles.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
          crossorigin="anonymous"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js"
            crossorigin="anonymous"></script>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
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

    <div class="sidebar" data-color="green" data-background-color="white" data-image="../assets/img/muse2-4.jpg">
     
      <div class="logo">
        <a href="http://www.creative-tim.com" class="simple-text logo-normal">
          Group 2021-114
        </a>
      </div>

      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item active">
            <a class="nav-link" href="./Attention.php">
              <i class="material-icons">library_books</i>
              <p>Analysis</p>
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
          <li class="nav-item ">
            <a class="nav-link" href="./dashboard.html">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./userScores.php">
              <i class="material-icons">content_paste</i>
              <p>User Scores</p>
            </a>
          </li>
        </ul>
      </div>

    </div>

    
    <div class="main-panel">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="Approvers-tab" data-toggle="tab" href="#Approvers" role="tab" aria-controls="Approvers" aria-selected="true">Upload Data: </a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="Approvers" role="tabpanel" aria-labelledby="Approvers-tab">
                                <br>
                                <form id="upload_csv" name="uploadcsv" method="post" enctype="multipart/form-data">
                                    <div class="container-fluid">
                                        <div class="card mb-4">
                                            <div class="card-header" >View Analysis</div>
                                            
                                            <div class="col-lg-6">
                                                <button type="submit" class="btn btn-info" name="submit" id="submit">Analyze
                                                    EEG Data
                                                </button>

                                            </div>

                                            <?php
                                              //read the latest record
                                              $resultAll = mysqli_query($con, "SELECT username FROM userrecords ORDER BY user_id DESC LIMIT 1");
                                              if(!$resultAll){
                                                die(mysqli_error($con));
                                              }

                                              if (mysqli_num_rows($resultAll) > 0) {
                                                while($rowData = mysqli_fetch_array($resultAll)){
                                                    $username = $rowData["username"];
                                                }
                                              }
                                              //echo "<h2>" . $username . "</h2>";

                                              $query11 = "SELECT user_id FROM users WHERE username = '".$username."'";
                                              $result11 = mysqli_query($con, $query11);
                                              $row = mysqli_fetch_assoc($result11);
                                              $userID =  $row['user_id'];
                                              //echo $row['user_id'];

                                              $getRecom = "Get Recommendations: ";
                                              //echo "Get Recommendations"."<h3> <a href='recommendations.php?userId=" . $row['user_id'] . "' target='_blank' >" . $row['user_id'] ." , " . $username . "</a></h3>";
                                              //echo "<h3>" . $getRecom . "</h3>"."<h3> <a href='recommendations.php?userId=" . $row['user_id'] . "' target='_blank' >" . $row['user_id'] ." , " . $username . "</a></h3>";
                                              //echo "Latest User:";
                                              //echo "<h3> <a href='recommendations.php?userId=" . $row['user_id'] . "' target='_blank' >" . $row['user_id'] ." , " . $username . "</a></h3>";

                                              //echo "<h3>" . $userID . "," . $username . "</h3>";

                                            ?>

                                            <div class="col-lg-6">
                                              <?php
                                                echo "Latest User:";
                                                echo "<h3> <a href='recommendations.php?userId=" . $row['user_id'] . "' target='_blank' >" . $row['user_id'] ." , " . $username . "</a></h3>";
                                              ?>
                                            </div>

                                            </br>
                                        </div>
                                        <div class="card-footer small text-muted">
                                            <b> Last update Time:&nbsp;</b>
                                            <input type="text" size="50" id="Approvers_update_time" style="border-style: none" value=""
                                                   disabled>
                                            <b> &nbsp;&nbsp;Last update By:&nbsp;</b>
                                            <input type="text" size="50" id="Approvers_update_by" style="border-style: none" value="" disabled>
                                        </div>
                                    </div>

                                </form>

                            </div>



                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="card mb-4">
                                        <div class="card-header"><i class="fas fa-chart-bar mr-1"></i> Minitue Wise user attention Score
                                        </div>
                                        <div class="card-body">
                                            <label>Attention Score<br>(%)</label>
                                            <canvas id="Q2_BarChart" width="100%" height="50"></canvas>

                                        </div>
                                        <label  style="text-align: center">Minutes</label>
                                        <div class="card-footer small text-muted"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="card mb-4">
                                        <div class="card-header"><i class="fas fa-chart-bar mr-1"></i> Emotion Analysis
                                        </div>
                                        <div class="card-body">
                                            <label>Emotion Score<br>(%)</label>
                                            <canvas id="Q3_BarChart" width="100%" height="50"></canvas>
                                        </div>
                                        <label  style="text-align: center">Emotion Category</label>
                                        <div class="card-footer small text-muted"></div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="card mb-4">
                                        <div class="card-header"><i class="fas fa-chart-bar mr-1"></i> Minutes Wise User Attention Score (%)
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                <table style="width: 800px">
                                                    <tbody>
                                                    <tr>
                                                        <td>High Attention Score:</td>
                                                        <td><input id="requestedBy" type="text"  style="border-style: none" size="50"
                                                                   value="" disabled></td>

                                                    </tr>
                                                    <tr>
                                                        <td>Medium Attention Score:</td>
                                                        <td><input id="requestedBy1" type="text"  style="border-style: none" size="50"
                                                                   value="" disabled></td>

                                                    </tr>
                                                    <tr>
                                                        <td>Low Attention Score:</td>
                                                        <td><input id="requestedBy2" type="text"  style="border-style: none" size="50"
                                                                   value="" disabled></td>

                                                    </tr>
                                                    <tr>
                                                        <td>Overall Average Attention Score:</td>
                                                        <td><input id="requestedBy3" type="text"  style="border-style: none" size="50"
                                                                   value="" disabled></td>

                                                    </tr>
                                                    <!--<tr><br>-</tr>-->
                                                    <tr>
                                                        <td>Status:</td>
                                                        <td><input id="status" type="text" style="border-style: none" size="50" value=""
                                                                   disabled></td>
                                                    </tr>
                                                    <tr></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer small text-muted"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="card mb-4">
                                        <div class="card-header"><i class="fas fa-chart-bar mr-1"></i> Emotional Score (%)
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                <table style="width: 800px">
                                                    <tbody>
                                                    <tr>
                                                        <td>Neutral:</td>
                                                        <td><input id="Happy" type="text"  style="border-style: none" size="50"
                                                                   value="" disabled></td>

                                                    </tr>
                                                    <tr>

                                                        <td>Happy:</td>
                                                        <td><input id="Neutral" type="text" style="border-style: none" size="50" value=""
                                                                   disabled></td>
                                                    </tr>
                                                    <tr>

                                                        <td>Sad:</td>
                                                        <td><input id="Sad" type="text" style="border-style: none" size="50" value=""
                                                                   disabled></td>
                                                    </tr>
                                                    <tr></tr>
                                                    <tr>
                                                        <td>Satisfying:</td>
                                                        <td><input id="Satisfying" type="text" style="border-style: none" size="50" value=""
                                                                   disabled></td>
                                                    </tr>
                                                    <tr></tr>
                                                    <tr>
                                                        <td>Status:</td>
                                                        <td><input id="high_score" type="text" style="border-style: none" size="50" value=""
                                                                   disabled></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer small text-muted"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="card mb-4">
                                            <div class="card-header"><i class="fas fa-chart-bar mr-1"></i> Enjoyment Efficiency
                                            </div>
                                            <div class="card-body">
                                                <canvas id="Q4_BarChart" width="100%" height="50"></canvas>
                                            </div>
                                            <div class="card-footer small text-muted"></div>
                                        </div>
                                    </div>
                                <div class="col-xl-6">
                                    <div class="card mb-4">
                                        <div class="card-header"><i class="fas fa-chart-bar mr-1"></i> Enjoyment Analysis
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                <table style="width: 800px">
                                                    <tbody>
                                                    <tr>
                                                        <td>Enjoyment Efficacy Score:</td>
                                                        <td><input id="enjoyescore" type="text"  style="border-style: none" size="50"
                                                                   value="" disabled></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Enjoyment Efficacy Status:</td>
                                                        <td><input id="ej_status" type="text"  style="border-style: none" size="50"
                                                                   value="" disabled></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer small text-muted"></div>
                                    </div>
                                </div>
                            </div>
                            </div>

                        </div>
                    </div>
                </div>





        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; </div>
                    
                </div>
            </div>
        </footer>
    </div>
</div>

<script>
    
     {

        $.ajax({
            method: 'GET',
            headers: {'Content-Type': 'application/json', 'Access-Control-Allow-Origin': '*'},
            //url: ' http://0.0.0.0:3019/score',
            url: 'http://127.0.0.1:3019/score',  // GETdata from API
            async: false,
            success: function (data) {
                console.log(data);
                let min = [];
                let min_score = [];
                let score = data['1'];
                let medium_score = data['3'];
                let low_score = data['2'];
                let score_val = data['4'];
                let status = data['5'];
                document.getElementById("requestedBy").value = parseFloat(score).toFixed(2)+"%";
                document.getElementById("requestedBy1").value = parseFloat(medium_score).toFixed(2)+"%";
                document.getElementById("requestedBy2").value = parseFloat(low_score).toFixed(2)+"%";
                document.getElementById("requestedBy3").value = parseFloat(score_val).toFixed(2)+"%";
                document.getElementById("status").value = status;

                let a_score= parseFloat(score).toFixed(2);
                let m_score= parseFloat(medium_score).toFixed(2);
                let l_score= parseFloat(low_score).toFixed(2);
                let over_score= parseFloat(score_val).toFixed(2);
                let a_status= status;

                //delete data["1"];
                //delete data["3"];
                //delete data["2"];
                //delete data["4"];
                delete data["5"];

                // Emotional Data : 'Happy = 100,'Neutral =200, Sad= 300,'Satisfying =400, high_score= 500
                let Happy = data['100'];
                let Neutral = data['200'];
                let Sad = data['300'];
                let Satisfying = data['400'];
                let high_score = data['500'];
                let emotion= [Happy,Neutral,Sad,Satisfying];
                console.log(emotion);

                //get highes emotion score
                let e_score = Math.max(Happy,Neutral,Sad,Satisfying).toFixed(2);
                let e_status= high_score;
                console.log("eeeeeeeeeeeeeeee"+e_score);
                var vtx = document.getElementById('Q3_BarChart'); //dispaly attention result on dashbord bar graph
                var mixedChart = new Chart(vtx, {
                    type: 'bar',
                    data: {
                        datasets: [{

                            label: "Bar",
                            backgroundColor: ["rgba(51, 110, 123, 1)", "rgba(0, 181, 204, 1)", "rgba(30, 139, 195, 1)", "rgba(137, 196, 244, 1)", "rgba(2,117,216,1)",
                                "rgba(2,117,216,1)", "rgb(216,38,94)", "rgb(216,38,94)", "rgb(216,38,94)", "rgb(216,38,94)", "rgb(216,38,94)"
                                , "rgb(216,38,94)", "rgb(216,38,94)"],
                            borderColor: "rgba(2,117,216,1)",
                            data: emotion,
                            order: 1
                        }, 
                      
                    ],
                        labels: ["Neutral","Happy","Sad","Satisfying"]

                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'product id'
                                },
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                },
                                gridLines: {
                                    display: true
                                }
                            }],
                        },

                    }

                });
                //display emotion scores on dashbord form
                document.getElementById("Happy").value =  parseFloat(Happy).toFixed(2)+"%";
                document.getElementById("Neutral").value = parseFloat(Neutral).toFixed(2)+"%";
                document.getElementById("Sad").value = parseFloat(Sad).toFixed(2)+"%";
                document.getElementById("Satisfying").value = parseFloat(Satisfying).toFixed(2)+"%";
                document.getElementById("high_score").value = high_score;
                //clear list data
                delete data["100"];
                delete data["200"];
                delete data["300"];
                delete data["400"];
                delete data["500"];

                // data from enjoyment score
                enjoyment_score= [];
                let Enjoye = data['900'];
                let not_enjoy = data['1000'];
                let ej_score= parseFloat(Enjoye).toFixed(2);
                let ej_status;
                if(ej_score> 70.00){
                    ej_status= "Excellent";
                }
                else if(ej_score> 30.00){
                    ej_status= "Average";
                }else{
                    ej_status= "Poor";
                }


                enjoyment_score.push(parseInt(data['900']));
                enjoyment_score.push(parseInt(data['1000']));

               // display enjoyment data on pie chart
                var enj = document.getElementById('Q4_BarChart').getContext('2d')
                var myPieChart = new Chart(enj, {
                    type: 'pie',
                    data: {
                        labels: ["Enjoye eficency","not enjoy"],
                        datasets: [{
                            data: enjoyment_score,
                            backgroundColor: ['#50C878', '#202e20'],
                        }],
                    },
                });
                document.getElementById("enjoyescore").value = Enjoye+"%";
                document.getElementById("ej_status").value = ej_status;
                delete data["900"];
                delete data["1000"];

                for (let key in data) {
                    console.log(key);
                    // console.log(avgFare);
                    min.push(key);
                    min_score.push(data[key]);
                }
                console.log(min);
                console.log(min_score);

                let ctx = document.getElementById('Q2_BarChart');
                let chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: min,
                        datasets: [{
                            //label: "Last min avg fare",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(2,117,216,1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 50,
                            pointBorderWidth: 2,
                            data: min_score,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'Min'
                                },
                                gridLines: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                },
                                gridLines: {
                                    display: true
                                }
                            }],
                        },
                        legend: {
                            display: false
                        }
                    }
                });

                uploadData(a_score, a_status, e_score, e_status, ej_score,ej_status);
                console.log(a_score,a_status,e_score,e_status, ej_score,ej_status);
            },
            error: function (errormessage) {
                console.log(errormessage);

            }
        });

    }
</script>
<script>
    function uploadData(a_score, a_status, e_score, e_status, ej_score,ej_status) {
        var userid,username,adname,adcategory,evaluation;
        userid= document.getElementById("userId").value;
        username= document.getElementById("uname").value;
        adname= document.getElementById("adname").value;
        adcategory=  document.getElementById("adcategory").value;
        adcategory= adcategory.trim();
        if(a_status != 'Weak' && e_status==adcategory && ej_status != 'Poor'){
            evaluation= 'Pass';
        }
        else{
            evaluation= 'Fail';
        }
        if (userid != "" && username != "" && adname != "" && adcategory != "") {

            console.log(a_score,a_status,e_score,e_status, ej_score,ej_status);
            $.ajax({
                url: "insertData.php",
                type: "POST",
                async: false,
                data: {a_score: a_score, a_status: a_status,e_score: e_score, e_status:e_status, ej_score: ej_score, ej_status: ej_status,
                    userid: userid, username: username, adname: adname, adcategory: adcategory, evaluation: evaluation},
                success: function (response) {
                    console.log(response);
                    userid= document.getElementById("userId").value= "";
                    username= document.getElementById("uname").value= "";
                    adname= document.getElementById("adname").value= "";
                    adcategory=  document.getElementById("adcategory").value= "";
                },

                error: function (errormessage) {
                    console.log("Data update fail");
                }
            });
        }


    }
</script>
      
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
