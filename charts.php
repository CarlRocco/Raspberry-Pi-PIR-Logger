<html>
<head>
  <title>PIR Logger</title>
  <div style="background-color:darkblue">
    <h1 style="margin-left: 20px"><font color="white">PIR Logger Data</font></h1>
  </div>
</head>

  <body>
     <?php
     if($_SERVER["REQUEST_METHOD"] == "POST")
     {
       $startDate = $_POST["startDate"];
       $startTime = $_POST["startTime"];
       $stopDate = $_POST["stopDate"];
       $stopTime = $_POST["stopTime"];
       $startDT = $startDate." ".$startTime;
       $stopDT = $stopDate." ".$stopTime;
       echo "<h3>PIR Log Data from ".$startDT." to ".$stopDT."</h3>";
     }
     ?>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
   
      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart', 'line']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Time');
        data.addColumn('number', 'DDM');
        data.addRows([
	<?php
	    $db = new PDO('sqlite:PIRLog.db');
	    $result = $db->query("SELECT * FROM ILSData WHERE DATETIME BETWEEN '".$startDT."' AND '".$stopDT."'");
	    
	    //$csvFileName = $startDT."_".$stopDT;
	    $csvFile = fopen("PIRLog.csv", "w");
	    // Header
	    $header = "Date Time,DDM,RF_LVL,SDM,Mod90Hz,Mod150Hz\n";
	    fwrite($csvFile, $header);
	    
	    foreach($result as $row)
	    {
	       print "['".$row['DATETIME']."',".$row['DDM']."],";
	       fwrite($csvFile, $row['DATETIME'].",".$row['DDM'].",".$row['RF'].",".$row['SDM'].",".$row['MOD90'].",".$row['MOD150']."\n");
	    }
	    fclose($csvFile);	    
	?>
        ]);

        // Set chart options
        var options = 
	{		
	   'title':'DDM',
	    hAxis:{
	       title: 'Time',
	       showTextEvery: 5,
	       slantedText:true, 
	       slantedTextAngle:90,
	    },
	    vAxis:{
	       title: 'DDM'
	    },
	 };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
     <?php
	 echo "Click <a href='PIRLog.csv'>here</a> to download the .csv file<br><br>";
     ?>

    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>
