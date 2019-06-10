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
		
		echo "
			<table border=1 >
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
					<td width='200'>$dt</td>
					<td width='80'>$ddm</td>
					<td width='80'>$rf</td>
					<td width='80'>$sdm</td>
					<td width='80'>$mod90</td>
					<td width='80'>$mod150</td>
					<td width='80'>$alarm</td></tr>
				</tr>
			</table>
		";
?>
