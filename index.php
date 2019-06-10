<html>
<head>
  <title>PIR Logger</title>
  <div style="background-color:darkblue">
    <h1 style="margin-left: 20px"><font color="white">PIR Logger</font></h1>
  </div><br>
</head>
<body>

<div id="data"></div>
<script>
function showPIRData() {
  fetch('ILSDataAJAX.php')
    .then(response => response.text())
    .then(responseText => {
      document.getElementById("data").innerHTML = responseText;
    })
}
// timer to update the latest PIR data in the "data" <div>
window.setInterval(showPIRData, 1000);
</script>

<form action="charts.php" method="post" target="blank">
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
      <p><input type="submit" name="submit" value="Submit"></p>
  </fieldset>
</form>
<a href="setup.php" target="blank">Logger Setup</a><br>
</body>
</html>
