<html>

<head>

  <title>PIR Logger-Main</title>

  <div style="background-color: #4CAF50; height: 40px;">

    <h1 style="margin-left: 20px;"><font color="white">PIR Logger Main</font></h1>

  </div><br>



  <style>

    table {      

      width: 75%;

      background-color: gainsboro;

    }

    th {

      text-align: left;

      height: 30px;

      color: white;

      background-color: #4CAF50;

    }

    td {

      font-weight: bold;

      color: blue;

    }

    table,th,td{

      padding-left: 5px;

      border: 1px solid #0000;

    }

  </style>

</head>

<body>



<div id="data">

  <table>

    <tr>

      <th>Date Time (UTC)</th>

      <th>DDM</th>

      <th>RF Lvl</th>

      <th>SDM</th>

      <th>90Mod</th>

      <th>150Mod</th>

      <th>Alarm</th>

    </tr>

    <tr>

      <td width='200' id='dt'>Loading..</td>

      <td width='80' id='ddm'>Loading..</td>

      <td width='80' id='rfLevel'>Loading..</td>

      <td width='80' id='sdm'>Loading..</td>

      <td width='80' id='mod90'>Loading..</td>

      <td width='80' id='mod150'>Loading..</td>

      <td width='80' id='alarm'>Loading..</td>

    </tr>

  </table>

</div><br>



<script>

const renderTableData = (data) => {

  console.log(data)

  for (let [key, val] of Object.entries(data)) {

    document.getElementById(key).textContent = val

  }

}



function showPIRData() {

  fetch('ILSDataAJAX.php')

    .then(response => response.json())

    .then(renderTableData)

    // .then(responseText => {

    //   document.getElementById("data").innerHTML = responseText;

    // })

}

// timer to update the latest PIR data in the "data" <div>

window.setInterval(showPIRData, 1000);

</script>



<!--<form action="charts.php" method="post" target="blank">-->

<form action="<?php echo htmlspecialchars($SERVER["PHP_SELF"]);?>" method="POST" style="width: 75%">

  <fieldset>

    <legend>Data Query</legend>

      <p>

        Start Date: <input type="date" name="startDate" required value="<?php echo $startDate;?>">

        Start Time (UTC): <input type="text" name="startTime" required step="1" placeholder="hh:mm:ss"value="<?php echo $startTime;?>">

      </p>

      <p>

        Stop Date: <input type="date" name="stopDate" required value="<?php echo $stopDate;?>">

        Stop Time (UTC): <input type="text" name="stopTime" required placeholder="hh:mm:ss" step="1" value="<?php echo $stopTime;?>">

      </p>

      <p><input type="submit" name="submit" value="Create .csv File"></p>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST")

{

 $startDate = $_POST["startDate"];

 $startTime = $_POST["startTime"];

 $stopDate = $_POST["stopDate"];

 $stopTime = $_POST["stopTime"];

 $startDT = $startDate." ".$startTime;

 $stopDT = $stopDate." ".$stopTime;

 echo "<h3>PIR Log Data<br>From: <font color='blue'>".$startDT."</font><br>To: <font color='blue'>".$stopDT."</font></h3>";

}



$db = new PDO('sqlite:PIRLog.db');

$result = $db->query("SELECT * FROM ILSData WHERE DATETIME BETWEEN '".$startDT."' AND '".$stopDT."'");



//$csvFileName = $startDT."_".$stopDT;

$csvFile = fopen("PIRLog.csv", "w");

// Header

$header = "Date Time,DDM,RF_LVL,SDM,Mod90Hz,Mod150Hz\n";

fwrite($csvFile, $header);



foreach($result as $row)

{

   #print "['".$row['DATETIME']."',".$row['DDM']."],";

   fwrite($csvFile, $row['DATETIME'].",".$row['DDM'].",".$row['RF'].",".$row['SDM'].",".$row['MOD90'].",".$row['MOD150']."\n");

}

fclose($csvFile);	    

echo "Click <a href='PIRLog.csv'>here</a> to download the most recent .csv file<br><br>";

?>

  </fieldset>

</form>



<a href="setup.php" target="blank">Logger Setup</a><br>



<!--Write CSV File-->





</body>



</html>

