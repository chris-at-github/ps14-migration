<?php

$db = new mysqli('db', 'db', 'db', 'db');

// TypoScript Root Einbindung anpassen
$query = 'UPDATE sys_template SET 
		title = "Ps14",
		constants = "@import \'EXT:ps14_site/Configuration/TypoScript/Site.constants.typoscript\'",
		config = "@import \'EXT:ps14_site/Configuration/TypoScript/Site.setup.typoscript\'"
	WHERE uid = 1';
$result = $db->query($query);

// PageTs
$query = 'UPDATE pages SET 
		TSconfig = "@import \'EXT:ps14_site/Configuration/TSConfig/Page.tsconfig\'"
	WHERE uid = 1';
$result = $db->query($query);

// UserTs
$query = 'UPDATE be_groups SET 
		TSconfig = "@import \'EXT:ps14_site/Configuration/TSConfig/User.tsconfig\'"
	WHERE uid = 2';
$result = $db->query($query);