<?php

$db = new mysqli('db', 'db', 'db', 'db');

$tables = [
	'tx_entity_domain_model_entity',
	'pages',
	'tt_content',
	'tx_news_domain_model_news',
	'tt_address',
];

foreach($tables as $table) {
	$query = 'SELECT mm.uid_local, mm.uid_foreign
		FROM sys_category_record_mm mm
			 INNER JOIN sys_category c ON mm.uid_local = c.uid
			 INNER JOIN ' . $table . ' f ON mm.uid_foreign = f.uid
		WHERE c.sys_language_uid = 1
			AND f.sys_language_uid = 0
			AND mm.tablenames = "' . $table . '"';

	$result = $db->query($query);

	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$removeQuery = 'DELETE FROM sys_category_record_mm 
       WHERE uid_local = ' . $row['uid_local'] . ' 
				AND uid_foreign = ' . $row['uid_foreign'] . '
				AND tablenames = "' . $table . '"';
			$db->query($removeQuery);
		}
	}
}