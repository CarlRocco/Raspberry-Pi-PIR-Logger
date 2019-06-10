<?php
	ini_set('display_errors', 'On');

	//open the database
	$db = new PDO('sqlite:PIRLog.db');
	
	//query the ILS Data table and get the last entry
	$result = $db->query("SELECT * FROM ILSData ORDER BY ID DESC LIMIT 1");
	
	//populate the variables
	$row = $result->fetch();

		// close the database connection
	$db = NULL;

	echo json_encode([
		'dt' => $row['DATETIME'],
		'ddm' => $row['DDM'],
		'rfLevel' => $row['RF'],
		'sdm' => $row['SDM'],
		'mod90' => $row['MOD90'],
		'mod150' => $row['MOD150'],
		'alarm' => $row['ALARM']
	]);
?>
