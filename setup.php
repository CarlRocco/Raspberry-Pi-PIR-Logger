<!DOCTYPE HTML>
<html>
<head>
  <title>PIR Logger</title>
  <div style="background-color:darkblue">
    <h1 style="margin-left: 20px"><font color="white">PIR Logger Setup</font></h1>
  </div>
</head>
<body>

<?php
$db = new PDO('sqlite:PIRLog.db');
if($_SERVER["REQUEST_METHOD"] == "POST")
{
   $rfLimit = $_POST["rflim"];
   $ddmLimit = $_POST["ddmlim"];
   $sdmLimit = $_POST["sdmlim"];
   $logging = $_POST["logging"];
   $interval = $_POST["interval"];
   $db->exec("UPDATE Settings SET 'lowRF'=".$rfLimit.", 'ddmLimit'=".$ddmLimit.", 'sdmLimit'=".$sdmLimit.", 'running'=".$logging.", 'logInterval'=".$interval);
  /*echo "<h2>Output</h2>";
  echo "RF Level Limit: ".$rfLimit."<br>";
  echo "UPDATE Settings SET 'lowRF'=".$rfLimit.", 'ddmLimit'=".$ddmLimit.", 'sdmLimit'=".$sdmLimit."<br>";
  echo "logging: ".$logging;

  $result = $db->query("SELECT * FROM Settings");	    
  foreach($result as $row)
  {
    $rfLimit = $row['lowRF'];
    $ddmLimit = $row['ddmLimit'];
    $sdmLimit = $row['sdmLimit'];
    
  }*/
}
$result = $db->query("SELECT * FROM Settings");
	    
	    foreach($result as $row)
	    {
        $running = "";
        if($row['running']==1){
          $running = "True";
        }else{
          $running = "False";
        }
        $receiving = "";
        if($row['receivingData']==1){
          $receiving = "True";
        }else{
          $receiving = "False";
        }
        $logInterval = $row['logInterval'];
        $rfLimit = $row['lowRF'];
        $ddmLimit = $row['ddmLimit'];
        $sdmLimit = $row['sdmLimit'];      
      }
?> 

<form method="post" action="<?php echo htmlspecialchars($SERVER["PHP_SELF"]);?>" method="POST">
  <table border=1>
    <tr><td width=200 style="background-color:darkblue"><font color="white">Logging Data:</font></td><td width=100><label id="lblRun" type="text" name="running"><?php echo $running;?></label>
    <?php //initial text for start/stop button
      $btnText = "";
      if($logging){
        $btnText = "Stop";
      }else{
        $btnText = "Start";
      }
    ?>
    </td><td><button type="submit" id="btnStartStop" name="btnStartStop" onclick="toggleButton()" style="width:100%" value="test"><?php echo $btnText;?></button></td></tr>
    <tr><td style="background-color:darkblue"><font color="white">Log Interval:</font></td><td><input type="number" name="interval" value="<?php echo $logInterval;?>"></td></tr>
    <tr><td style="background-color:darkblue"><font color="white">Low RF Level Alarm Limit:</font></td><td><input type="number" name="rflim" value="<?php echo $rfLimit;?>"></td></tr>
    <tr><td style="background-color:darkblue"><font color="white">DDM Alarm Limit:</font></td><td><input step=".001" type="number" name="ddmlim" value="<?php echo $ddmLimit;?>"></td></tr>
    <tr><td style="background-color:darkblue"><font color="white">SDM Alarm Limit:</font></td><td><input type="number" name="sdmlim" value="<?php echo $sdmLimit;?>"></td></tr>
    <tr><td></td><td></td><td><input type="submit" name="submit" value="Submit"></td></tr>
  </table>       
  <input id="log" type="hidden" name="logging" value=<?php echo $logging;?> >       
</form>

<script>
  function toggleButton(){
    var b = document.getElementById("btnStartStop");
    var l = document.getElementById("lblRun");
    if(b.innerHTML=="Start"){
      //b.innerHTML = "Stop";
      //l.innerHTML = "True";
      document.getElementById("log").value = 1;
    }else{
      //b.innerHTML = "Start";
      //l.innerHTML = "False";
      document.getElementById("log").value = 0;
    }
  }
  

</script>
</body>
</html>
