<?php
	//open the database
    $db = new PDO('sqlite:PIRLog.db');
    
    //query the ILS Data table and get the last entry
    $result = $db->query("SELECT * FROM ILSData ORDER BY ID DESC LIMIT 1");
    
    //populate the variables
    $dt = "";
    $ddm = $sdm = $rf = $mod90 = $mod150 = $alarm = 0;
    foreach($result as $row)
    {
		$dt = $row['DATETIME'];
		$rf = $row['RF'];
		$ddm = $row['DDM'];
		$sdm = $row['SDM'];
		$mod90 = $row['MOD90'];
		$mod150 = $row['MOD150'];
		$alarm = $row['ALARM'];
    }
    
     // close the database connection
    $db = NULL;   
    
    echo "<table border=1 >";
    echo "<tr><td>Date Time (UTC)</td><td>DDM</td><td>RF Lvl</td><td>SDM</td><td>90Mod</td><td>150Mod</td><td>Alarm</td></tr>";
	echo "<td width='200'>".$dt."</td>";
	echo "<td width='80'>".$ddm."</td>";
	echo "<td width='80'>".$rf."</td>";
	echo "<td width='80'>".$sdm."</td>";
	echo "<td width='80'>".$mod90."</td>";
	echo "<td width='80'>".$mod150."</td>";
	echo "<td width='80'>".$alarm."</td></tr>";
    echo "</table><br><br>";
    
?>
