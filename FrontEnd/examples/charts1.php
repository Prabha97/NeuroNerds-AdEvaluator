<?php

include("config.php"); 

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Mind Monitor - See what's really going on inside your head! Real time EEG graphs from your Interaxon Muse headband">
    <meta name="keywords" content="Mind Monitor,Muse Monitor,Muse,Monitor,Interaxon,EEG,OSC,Data,Frequency,Streaming,Recording,Brain Waves,Real time,FFT">
    <meta name="author" content="James Clutterbuck">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/favicon/manifest.json">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="msapplication-config" content="/favicon/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- <script src="../assets/js/core/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <meta property="og:title" content="Mind Monitor" />
    <meta property="og:description" content="Mind Monitor - See what's really going on inside your head! Real time EEG graphs from your Interaxon Muse headband" />
    <meta property="og:url" content="https://mind-monitor.com" />
    <meta property="og:locale" content="en_CA" />
    <meta property="og:image" content="https://mind-monitor.com/img/mm-background.jpg" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Mind Monitor" />
    
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>EEG Based Ad Evaluator</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
   
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="custom/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="custom/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="custom/device-mockups/device-mockups.min.css">
    
    <!-- Theme CSS -->
    <link href="custom/custom.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-153568824-1', 'auto');
	ga('send', 'pageview');
	function gaTrack(c,l){ga('send', {hitType: 'event',eventCategory: c,eventAction: 'Click',eventLabel: l});return true;};
    </script>
    
    <!-- Google Charts -->
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
      google.charts.load('current', {'packages':['corechart','gauge']});
      google.charts.setOnLoadCallback(initChart);
      
      var chartData;
      var chartView;
      var chartOptions;
      var chart;
      
      var gaugeDataPoint;
      var gaugeDataSessionAbs;
      var gaugeDataSessionRel;
      var gaugeOptionsPoint;
      var gaugeOptionsAbs;
      var gaugeOptionsRel;
      var gaugesPoint;
      var gaugesSessionAbs;
      var gaugesSessionRel;
      
      var barDataPoint;
      var barDataSession;
      var barOptions;
      var barPoint;
      var barSession;
      
      var annotations = [];
      var annotationStyle = null;
      var annotationMarker = null;
      var annotationText = null;
      var timedFunc;
      var series;
      
      function clearChartData(){
        chartData = new google.visualization.DataTable();
        chartData.addColumn('datetime', 'Date');
        chartData.addColumn('number', 'Delta'); //Col 1 - Absolute
        chartData.addColumn('number', 'Theta');
        chartData.addColumn('number', 'Alpha');
        chartData.addColumn('number', 'Beta');
        chartData.addColumn('number', 'Gamma');
        
        chartData.addColumn('number', 'Delta Rel'); //Col 6 - Relative
        chartData.addColumn('number', 'Theta Rel');
        chartData.addColumn('number', 'Alpha Rel');
        chartData.addColumn('number', 'Beta Rel');
        chartData.addColumn('number', 'Gamma Rel');
        
        chartData.addColumn('number', 'Acc X'); //Col 11
        chartData.addColumn('number', 'Acc Y');
        chartData.addColumn('number', 'Acc Z');
        
        chartData.addColumn('number', 'Gyro X'); //Col 14
        chartData.addColumn('number', 'Gyro Y');
        chartData.addColumn('number', 'Gyro Z');
        
        chartData.addColumn('number', 'RAW TP9'); //Col 17
        chartData.addColumn('number', 'RAW AF7');
        chartData.addColumn('number', 'RAW AF8');
        chartData.addColumn('number', 'RAW TP10');
        chartData.addColumn('number', 'RAW AUX-R');
        
        chartData.addColumn('number', 'Battery'); //Col 22
        
        chartData.addColumn('number', 'Delta Left'); //Col 23 (Absolutes)
        chartData.addColumn('number', 'Theta Left');
        chartData.addColumn('number', 'Alpha Left');
        chartData.addColumn('number', 'Beta Left');
        chartData.addColumn('number', 'Gamma Left');
        
        chartData.addColumn('number', 'Delta Right'); //Col 28
        chartData.addColumn('number', 'Theta Right');
        chartData.addColumn('number', 'Alpha Right');
        chartData.addColumn('number', 'Beta Right');
        chartData.addColumn('number', 'Gamma Right');
        
        chartData.addColumn({type:'string', role:'style'});//Annotation style
        chartData.addColumn({type:'string', role:'annotation'}); // Chart marker
        chartData.addColumn({type:'string', role:'annotationText'}); // Hover text
      }
      
      function setChartOptions(){
        var smooth = document.getElementById('chartSmooth').checked;
        var curveTypeValue;
        if (smooth){
          curveTypeValue = 'function';
        } else {
          curveTypeValue = 'none';
        }
        
        var elements = document.getElementsByName('chartAverageMinutes');
        var averageMinutes;
        for (var i=0;i<elements.length;i++){
          if (elements[i].checked){
            averageMinutes = (elements[i].value==1)?true:false;
          }
        }
          
        var titleValue = 'Mind Monitor - ';
        
        if (averageMinutes){
          titleSensors = 'Average';
        }
        
        var eS = document.getElementsByName('chartSeries');

        for (var i=0;i<eS.length;i++){
          if (eS[i].checked){
            series = eS[i].value;
          }
        }
        
        var sensors = [
          document.getElementById('TP9').checked,
          document.getElementById('AF7').checked,
          document.getElementById('AF8').checked,
          document.getElementById('TP10').checked,
          document.getElementById('AUXR').checked];
          
        var waves = [
          document.getElementById('Delta').checked,
          document.getElementById('Theta').checked,
          document.getElementById('Alpha').checked,
          document.getElementById('Beta').checked,
          document.getElementById('Gamma').checked
        ];
        
        var colourOrder = [
          '#CC0000',
          '#9933CC',
          '#0099CC',
          '#669900',
          '#FF8A00'
        ];
        
        var waveColours = [
          '#CC0000',
          '#9933CC',
          '#0099CC',
          '#669900',
          '#FF8A00'
        ];
        
        if (series=='Absolute' || series=='Relative'){
          document.getElementById('Delta').disabled=false;
          document.getElementById('Theta').disabled=false;
          document.getElementById('Alpha').disabled=false;
          document.getElementById('Beta').disabled=false;
          document.getElementById('Gamma').disabled=false;
        } else {
          document.getElementById('Delta').disabled=true;
          document.getElementById('Theta').disabled=true;
          document.getElementById('Alpha').disabled=true;
          document.getElementById('Beta').disabled=true;
          document.getElementById('Gamma').disabled=true;
        }
        
        if (series=='Absolute' || series=='Relative'){
          document.getElementById('AUXR').checked=false;
          document.getElementById('AUXR').disabled=true;
        }
        
        if (series=='Absolute' || series=='Relative' || series=='RAW'){
          document.getElementById('TP9').disabled=false;
          document.getElementById('AF7').disabled=false;
          document.getElementById('AF8').disabled=false;
          document.getElementById('TP10').disabled=false;
          if (series=='RAW') {document.getElementById('AUXR').disabled=false;}            
          var sNames = [];
          if (sensors[0]){sNames.push('TP9');}
          if (sensors[1]){sNames.push('AF7');}
          if (sensors[2]){sNames.push('AF8');}
          if (sensors[3]){sNames.push('TP10');}
          if (sensors[4]){sNames.push('AUX-R');}
          sensorsString = '('+sNames.join(',')+')';

          var titleSensors = '';          
          if (!arrayEqual(sensors,[1,1,1,1,0])){
            titleSensors = sensorsString;
          }
          
          if (arrayEqual(sensors,[0,0,0,0,0])){
           titleSensors = 'NO SENSORS SELECTED';        
          }
          if (arrayEqual(sensors,[1,1,0,0,0])){
           titleSensors = 'Left '+sensorsString;        
          }
          if (arrayEqual(sensors,[0,1,1,0,0])){
           titleSensors = 'Front '+sensorsString;        
          }
          if (arrayEqual(sensors,[0,0,1,1,0])){
           titleSensors = 'Right '+sensorsString;        
          }
          if (arrayEqual(sensors,[1,0,0,1,0])){
           titleSensors = 'Back '+sensorsString;        
          }
                  
          titleValue = titleValue + titleSensors + ' ';
        } else {
          document.getElementById('TP9').disabled=true;
          document.getElementById('AF7').disabled=true;
          document.getElementById('AF8').disabled=true;
          document.getElementById('TP10').disabled=true;
          document.getElementById('AUXR').disabled=true;           
        }

        chartView = new google.visualization.DataView(chartData);
        chartView.hideColumns([23]);//D Left
        chartView.hideColumns([24]);//T Left
        chartView.hideColumns([25]);//A Left
        chartView.hideColumns([26]);//B Left
        chartView.hideColumns([27]);//G Left
        chartView.hideColumns([28]);//D Right
        chartView.hideColumns([29]);//T Right
        chartView.hideColumns([30]);//A Right
        chartView.hideColumns([31]);//B Right
        chartView.hideColumns([32]);//G Right
        if (series=='Acc') {
          titleValue+='Accelerometer';
          chartView.hideColumns([1,2,3,4,5]);//Absolute
          chartView.hideColumns([6,7,8,9,10]);//Relative
          chartView.hideColumns([14,15,16]);//Gyro
          chartView.hideColumns([17,18,19,20,21]);//Raw
          chartView.hideColumns([22]);//battery
          setSelectValues('yAxisMax',[1,2,3,4]);
          setSelectValues('yAxisMin',[-1,-2,-3,-4]);
        } else if (series=='Gyro') {
          titleValue+='Gyroscope';
          chartView.hideColumns([1,2,3,4,5]);//Absolute
          chartView.hideColumns([6,7,8,9,10]);//Relative
          chartView.hideColumns([11,12,13]);//Acc
          chartView.hideColumns([17,18,19,20,21]);//Raw
          chartView.hideColumns([22]);//battery
          setSelectValues('yAxisMax',[1,2,3,4,5,6,7,8,9,10]);
          setSelectValues('yAxisMin',[-1,-2,-3,-4,-5,-6,-7,-8,-9,-10]);
        } else if (series=='Absolute') {
          titleValue+='Absolute Brain Waves';
          chartView.hideColumns([6,7,8,9,10]);//Relative
          chartView.hideColumns([11,12,13]);//Acc
          chartView.hideColumns([14,15,16]);//Gyro
          chartView.hideColumns([17,18,19,20,21]);//Raw
          chartView.hideColumns([22]);//battery
          setSelectValues('yAxisMax',[100,150,200]);
          setSelectValues('yAxisMin',[0,-100]);
          var ci=0;
          waveColours=[];
          for(var i=0;i<waves.length;i++){
            if (!waves[i]){
              chartView.hideColumns([i+1]);
            } else {
              waveColours.push(colourOrder[ci]);
            }
            ci++;
          }
        } else if (series=='Relative') {
          titleValue+='Relative Brain Waves';
          chartView.hideColumns([1,2,3,4,5]);//Absolute
          chartView.hideColumns([11,12,13]);//Acc
          chartView.hideColumns([14,15,16]);//Gyro
          chartView.hideColumns([17,18,19,20,21]);//Raw
          chartView.hideColumns([22]);//battery
          setSelectValues('yAxisMax',[1,1.1]);
          setSelectValues('yAxisMin',[0,-0.1]);
          var ci=0;
          waveColours=[];
          for(var i=0;i<waves.length;i++){
            if (!waves[i]){
              chartView.hideColumns([i+6]);
            } else {
              waveColours.push(colourOrder[ci]);
            }
            ci++;
          }
        } else if (series=='RAW') {
          titleValue+='RAW Brain Waves';
          chartView.hideColumns([1,2,3,4,5]);//Absolute
          chartView.hideColumns([6,7,8,9,10]);//Relative
          chartView.hideColumns([11,12,13]);//Acc
          chartView.hideColumns([14,15,16]);//Gyro
          chartView.hideColumns([22]);//battery
          for(var i=0;i<sensors.length;i++){
            if (!sensors[i]){
              chartView.hideColumns([i+17]);
            }
          }
          setSelectValues('yAxisMax',[800,850,900,950,1000,1050]);
          setSelectValues('yAxisMin',[600,650,700,750,800]);
        } else {
          titleValue+='Battery';
          chartView.hideColumns([1,2,3,4,5]);//Absolute
          chartView.hideColumns([6,7,8,9,10]);//Relative
          chartView.hideColumns([11,12,13]);//Acc
          chartView.hideColumns([14,15,16]);//Gyro
          chartView.hideColumns([17,18,19,20,21]);//Raw
          setSelectValues('yAxisMax',[25,50,100]);
          setSelectValues('yAxisMin',[0]);
        }

        var yAxisMin = document.getElementById('yAxisMin').value;
        var yAxisMax = document.getElementById('yAxisMax').value;

        chartOptions = {
          explorer: {
            axis: 'horizontal',
            maxZoomOut:1,
            keepInBounds:true
          },
          title: titleValue,
          curveType: curveTypeValue,
          chartArea : { top: 60, bottom: 60, left : 60, right : 60},
          legend: { position: 'bottom' },
          series: {
            0: { color: waveColours[0] },
            1: { color: waveColours[1] },
            2: { color: waveColours[2] },
            3: { color: waveColours[3] },
            4: { color: waveColours[4] },

            5: { color: '#CC0000' },
            6: { color: '#9933CC' },
            7: { color: '#0099CC' },
            8: { color: '#669900' },
            9: { color: '#FF8A00' },

            10: { color: '#CC0000' },
            11: { color: '#669900' },
            12: { color: '#0099CC' },
            
            13: { color: '#CC0000' },
            14: { color: '#669900' },
            15: { color: '#0099CC' },

            16: { color: '#CC0000' },
            17: { color: '#9933CC' },
            18: { color: '#0099CC' },
            19: { color: '#669900' },
            20: { color: '#FF8A00' },
            
            21: { color: '#CC0000' }//Battery
          },
          vAxis: {viewWindow:{min: yAxisMin, max: yAxisMax}}
        };
        
        if ((series=='Gyro')||(series=='Acc')) {
          chartOptions.series = {
            0: { color: '#CC0000' },
            1: { color: '#669900' },
            2: { color: '#0099CC' }
          }
        }
      }
      
      function setSelectValues(id,values){
        var selectElem = document.getElementById(id);
        startValue = selectElem.value;
        selectElem.options.length=0;
        selectElem.options[selectElem.options.length] = new Option('Auto','automatic',false,false);
        values.forEach(function(value){
          selectElem.options[selectElem.options.length] = new Option(value,value,false,false);
          if (startValue == value){
            selectElem.options[selectElem.options.length-1].selected = true;
          }
        });
      }
      
      function initChart() {
        //Set defaults
        var animate = false;
        document.getElementById("chartAnimate").checked = animate;
        document.getElementById("chartAnimateDelay").disabled = !animate;
        var elements = document.getElementsByName("cas");
        for (var i=0;i<elements.length;i++){
          elements[i].disabled = !animate;
        }
      
        clearChartData();
        setChartOptions();
        chart = new google.visualization.LineChart(document.getElementById('museChart'));
        //chart.draw(chartView, chartOptions);

        //Extra test stuff        
        google.visualization.events.addListener(chart, 'select', selectHandler);
        
        //Gauges
        gaugeDataPoint = new google.visualization.DataTable();
        gaugeDataSessionAbs = new google.visualization.DataTable();
        gaugeDataSessionRel = new google.visualization.DataTable();
        
        gaugeOptionsPoint = {
          height: 100,
          min:-100, max:100
        };
        
        gaugeOptionsAbs = {
          height: 100,
          min:-100, max:100
        };
        
        gaugeOptionsRel = {
          height: 100,
          min:0, max:1
        };
        
        gaugesPoint = new google.visualization.Gauge(document.getElementById('museGaugesPoint'));
        gaugesPoint.draw(gaugeDataPoint, gaugeOptionsPoint);
        
        gaugesSessionAbs = new google.visualization.Gauge(document.getElementById('museGaugesSessionAbs'));
        gaugesSessionAbs.draw(gaugeDataSessionAbs, gaugeOptionsAbs);
        
        gaugesSessionRel = new google.visualization.Gauge(document.getElementById('museGaugesSessionRel'));
        gaugesSessionRel.draw(gaugeDataSessionRel, gaugeOptionsRel);
        
        //Bar chart
        barDataPoint = new google.visualization.DataTable();
        barDataSession = new google.visualization.DataTable();
        
        barOptions = {
          title: 'Left vs Right',
          height:200,
          width:300,
          isStacked:true,
          legend: { position: 'none' },
          hAxis: { textPosition: 'none'}
        };
        
        barPoint = new google.visualization.BarChart(document.getElementById('museBarChartPoint'));
        barSession = new google.visualization.BarChart(document.getElementById('museBarChartSession'));         
      }
      
      function selectHandler(e){
        var selection = chart.getSelection();
        if (selection.length<1){return;}
        
        var dataOffset=0;
        if (series=='Relative') {
          dataOffset=5;
        }
        
        var dataRow = selection[0].row;
        gaugeOptionsPoint.min = Math.floor(Math.min(
          chartData.getColumnRange(dataOffset+1).min,
          chartData.getColumnRange(dataOffset+2).min,
          chartData.getColumnRange(dataOffset+3).min,
          chartData.getColumnRange(dataOffset+4).min,
          chartData.getColumnRange(dataOffset+5).min
          )*10)/10;
        gaugeOptionsPoint.max = Math.ceil(Math.max(
          chartData.getColumnRange(dataOffset+1).max,
          chartData.getColumnRange(dataOffset+2).max,
          chartData.getColumnRange(dataOffset+3).max,
          chartData.getColumnRange(dataOffset+4).max,
          chartData.getColumnRange(dataOffset+5).max
          )*10)/10;

        gaugeDataPoint = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Point '+chartData.getColumnLabel(dataOffset+1), chartData.getValue(dataRow,dataOffset+1)],//Delta
          ['Point '+chartData.getColumnLabel(dataOffset+2), chartData.getValue(dataRow,dataOffset+2)],//Theta
          ['Point '+chartData.getColumnLabel(dataOffset+3), chartData.getValue(dataRow,dataOffset+3)],//Alpha
          ['Point '+chartData.getColumnLabel(dataOffset+4), chartData.getValue(dataRow,dataOffset+4)],//Beta
          ['Point '+chartData.getColumnLabel(dataOffset+5), chartData.getValue(dataRow,dataOffset+5)]//Gamma
        ]);
        gaugesPoint.draw(gaugeDataPoint, gaugeOptionsPoint);
        
        //Bar chart
        barOptions.title = 'Selected Point Absolute - Left vs Right\n'+chartData.getValue(dataRow,0);
        
        minBarValue = 0;
        for(var x=0;x<10;x++){
          minBarValue = Math.min(minBarValue,chartData.getValue(dataRow,17+x));
        }
        if (minBarValue>0){ minBarValue = 0 };
        if (minBarValue<0){ minBarValue = -minBarValue };
        minBarValue = minBarValue + 0.1;//Just to show that the zeroed relative min value isn't actually zero.
        
        barDataPoint = google.visualization.arrayToDataTable([
          ['Label', 'Left', 'Right'],
          ['Delta', -(minBarValue+chartData.getValue(dataRow,22)), minBarValue+chartData.getValue(dataRow,27)],
          ['Theta', -(minBarValue+chartData.getValue(dataRow,23)), minBarValue+chartData.getValue(dataRow,28)],
          ['Alpha', -(minBarValue+chartData.getValue(dataRow,24)), minBarValue+chartData.getValue(dataRow,29)],
          ['Beta', -(minBarValue+chartData.getValue(dataRow,25)), minBarValue+chartData.getValue(dataRow,30)],
          ['Gamma', -(minBarValue+chartData.getValue(dataRow,26)), minBarValue+chartData.getValue(dataRow,31)]
        ]);
        
        barPoint.draw(barDataPoint, barOptions);
      }
      
      function updateImageShareLink(){
        document.getElementById('png').innerHTML = '<blockquote style="display:inline-block">Right click this <img download="MindMonitor.png" alt="Mind Monitor Graph" height="47" src="' + chart.getImageURI() + '"/> to "Save As" a PNG Image.</blockquote>';
        document.getElementById('png').style.display="inline-block";
      }
      
      function readInputFile(file) {
        console.log(file);
        var pngLink = document.getElementById('png');
        if (pngLink!=null){
          document.getElementById('png').style.display="none";
          console.log('inside pngLink');
        }
        var elements = document.getElementsByName('chartAverageMinutes');
        var averageMinutes;
        for (var i=0;i<elements.length;i++){
          if (elements[i].checked){
            averageMinutes = (elements[i].value==1)?true:false;
          }
        } 
        var showDataMarkers = document.getElementById('chartDataMarkers').checked;
        var showHSI = document.getElementById('chartShowHSI').checked;
        var animate = document.getElementById('chartAnimate').checked;
        
        var sensors = [
          document.getElementById('TP9').checked,
          document.getElementById('AF7').checked,
          document.getElementById('AF8').checked,
          document.getElementById('TP10').checked,
          document.getElementById('AUXR').checked
          ]; 

        console.log(sensors);

        var reader = new FileReader();
        reader.onload = function(progressEvent){
          var chartOutput = document.getElementById("chartOutput")
          chartOutput.innerHTML = "-Reading data-";
          //Check for MindMonitor CSV header
          var testValue = this.result.substr(0,19);
          console.log(testValue);
          if (testValue!='TimeStamp,Delta_TP9'){
            if (this.result=='' && Math.floor(file.size/1024/1024)>=250){
              chartOutput.innerHTML='<blockquote class="warning">Unable to load file due to low web browser memory. Max file size (using Chrome) is 261MB, this file is '+Math.floor(file.size/1024/1024)+'MB.<br>Please try splitting your CSV into multiple smaller files using this Windows utility <a href="misc/SplitLargeCSV.zip">SplitLargeCSV.zip</a></blockquote>';
            } else {
              chartOutput.innerHTML='<blockquote class="warning">This file is not a Mind Monitor CSV!!.</blockquote>';
            }
            if (this.result.substr(0,2)=="PK"){
              chartOutput.innerHTML='<blockquote class="warning">This file is not a Mind Monitor CSV. It looks like a ZIP file. Please unzip and use the CSV file inside.</blockquote>';
            }
            return;            
          }
          var nanCount=0;
          var bfCount=0;
          
          var lines = this.result.split('\n');
          clearChartData();
          setChartOptions();
          
          annotations = [];
          
          var deltaAve=0;
          var thetaAve=0
          var alphaAve=0;
          var betaAve=0
          var gammaAve=0;

          var deltaAveL=0;
          var thetaAveL=0
          var alphaAveL=0;
          var betaAveL=0
          var gammaAveL=0;

          var deltaAveR=0;
          var thetaAveR=0
          var alphaAveR=0;
          var betaAveR=0
          var gammaAveR=0;                    
          
          var countAve=0;
          
          var renderedPoints = 0;
          
          var lastDate;
          var averageSeconds=false;
        
          var HSIcol = 32;
          var AccCol = 26;
          var RAWCol = 21;
          var GyroCol = -1;
          var BattCol = -1;
          
          var HSIFitOK = true;
          var HSILimit = 4;
          var testVal = 0;
          
          function parseDate(dateString){
            try {
              var dateTimeSplit = dateString.split(' ');
              var dateSplit = dateTimeSplit[0].split('-');
              var timeSplit = dateTimeSplit[1].split(':');
              var secsSplit = timeSplit[2].split('.');
              var ms=0;
              if (secsSplit.length==2){
                ms = parseInt(secsSplit[1]);
              }            
              return new Date(parseInt(dateSplit[0]),parseInt(dateSplit[1])-1,parseInt(dateSplit[2]),parseInt(timeSplit[0]),parseInt(timeSplit[1]),parseInt(secsSplit[0]),ms);
            } catch (err) {
              chartOutput.innerHTML = '<blockquote class="warning">Invalid date detected: "'+dateString+'". Dates should be in the format: "YYYY-MM-DD hh:mm:ss.sss".<br>Did you edit this file in Excel?</blockquote>';
              throw new Error('Error Reading Date');
            }
          }
          
          function processLine(lineIndex) { 
            var values = lines[lineIndex].split(',');
            var dataAddedToChart = false;
            if (values.length>1){//Ignore blanks
              if (lineIndex==0){
                //Header
                RAWAUXRCol = -1;
                for (var i=0;i<values.length;i++){
                  if (values[i]=='HeadBandOn'){
                    HSIcol = i;
                  }
                  if (values[i]=='Accelerometer_X'){
                    AccCol = i;
                  }
                  if (values[i]=='Gyro_X'){
                    GyroCol = i;
                  }
                  if (values[i]=='RAW_TP9'){
                    RAWCol = i;
                  }
                  if (values[i]=='AUX_RIGHT'){
                    RAWAUXRCol = i;
                  }
                  if (values[i]=='Battery'){
                    BattCol = i;
                  }
                }
              } else {
                var thisDate = parseDate(values[0]);
                
                if (averageMinutes){
                  thisDate.setSeconds(0);
                  thisDate.setMilliseconds(0);
                }
                if (averageSeconds){
                  thisDate.setMilliseconds(0);
                }
                                
                if (lineIndex==1){
                  lastDate = thisDate;
                }
                
                if (values[1]==''){
                  //Element
                  if (showDataMarkers){
                    var element = values[values.length-1].trim();//Fix weird EOLs
                    element = element.replace('/muse/elements/','');
                    element = element.replace('/Marker','Marker');
                    if (element!='blink'){
                      var text = element;
                      var marker = element.substr(0,1).toUpperCase();
                      var style = 'point{color:#A00;}'; //Jaw Clench
                      if (element.indexOf('Marker')!=-1){
                        marker = 'M'+element.substr(element.length-1,1);
                        style = 'point{color:#000;}'; //Marker
                      }
                      addAnnotation(style,marker,text); 
                    }
                  }
                } else {
                  if (showHSI){
                    var headbandOn = values[HSIcol]==1?true:false;
                    var HSIDataOK = true;
                    
                    for (var i = HSIcol+1;i<HSIcol+3;i++){
                      if (values[i]>=HSILimit){
                        HSIDataOK = false;
                      }
                    }
                                      
                    var thisHSIFitOK = headbandOn && HSIDataOK;
                    if (HSIFitOK!=thisHSIFitOK){
                      if (thisHSIFitOK){
                        addAnnotation('point{color:#0F0;}','GF','Good Fit');                      
                      } else {
                        addAnnotation('point{color:#F00;}','BF','Bad Fit');
                        bfCount++;
                      }
                    }
                    HSIFitOK = thisHSIFitOK;   
                  }
                  
                  var rawTP9=0;
                  var rawAF7=0;
                  var rawAF8=0;
                  var rawTP10=0;
                  var rawAUXR=0;
                  
                  rawTP9 = parseFloat(values[RAWCol]);
                  rawAF7 = parseFloat(values[RAWCol+1]);
                  rawAF8 = parseFloat(values[RAWCol+2]);
                  rawTP10 = parseFloat(values[RAWCol+3]);
                  if (RAWAUXRCol!=-1){
                    rawAUXR = parseFloat(values[RAWAUXRCol]);
                  }
                  
                  if (isNaN(rawTP9) || isNaN(rawAF7) || isNaN(rawAF7) || isNaN(rawTP10)){
                    addAnnotation('point{color:#F00;}','NaN','Not a Number - Bad Data');
                    nanCount++;
                  }
                  
                  var delta = getValidAverages(1,values,sensors);
                  var theta = getValidAverages(5,values,sensors);
                  var alpha = getValidAverages(9,values,sensors);
                  var beta = getValidAverages(13,values,sensors);
                  var gamma = getValidAverages(17,values,sensors);
                  
                  var deltaL = getValidAveragesLR(1,2,values);
                  var thetaL = getValidAveragesLR(5,6,values);
                  var alphaL = getValidAveragesLR(9,10,values);
                  var betaL = getValidAveragesLR(13,14,values);
                  var gammaL = getValidAveragesLR(17,18,values);
                  
                  var deltaR = getValidAveragesLR(3,4,values);
                  var thetaR = getValidAveragesLR(7,8,values);
                  var alphaR = getValidAveragesLR(11,12,values);
                  var betaR = getValidAveragesLR(15,16,values);
                  var gammaR = getValidAveragesLR(19,20,values);

                  if (averageMinutes || averageSeconds) {
                    countAve++;
                    deltaAve+=delta;
                    thetaAve+=theta;
                    alphaAve+=alpha;
                    betaAve+=beta;
                    gammaAve+=gamma;

                    deltaAveL+=deltaL;
                    thetaAveL+=thetaL;
                    alphaAveL+=alphaL;
                    betaAveL+=betaL;
                    gammaAveL+=gammaL;
                    
                    deltaAveR+=deltaR;
                    thetaAveR+=thetaR;
                    alphaAveR+=alphaR;
                    betaAveR+=betaR;
                    gammaAveR+=gammaR;
                  } else {
                    var gyroX=0;
                    var gyroY=0;
                    var gyroZ=0;
                    
                    if(GyroCol!=-1){
                      gyroX = parseFloat(values[GyroCol]);
                      gyroY = parseFloat(values[GyroCol+1]);
                      gyroZ = parseFloat(values[GyroCol+2]);
                    }
                    
                    var rawTP9=0;
                    var rawAF7=0;
                    var rawAF8=0;
                    var rawTP10=0;
                    var rawAUXR=0;
                    
                    rawTP9 = parseFloat(values[RAWCol]);
                    rawAF7 = parseFloat(values[RAWCol+1]);
                    rawAF8 = parseFloat(values[RAWCol+2]);
                    rawTP10 = parseFloat(values[RAWCol+3]);
                    if (RAWAUXRCol!=-1){
                      rawAUXR = parseFloat(values[RAWAUXRCol]);
                    }
                                        
                    var batt=0;
                    batt = parseFloat(values[BattCol]);
                    if (batt<=0) {batt=null;}
                    
                    var powSum =  Math.pow(10,deScale(delta)) + Math.pow(10,deScale(theta)) + Math.pow(10,deScale(alpha)) + Math.pow(10,deScale(beta)) + Math.pow(10,deScale(gamma));                     
                    var deltaRel = (Math.pow(10,deScale(delta)) / powSum);
                    var thetaRel = (Math.pow(10,deScale(theta)) / powSum);
                    var alphaRel = (Math.pow(10,deScale(alpha)) / powSum);
                    var betaRel = (Math.pow(10,deScale(beta)) / powSum);
                    var gammaRel = (Math.pow(10,deScale(gamma)) / powSum);
                    
                    getAnnotations();
                    chartData.addRow([
                      thisDate,
                      delta,theta,alpha,beta,gamma,
                      deltaRel,thetaRel,alphaRel,betaRel,gammaRel,
                      parseFloat(values[AccCol]),parseFloat(values[AccCol+1]),parseFloat(values[AccCol+2]),
                      gyroX,gyroY,gyroZ,
                      rawTP9,rawAF7,rawAF8,rawTP10,rawAUXR,
                      batt,
                      deltaL,thetaL,alphaL,betaL,gammaL,
                      deltaR,thetaR,alphaR,betaR,gammaR,
                      annotationStyle,annotationMarker,annotationText]);
                    dataAddedToChart = true;
                  }
                  
                  if (lastDate.getTime()>thisDate.getTime()){
                    //data out of order. discard
                    //console.log(thisDate.toISOString(),lastDate.toISOString(),thisDate.getTime()-lastDate.getTime());
                    //debugger;
                  } else if ((averageMinutes || averageSeconds) && thisDate.getTime()!=lastDate.getTime()) {
                    lastDate = thisDate;
                    
                    deltaAve = deltaAve/countAve;
                    thetaAve = thetaAve/countAve;
                    alphaAve = alphaAve/countAve;
                    betaAve = betaAve/countAve;
                    gammaAve = gammaAve/countAve;

                    deltaAveL = deltaAveL/countAve;
                    thetaAveL = thetaAveL/countAve;
                    alphaAveL = alphaAveL/countAve;
                    betaAveL = betaAveL/countAve;
                    gammaAveL = gammaAveL/countAve;
                    
                    deltaAveR = deltaAveR/countAve;
                    thetaAveR = thetaAveR/countAve;
                    alphaAveR = alphaAveR/countAve;
                    betaAveR = betaAveR/countAve;
                    gammaAveR = gammaAveR/countAve;
                    
                    getAnnotations();
                    
                    var gyroX=0;
                    var gyroY=0;
                    var gyroZ=0;
                    
                    if(GyroCol!=-1){
                      gyroX = parseFloat(values[GyroCol]);
                      gyroY = parseFloat(values[GyroCol+1]);
                      gyroZ = parseFloat(values[GyroCol+2]);
                    }
                    
                    var batt=0;
                    batt = parseFloat(values[BattCol]);
                    if (batt<=0) {batt=null;}
                    
                    var powSum =  Math.pow(10,deScale(deltaAve)) + Math.pow(10,deScale(thetaAve)) + Math.pow(10,deScale(alphaAve)) + Math.pow(10,deScale(betaAve)) + Math.pow(10,deScale(gammaAve));                     
                    var deltaRel = (Math.pow(10,deScale(deltaAve)) / powSum);
                    var thetaRel = (Math.pow(10,deScale(thetaAve)) / powSum);
                    var alphaRel = (Math.pow(10,deScale(alphaAve)) / powSum);
                    var betaRel = (Math.pow(10,deScale(betaAve)) / powSum);
                    var gammaRel = (Math.pow(10,deScale(gammaAve)) / powSum);
                                        
                    chartData.addRow([thisDate,
                      deltaAve,thetaAve,alphaAve,betaAve,gammaAve,
                      deltaRel,thetaRel,alphaRel,betaRel,gammaRel,
                      parseFloat(values[AccCol]),parseFloat(values[AccCol+1]),parseFloat(values[AccCol+2]),
                      gyroX,gyroY,gyroZ,
                      rawTP9,rawAF7,rawAF8,rawTP10,rawAUXR,
                      batt,
                      deltaAveL,thetaAveL,alphaAveL,betaAveL,gammaAveL,
                      deltaAveR,thetaAveR,alphaAveR,betaAveR,gammaAveR,
                      annotationStyle,annotationMarker,annotationText]);
                      
                    dataAddedToChart = true;
                    renderedPoints++;
                                      
                    deltaAve=0;
                    thetaAve=0
                    alphaAve=0;
                    betaAve=0
                    gammaAve=0;
                    
                    deltaAveL=0;
                    thetaAveL=0
                    alphaAveL=0;
                    betaAveL=0
                    gammaAveL=0;
                    
                    deltaAveR=0;
                    thetaAveR=0
                    alphaAveR=0;
                    betaAveR=0
                    gammaAveR=0;
                    
                    countAve=0;
                  }
                  
                }
              }
            }
            if (animate){
              var animateDelay;
              if (dataAddedToChart){
                animateDelay = document.getElementById('chartAnimateDelay').value;
              } else {
                animateDelay = 0;
              }            
              timedFunc = setTimeout(function () {
                while(chartData.getNumberOfRows()>100) {
                  chartData.removeRow(0);
                }
                if (dataAddedToChart){
                  var percent = Math.round((lineIndex/lines.length)*1000)/10; 
                  chartOutput.innerHTML = 'Processing data points: '+ lines[lineIndex].split(',')[0] +' ['+lineIndex+'/'+lines.length + '] ' + percent +'%';
                  chart.draw(chartView, chartOptions);
                }
                lineIndex++;
                if (lineIndex<lines.length){
                  processLine(lineIndex);
                } else {
                  drawComplete(lines.length);
                }
              }, animateDelay);
            }
          }
          
          function drawComplete(lineCount, nanCount, bfCount){
            chart.draw(chartView, chartOptions);
            chartOutput.innerHTML = 'Chart Complete: '+lines.length+' data points.'
            if (averageSeconds && !averageMinutes){
              chartOutput.innerHTML += ' '+renderedPoints+' seconds.';
            }
            if (averageMinutes) {
              chartOutput.innerHTML+=' '+renderedPoints+' minutes.';
              if (renderedPoints<10){
                chartOutput.innerHTML+='<blockquote class="warning">Not enough minutes of data to properly graph, please unselect "Average Minutes" to show each data point.</blockquote>';
              }
            }
            if (((averageMinutes || averageSeconds) && nanCount>1) || (!averageMinutes && nanCount>10)){
              chartOutput.innerHTML+='<blockquote class="warning">NaN (Not a Number) data found. This recording may be bad. Please check "All Data Points" "RAW" view to confirm.<br/>NaN values are sent from the Interaxon SDK when there is bad bluetooth packet data. This can mean your device is incompatible with the Muse, that your Muse is suffering from Bluetooth interference, or that another high bandwidth Bluetooth device (e.g. headphones) is preventing data from being received from the Muse.<br>[NaN Count:'+nanCount+' ('+Math.round(nanCount/lines.length*100)+'% of data).]</blockquote>';
            }
            if (bfCount>2){
              chartOutput.innerHTML+='<blockquote class="warning">'+bfCount+' Bad Fit (BF) data markers found; This recording may be bad. While this session was recorded the data quality dropped below the minimum requirements. This is shown in the chart with the marker "BF" for "Bad Fit". When data quality is restored you will see "GF" for "Good Fit". You may also see "J" for "Jaw Clench" which also indicates an area of bad data quality. Ideally, for good data quality you want your RAW EEG to have only a 50uV difference between the minimum and maximum values, with large spikes only when blinking. This is shown in <a href="img/mm-screen-3.jpg">this screenshot.</a></blockquote>';
            }
            updateImageShareLink();

            //Render average session gagues
            var groupData = google.visualization.data.group(chartData, [{column: 0, modifier: function () {return '';}, type: 'string'}],
              [
              {'column':1, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Delta'},
              {'column':2, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Theta'},
              {'column':3, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Alpha'},
              {'column':4, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Beta'},
              {'column':5, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Gamma'},
              
              {'column':6, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Delta Rel'},
              {'column':7, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Theta Rel'},
              {'column':8, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Alpha Rel'},
              {'column':9, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Beta Rel'},
              {'column':10, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Ave Gamma Rel'},
              
              {'column':23, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Delta Left'}, //Left
              {'column':24, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Theta Left'},
              {'column':25, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Alpha Left'},
              {'column':26, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Beta Left'},
              {'column':27, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Gamma Left'},
              
              {'column':28, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Delta Right'}, //Right
              {'column':29, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Theta Right'},
              {'column':30, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Alpha Right'},
              {'column':31, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Beta Right'},
              {'column':32, 'aggregation': google.visualization.data.avg, 'type': 'number', 'label': 'Gamma Right'}
              ]);                                                                                                    
             
            gaugeDataSessionAbs = google.visualization.arrayToDataTable([
              ['Label', 'Value'],
              [groupData.getColumnLabel(1), groupData.getValue(0,1)],//Delta
              [groupData.getColumnLabel(2), groupData.getValue(0,2)],//Theta
              [groupData.getColumnLabel(3), groupData.getValue(0,3)],//Alpha
              [groupData.getColumnLabel(4), groupData.getValue(0,4)],//Beta
              [groupData.getColumnLabel(5), groupData.getValue(0,5)]//Gamma
            ]);
            gaugesSessionAbs.draw(gaugeDataSessionAbs, gaugeOptionsAbs);
            
            gaugeDataSessionRel = google.visualization.arrayToDataTable([
              ['Label', 'Value'],
              [groupData.getColumnLabel(6), groupData.getValue(0,6)],//Delta
              [groupData.getColumnLabel(7), groupData.getValue(0,7)],//Theta
              [groupData.getColumnLabel(8), groupData.getValue(0,8)],//Alpha
              [groupData.getColumnLabel(9), groupData.getValue(0,9)],//Beta
              [groupData.getColumnLabel(10), groupData.getValue(0,10)]//Gamma
            ]);
            gaugesSessionRel.draw(gaugeDataSessionRel, gaugeOptionsRel);
            
            //Bar chart session
            barOptions.title = 'Session Average Absolute\nLeft vs Right';
            
            minBarValue = 0;
            for(var x=0;x<10;x++){
              minBarValue = Math.min(minBarValue,groupData.getValue(0,6+x));
            }
            if (minBarValue>0){ minBarValue = 0 };
            if (minBarValue<0){ minBarValue = -minBarValue };
            minBarValue = minBarValue + 0.1;//Just to show that the zeroed relative min value isn't actually zero.
            
            barDataSession = google.visualization.arrayToDataTable([
              ['Label', 'Ave Left', 'Ave Right'],
              ['Delta', -(minBarValue+groupData.getValue(0,10+1)), minBarValue+groupData.getValue(0,15+1)],
              ['Theta', -(minBarValue+groupData.getValue(0,10+2)), minBarValue+groupData.getValue(0,15+2)],
              ['Alpha', -(minBarValue+groupData.getValue(0,10+3)), minBarValue+groupData.getValue(0,15+3)],
              ['Beta', -(minBarValue+groupData.getValue(0,10+4)), minBarValue+groupData.getValue(0,15+4)],
              ['Gamma', -(minBarValue+groupData.getValue(0,10+5)), minBarValue+groupData.getValue(0,15+5)]
            ]);
            
            barSession.draw(barDataSession, barOptions);
          }
          
          if (animate){
            setTimeout(function () { processLine(0);}, 100);
          } else {
            //Detect large 220/256Hz files
            if (lines.length>1000){
              var firstDate = parseDate(lines[1].split(',')[0]);
              var lastDate = parseDate(lines[lines.length-2].split(',')[0]);
              console.log(firstDate,lastDate);
              var secsDiff = (lastDate.getTime()-firstDate.getTime())/1000;
              var dataFreq = lines.length/secsDiff;
              if (dataFreq>200){
                averageSeconds = true;
              }
            }
             
            for(var lineIndex = 0; lineIndex < lines.length; lineIndex++){
              processLine(lineIndex);
            }
            
            drawComplete(lines.length, nanCount, bfCount);
          }
        };
        reader.readAsText(file);
      };

      function addAnnotation(style,marker,text){
        for (var i=0;i<annotations.length;i++){
          if (annotations[i][0]==style && annotations[i][1]==marker && annotations[i][2]==text){
            return;
          }
        }
        annotations.push([style,marker,text]);        
      }

      function getAnnotations(){
        annotationStyle = null;
        annotationMarker = null;
        annotationText = null;
        if (annotations.length>0){
          annotationStyle = annotations[0][0];
          annotationMarker = annotations[0][1];
          annotationText = annotations[0][2];
          if (annotations.length>1){
            for (var i=1;i<annotations.length;i++){
              annotationStyle = 'point{color:#F00;}';
              annotationMarker += ','+annotations[i][1];
              annotationText += ', '+annotations[i][2];
            }
          }
          annotations = [];
        }
      }
      
      function arrayEqual(arr1,arr2) {
        if (arr1.length!=arr2.length){
          return false;
        }
        for (var i=0;i<arr1.length;i++){
          if (arr1[i]!=arr2[i]){
            return false;
          }  
        }
        return true;
      }
      
      function getValidAverages(startIndex, values, sensors){
        var count=0;
        var sum=0;
        for (var i=startIndex;i<startIndex+4;i++){
          if (!sensors[i-startIndex]){
            continue;
          }
          var val = parseFloat(values[i]);
          if (!isNaN(val)){
            sum+=val;
            count++;
          }
        }
        if (count==0){
          return null;
        }
        return scaleBrainwaves(sum/count);
      }

      function getValidAveragesLR(col1,col2,values){
        var count=0;
        var sum=0;

        var val = parseFloat(values[col1]);
        if (!isNaN(val)){
          sum+=val;
          count++;
        }

        var val = parseFloat(values[col2]);
        if (!isNaN(val)){
          sum+=val;
          count++;
        }

        if (count==0){
          return null;
        }
        return scaleBrainwaves(sum/count);
      }
      
      function scaleBrainwaves(value){
        return (value+1)*50;
      }
      
      function deScale(value){
        return (value/50)-1;
      }
      
      function updateChartOptions(file){
        setChartOptions();
        chart.draw(chartView, chartOptions);
        if (file.length>0){
        // if (document.getElementById('CSVFile').files.length>0){
          updateImageShareLink();
        }
      }
      
      function reloadChartData(file){
        clearTimeout(timedFunc);
        var animate = document.getElementById('chartAnimate').checked;
        document.getElementById("chartAnimateDelay").disabled = !animate;
        var elements = document.getElementsByName("cas");
        for (var i=0;i<elements.length;i++){
          elements[i].disabled = !animate;
        }
        
        if (file.length>0){
        // if (document.getElementById('CSVFile').files.length>0){
          readInputFile(file[0]);
        }
      }
      function setSpeedSelect(value){
        document.getElementById("chartAnimateDelay").value = value;
      }
      function setSpeedRadio(value){
        var elements = document.getElementsByName("cas");
        for (var i=0;i<elements.length;i++){
          if (elements[i].value == value) {
            elements[i].checked = true;          
          }
        }
      }
    </script>
  </head>

  <body id="page-top">
    
    <section class="patterned">
      <div class="container">
        <h2>EEG Visualization</h2>
        <!--<p>Select Mind Monitor CSV recording: <input id="CSVFile1" type="file" class="btn btn-outline btn-xl inline" name="CSV File"/></p>-->
        <?php 
          
          //$recentRec = "SELECT csvFilePath FROM userrecords ORDER BY user_id DESC LIMIT 1";
          // $resultAll = mysqli_query($con, "SELECT csvFilePath FROM userrecords ORDER BY user_id DESC LIMIT 1");
          // if(!$resultAll){
          //   die(mysqli_error($con));
          // }

          // if (mysqli_num_rows($resultAll) > 0) {
          //   while($rowData = mysqli_fetch_array($resultAll)){
          //       $CSVFile = $rowData["csvFilePath"];
          //       //echo $rowData["csvFilePath"].'<br>';
          //       //echo "<h2>" . $recordCSV . "</h2>";
          //   }
          //   //echo "<h2>" . $CSVFile . "</h2>";
          // }
          

        
        ?>

        <!---------------------------------------------------------------------------------------------------->

        <?php 
            //This query of lates file from db
            // $latefile = "SELECT 
            //                 <table_name_of_file_names_column> 
            //             FROM 
            //                 <table_name> 
            //             ORDER BY
            //                 <table_primary_key> DESC LIMIT 1";
            // $example = "SELECT 
            //                 csvFilePath
            //             FROM
            //             userrecords
            //             ORDER BY
            //             user_id DESC LIMIT 1";

            // get file name from the query and set  a variable
            // $file_name = 'C:\/xampp\/htdocs\/api\/index.php';

          //$recentRec = "SELECT csvFilePath FROM userrecords ORDER BY user_id DESC LIMIT 1";
          $resultAll = mysqli_query($con, "SELECT csvFilePath FROM userrecords ORDER BY user_id DESC LIMIT 1");
          if(!$resultAll){
            die(mysqli_error($con));
          }

          if (mysqli_num_rows($resultAll) > 0) {
            while($rowData = mysqli_fetch_array($resultAll)){
                $CSVFile = $rowData["csvFilePath"];
                //echo $rowData["csvFilePath"].'<br>';
                //echo "<h2>" . $recordCSV . "</h2>";
            }
            //echo "<h2>" . $CSVFile . "</h2>";
          }

          //echo "<h2>" . $CSVFile . "</h2>";
          $file_name = $CSVFile;
          //$file_name = 'museMonitor_2021-09-11--01-25-58_9145199830808311087.csv';
          echo "<h2>" . $file_name . "</h2>";
          
        ?>


        <script type="text/javascript">
        $( window ).on( "load", function() {

            var db_file = '<?php echo $file_name; ?>';
            const  fileInput = document.getElementById('CSVFile');
            const dataTransfer = new DataTransfer()
            
            //const file = new File(['Hello world!'], db_file, {type: 'text/plain'})
            const file = new File(['Hello world!'], db_file, {type: 'application/vnd.ms-excel'})
            dataTransfer.items.add(file)
            fileInput.files = dataTransfer.files
            console.log(fileInput);
            console.log(fileInput.files[0]);
            readInputFile(fileInput.files[0])
            updateChartOptions(fileInput.files);
            reloadChartData(fileInput.files);
        })

        </script>

        <form action="charts1.php" method="post" enctype='multipart/form-data' >

        <input type="file" id="CSVFile" name="CSVFile" />
        <input type="submit" value="submit">

        <input type="text" oninput="drop()" id="db_file" value="">
        </form>
        


        <!---------------------------------------------------------------------------------------------------->

        
        <!--<p>Select Mind Monitor CSV recording: <input id="CSVFile" type="file" class="btn btn-outline btn-xl inline" name="CSV File"/></p>-->
        <blockquote>
          <div class="table-responsive">
            <table class="chartOptions">
            <tr>
              <td><input type="radio" name="chartAverageMinutes" checked="checked" value="1" onClick="reloadChartData();"/>  Average Minutes</td>
              <td><input name="chartSeries" type="radio" value="Absolute" checked="checked" onClick="updateChartOptions();"/> Absolute Brainwaves</td>
              <td><select id="yAxisMax" onClick="updateChartOptions();">
                <option value="automatic" selected="selected">Auto</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                </select> Y Axis Max</td>
              <td><input id="chartAnimate" type="checkbox" checked="checked" onClick="document.getElementsByName('chartAverageMinutes')[this.checked?1:0].checked=true;reloadChartData();"/> Animate</td>
            </tr>
            <tr>
              <td><input type="radio" name="chartAverageMinutes" value="0" onClick="reloadChartData();"/> All Data Points</td>
              <td><input name="chartSeries" type="radio" value="Relative" onClick="updateChartOptions();"/> Relative Brainwaves</td>
              <td><select id="yAxisMin" onClick="updateChartOptions();">
                <option value="automatic" selected="selected">Auto</option>
                <option value="-1">-1</option>
                <option value="-2">-2</option>
                <option value="-3">-3</option>
                <option value="-4">-4</option>
                </select> Y Axis Min</td>
              <td><select id="chartAnimateDelay" onClick="setSpeedRadio(this.value);">
                <option value="0">0ms</option>
                <option value="1">1ms</option>
                <option value="10" selected="selected">10ms</option>
                <option value="50">50ms</option>
                <option value="100">100ms</option>
                <option value="200">200ms</option>
                <option value="400">400ms</option>
                <option value="600">600ms</option>
                <option value="800">800ms</option>
                <option value="1000">1Sec</option>
                </select> Loading Delay</td>
            </tr>
            <tr>
              <td></td>
              <td><input name="chartSeries" type="radio" value="Acc" onClick="updateChartOptions();"/> Accelerometer</td>
              <td></td>
              <td>
                <input type="radio" name="cas" value="0" onClick="setSpeedSelect(this.value);"/>
                <input type="radio" name="cas" value="1" onClick="setSpeedSelect(this.value);"/>
                <input type="radio" name="cas" value="10" checked="checked" onClick="setSpeedSelect(this.value);"/>
                <input type="radio" name="cas" value="50" onClick="setSpeedSelect(this.value);"/>
                <input type="radio" name="cas" value="100" onClick="setSpeedSelect(this.value);"/>
                <input type="radio" name="cas" value="200" onClick="setSpeedSelect(this.value);"/>
                <input type="radio" name="cas" value="400" onClick="setSpeedSelect(this.value);"/>
                <input type="radio" name="cas" value="600" onClick="setSpeedSelect(this.value);"/>
                <input type="radio" name="cas" value="800" onClick="setSpeedSelect(this.value);"/>
                <input type="radio" name="cas" value="1000" onClick="setSpeedSelect(this.value);"/></td>
            </tr>
            <tr>
              <td></td>
              <td><input name="chartSeries" type="radio" value="Gyro" onClick="updateChartOptions();"/> Gyro</td>
              <td><input id="chartDataMarkers" data-toggle="tooltip" data-html="true" title="J - Jaw Clench<br>NaN - Not a Number (Bad Data)<br>M[1-5] - Manual Markers (Enabled in Advanced Settings in the App)" type="checkbox" checked="checked" onClick="reloadChartData();"/> Data Markers</td>
              <td></td>
            </tr>
            <tr>
              <td><input id="Delta" type="checkbox" checked="checked" onClick="reloadChartData();"/>Delta <input id="Theta" type="checkbox" checked="checked" onClick="reloadChartData();"/>Theta <input id="Alpha" type="checkbox" checked="checked" onClick="reloadChartData();"/>Alpha <input id="Beta" type="checkbox" checked="checked" onClick="reloadChartData();"/>Beta <input id="Gamma" type="checkbox" checked="checked" onClick="reloadChartData();"/>Gamma</td>
              <td><input name="chartSeries" type="radio" value="RAW" onClick="updateChartOptions();"/> RAW</td>
              <td><input id="chartShowHSI" data-toggle="tooltip" data-html="true" title="GF - Good Fit<br/>BF - Bad Fit" type="checkbox" checked="checked" onClick="reloadChartData();"/> Show HSI</td>
              <td></td>
            </tr>
            <tr>
              <td><input id="TP9" data-toggle="tooltip" title="Left Ear Electrode" type="checkbox" checked="checked" onClick="reloadChartData();"/>TP9
              <input id="AF7" data-toggle="tooltip" title="Front Left Electrode" type="checkbox" checked="checked" onClick="reloadChartData();"/>AF7
              <input id="AF8" data-toggle="tooltip" title="Front Right Electrode" type="checkbox" checked="checked" onClick="reloadChartData();"/>AF8
              <input id="TP10" data-toggle="tooltip" title="Right Ear Electrode" type="checkbox" checked="checked" onClick="reloadChartData();"/>TP10
              <input id="AUXR" data-toggle="tooltip" data-html="true" title="Right Auxiliary USB Electrode.<br/><br/>Only enable this if you are using an auxiliary electrode plugged into the right USB port of a Muse 2/S." type="checkbox" checked="checked" onClick="reloadChartData();"/>AUX-R
              </td>
              <td><input name="chartSeries" type="radio" value="Battery" onClick="updateChartOptions();"/> Battery</td>
              <td><input id="chartSmooth" type="checkbox" checked="checked" onClick="updateChartOptions();"/> Smooth Curve</td>
              <td></td>
            </tr>
            </table>
          </div>
        </blockquote>
        <p><span id="chartOutput"></span> <span id='png'></span></p>
        <p><div id="museChart" style="height: 600px"></div></p>
        <p style="clear:both;">
          <div>
            <div id="museBarChartSession" class="museBarChart"></div>
            <div id="museGaugesSessionAbs" class="museGauge"></div>
            <div id="museGaugesSessionRel" class="museGauge"></div>
          </div>
          <div>
            <div id="museBarChartPoint" class="museBarChart"></div>
            <div id="museGaugesPoint" class="museGauge"></div>
          </div>
        </p>
        </div>
    </section>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Theme JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="custom/custom.js"></script>
    <script>
    <!-- Google Charts -->
    //document.getElementById('CSVFile').onchange = function(){readInputFile(this.files[0]);};
    <!--- Bootstrap tooltip -->
    //$('[data-toggle="tooltip"]').tooltip();
    </script>
  </body>
</html>