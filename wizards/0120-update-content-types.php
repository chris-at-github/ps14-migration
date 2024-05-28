<?php

$db = new mysqli('db', 'db', 'db', 'db');

// ---------------------------------------------------------------------------------------------------------------------
// Hero
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

// ---------------------------------------------------------------------------------------------------------------------
// Accordion
$query = 'UPDATE tt_content SET 
		CType = "ps14_accordion"    
	WHERE CType = "ce_accordion"';
$result = $db->query($query);

$query = 'UPDATE tx_foundation_domain_model_elements SET 
		record_type = "ps14_accordion_default"      
	WHERE record_type = "ce_accordion_default"';
$result = $db->query($query);

$query = 'UPDATE tx_foundation_domain_model_elements SET 
		record_type = "ps14_accordion_records"      
	WHERE record_type = "ce_accordion_records"';
$result = $db->query($query);

// ---------------------------------------------------------------------------------------------------------------------
// Marker
$query = 'UPDATE tt_content SET 
		CType = "ps14_marker",
    image = tx_xo_file
	WHERE CType = "ce_marker"';
$result = $db->query($query);

$query = 'UPDATE tx_foundation_domain_model_elements SET 
		record_type = "ps14_marker_default"      
	WHERE record_type = "ce_marker_default"';
$result = $db->query($query);

$query = 'UPDATE sys_file_reference sfr
	JOIN tt_content tc ON sfr.uid_foreign = tc.uid
	SET sfr.fieldname = "image"
	WHERE tc.CType = "ps14_marker"
		AND sfr.fieldname = "tx_xo_file"';
$result = $db->query($query);

// ---------------------------------------------------------------------------------------------------------------------
// Teaser
$query = 'UPDATE tt_content SET 
		list_type = "ps14teaser_frontend"
	WHERE list_type = "teaser_frontend"';
$result = $db->query($query);

// ---------------------------------------------------------------------------------------------------------------------
// Downloads
$query = 'UPDATE tt_content SET 
		CType = "ps14_downloads"
	WHERE CType = "ce_downloads"';
$result = $db->query($query);

$query = 'UPDATE sys_file_reference sfr
	JOIN tt_content tc ON sfr.uid_foreign = tc.uid
	SET sfr.fieldname = "tx_foundation_file"
	WHERE tc.CType = "ps14_downloads"
		AND sfr.fieldname = "tx_xo_file"';
$result = $db->query($query);
