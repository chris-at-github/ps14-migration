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

// ---------------------------------------------------------------------------------------------------------------------
// Address
$query = 'UPDATE tt_content SET 
		list_type = "ps14foundation_addressrecord",
    pi_flexform = REPLACE(pi_flexform, "structuredDataMainOpeningHoursCategory", "structuredDataOpeningHoursCategory")
	WHERE list_type = "xo_addressrecord"';
$result = $db->query($query);

$query = 'UPDATE tt_content SET 
		tx_foundation_variant = "imprint"
	WHERE uid = 68';
$result = $db->query($query);

// ---------------------------------------------------------------------------------------------------------------------
// Container
$query = 'UPDATE tt_content SET 
		CType = "ps14_container_2_column"
	WHERE CType = "container-2-column"';
$result = $db->query($query);

// ---------------------------------------------------------------------------------------------------------------------
// Timeline
$query = 'UPDATE tt_content SET 
		CType = "ps14_timeline"
	WHERE CType = "ce_history"';
$result = $db->query($query);

$query = 'UPDATE tx_foundation_domain_model_elements SET 
		record_type = "ps14_timeline_default"      
	WHERE record_type = "ce_history_default"';
$result = $db->query($query);

// ---------------------------------------------------------------------------------------------------------------------
// Kist Values
$query = 'UPDATE tt_content SET 
		CType = "ps14_kist_values"
	WHERE CType = "ce_kist_values"';
$result = $db->query($query);

$query = 'UPDATE tx_foundation_domain_model_elements SET 
		record_type = "ps14_kist_values_default"      
	WHERE record_type = "ce_kist_values_default"';
$result = $db->query($query);

// ---------------------------------------------------------------------------------------------------------------------
// News
$query = 'UPDATE tt_content SET 
		CType = "news_pi1",
		list_type = ""
	WHERE list_type = "news_pi1"';
$result = $db->query($query);
