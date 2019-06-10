<html>
<head>
  <title>PIR Logger</title>
  <div style="background-color:darkblue">
    <h1 style="margin-left: 20px"><font color="white">PIR Logger</font></h1>
  </div><br>

  <style>
    th {
      text-align: left;
    }
  </style>
</head>
<body>

<div id="data">
  <table style="border: 1">
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
</div>

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
