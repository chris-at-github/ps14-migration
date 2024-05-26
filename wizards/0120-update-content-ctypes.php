<?php

$db = new mysqli('db', 'db', 'db', 'db');

$query = 'UPDATE tt_content SET 
		CType = "ps14_hero",
    image = tx_xo_file           
	WHERE CType = "ce_hero"';
$result = $db->query($query);


$query = 'UPDATE sys_file_reference sfr
JOIN tt_content tc ON sfr.uid_foreign = tc.uid
SET sfr.fieldname = "image"
WHERE tc.CType = "ps14_hero"
	AND sfr.fieldname = "tx_xo_file"';
$result = $db->query($query);