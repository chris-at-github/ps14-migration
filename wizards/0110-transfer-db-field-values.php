<?php

$db = new mysqli('db', 'db', 'db', 'db');

// ---------------------------------------------------------------------------------------------------------------------
// Pages
$query = 'UPDATE pages SET 
	tx_foundation_navigation_not_linked = tx_xo_no_link,
	tx_foundation_disable_breadcrumb = tx_xo_no_breadcrumb,
	tx_foundation_breadcrumb_hidden = tx_xo_breadcrumb_hidden,
	tx_foundation_navigation_content = tx_xo_navigation_content,
	tx_foundation_navigation_layout = tx_xo_navigation_layout,
	tx_foundation_disable_sticky = tx_xo_no_sticky';
$result = $db->query($query);

$query = 'UPDATE pages SET 
		tx_foundation_identifier = "individual-product"
	WHERE uid IN (183)';
$result = $db->query($query);

// ---------------------------------------------------------------------------------------------------------------------
// Content
$query = 'UPDATE tt_content SET 
	tx_foundation_variant = tx_xo_variant,
	tx_foundation_file = tx_xo_file,
	tx_foundation_elements = tx_xo_elements,
	tx_foundation_no_frame = tx_xo_no_frame,
	tx_foundation_parent = tx_xo_parent,
	tx_foundation_print_break = tx_xo_print_break,
	tx_foundation_print_visibility = tx_xo_print_visibility,
	tx_foundation_section_menu_title = tx_xo_section_menu_title,
	tx_foundation_readmore = tx_xo_readmore,
	tx_site_wkhtmltopdf_enabled = tx_xna_wkhtmltopdf_enabled,
	tx_site_wkhtmltopdf_title = tx_xna_wkhtmltopdf_title';
$result = $db->query($query);

$query = 'UPDATE tt_content SET 
		tx_foundation_variant = "default"
	WHERE tx_foundation_variant = 0';
$result = $db->query($query);

$query = 'UPDATE tt_content SET 
		header_layout = "100"
	WHERE uid IN (384, 388)';
$result = $db->query($query);

// Pages Content MM
$query = 'INSERT IGNORE INTO tx_foundation_pages_content_mm (
		uid_local,
		uid_foreign,
		sorting,
		sorting_foreign
  ) 
	SELECT 
		uid_local,
		uid_foreign,
		sorting,
		sorting_foreign
	FROM tx_xo_pages_content_mm
';
$result = $db->query($query);


// ---------------------------------------------------------------------------------------------------------------------
// Categories
$query = 'UPDATE sys_category SET
	tx_foundation_identifier = tx_xo_identifier,
	tx_foundation_link = tx_xo_link';
$result = $db->query($query);

$query = 'UPDATE sys_category SET 
		tx_foundation_identifier = "opening-hours-type"
	WHERE uid IN (195)';
$result = $db->query($query);

$query = 'UPDATE sys_category SET 
		tx_foundation_identifier = "contact-countries"
	WHERE uid IN (12)';
$result = $db->query($query);

$query = 'UPDATE sys_category SET 
		tx_foundation_identifier = "contact-continents"
	WHERE uid IN (198)';
$result = $db->query($query);

$query = 'UPDATE sys_category SET 
		tx_foundation_identifier = "contact-product-lines"
	WHERE uid IN (202)';
$result = $db->query($query);

$query = 'UPDATE sys_category SET 
		tx_foundation_identifier = "entity-product-main"
	WHERE uid IN (7)';
$result = $db->query($query);

$query = 'UPDATE sys_category SET 
		tx_foundation_identifier = "entity-product-filter",
    sorting = 1           
	WHERE uid IN (16)';
$result = $db->query($query);


// ---------------------------------------------------------------------------------------------------------------------
// Address
$query = 'UPDATE tt_address SET 
	tx_foundation_directors = tx_xo_directors,
	tx_foundation_commercial_register = tx_xo_commercial_register,
	tx_foundation_registered_office = tx_xo_registered_office,
	tx_foundation_vat_number = tx_xo_vat_number,
	tx_foundation_opening_hours_description = tx_xo_opening_hours_description,
	tx_foundation_opening_hours = tx_xo_opening_hours,
	tx_foundation_youtube = tx_xo_youtube,
	instagram = tx_xo_instagram,
	tx_foundation_additional_description = tx_xo_additional_description';
$result = $db->query($query);

$query = 'UPDATE tt_address SET 
		record_type = "Ps14\\\Foundation\\\Domain\\\Model\\\Address"
	WHERE record_type = "Ps\\\Xo\\\Domain\\\Model\\\Address"';
$result = $db->query($query);

$query = 'UPDATE tt_address SET 
		tx_foundation_identifier = "provider"
	WHERE uid IN (14)';
$result = $db->query($query);


// ---------------------------------------------------------------------------------------------------------------------
// Elements
$query = 'INSERT IGNORE INTO tx_foundation_domain_model_elements (
		uid, 
		pid, 
		title, 
		description, 
		link, 
		media, 
		thumbnail, 
		files, 
		content, 
		print_break, 
		print_visibility, 
		record_type, 
		sorting, 
		foreign_uid, 
		foreign_field, 
		tstamp, 
		crdate, 
		cruser_id, 
		deleted, 
		hidden, 
		starttime, 
		endtime, 
		t3ver_oid, 
		t3ver_id, 
		t3ver_wsid, 
		t3ver_label, 
		t3ver_state, 
		t3ver_stage, 
		t3ver_count, 
		t3ver_tstamp, 
		t3ver_move_id, 
		sys_language_uid, 
		l10n_parent, 
		l10n_diffsource, 
		l10n_state,
    tx_accordion_active,
    tx_timeline_date,
    tx_kist_values_color_scheme
  ) 
	SELECT 
		uid, 
		pid, 
		title, 
		description, 
		link, 
		media, 
		thumbnail, 
		files, 
		content, 
		print_break, 
		print_visibility, 
		record_type, 
		sorting, 
		foreign_uid, 
		foreign_field, 
		tstamp, 
		crdate, 
		cruser_id, 
		deleted, 
		hidden, 
		starttime, 
		endtime, 
		t3ver_oid, 
		t3ver_id, 
		t3ver_wsid, 
		t3ver_label, 
		t3ver_state, 
		t3ver_stage, 
		t3ver_count, 
		t3ver_tstamp, 
		t3ver_move_id, 
		sys_language_uid, 
		l10n_parent, 
		l10n_diffsource, 
		l10n_state,
		tx_ce_accordion_active,
		tx_ce_history_date,
		tx_ce_kist_values_color_scheme
	FROM tx_xo_domain_model_elements
';
$result = $db->query($query);

// Überschriften-Typ aus xo_elements nach tt_content übertragen
$query = '
	SELECT foreign_uid, title_type
	FROM tx_xo_domain_model_elements
	WHERE title_type > 0 AND record_type LIKE "ce_%"
	GROUP BY foreign_uid';
$result = $db->query($query);

if($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$updateQuery = 'UPDATE tt_content
			SET tx_foundation_elements_header = "' . $row['title_type'] . '"
			WHERE uid = ' . $row['foreign_uid'];
		$db->query($updateQuery);
	}
}


// ---------------------------------------------------------------------------------------------------------------------
// Readmore
$query = 'INSERT INTO tx_foundation_domain_model_readmore (
    pid,
    sorting,
    tstamp,
    crdate,
    cruser_id,
    deleted,
    hidden,
    starttime,
    endtime,
    t3ver_oid,
    t3ver_wsid,
    t3ver_state,
    t3ver_stage,
    t3ver_count,
    t3ver_tstamp,
    t3ver_move_id,
    sys_language_uid,
    l10n_parent,
    l10n_diffsource,
    l10n_state,
    title,
    link,
    foreign_uid,
    foreign_field,
    t3ver_id,
    t3ver_label
)
SELECT
    pid,
    sorting,
    tstamp,
    crdate,
    cruser_id,
    deleted,
    hidden,
    starttime,
    endtime,
    t3ver_oid,
    t3ver_wsid,
    t3ver_state,
    t3ver_stage,
    t3ver_count,
    t3ver_tstamp,
    t3ver_move_id,
    sys_language_uid,
    l10n_parent,
    l10n_diffsource,
    l10n_state,
    title,
    link,
    foreign_uid,
    foreign_field,
    t3ver_id,
    t3ver_label
	FROM tx_xo_domain_model_readmore';
$result = $db->query($query);


// ---------------------------------------------------------------------------------------------------------------------
// Opening Hours
$query = 'INSERT IGNORE INTO tx_foundation_domain_model_openinghours (
		uid, 
		pid, 
		sorting, 
		tstamp, 
		crdate, 
		deleted, 
		hidden, 
		starttime, 
		endtime, 
		t3ver_oid, 
		t3ver_wsid, 
		t3ver_state, 
		t3ver_stage, 
		sys_language_uid, 
		l10n_parent, 
		l10n_diffsource, 
		l10n_state, 
		address, 
		days, 
		days_title, 
		open_at, 
		close_at, 
		category, 
		cruser_id, 
		t3ver_id, 
		t3ver_label, 
		t3ver_count, 
		t3ver_tstamp, 
		t3ver_move_id
  ) 
	SELECT 
		uid, 
		pid, 
		sorting, 
		tstamp, 
		crdate, 
		deleted, 
		hidden, 
		starttime, 
		endtime, 
		t3ver_oid, 
		t3ver_wsid, 
		t3ver_state, 
		t3ver_stage, 
		sys_language_uid, 
		l10n_parent, 
		l10n_diffsource, 
		l10n_state, 
		address, 
		days, 
		days_title, 
		open_at, 
		close_at, 
		category, 
		cruser_id, 
		t3ver_id, 
		t3ver_label, 
		t3ver_count, 
		t3ver_tstamp, 
		t3ver_move_id
	FROM tx_xo_domain_model_openinghours
';
$result = $db->query($query);

$query = 'UPDATE sys_file_reference SET 
		tablenames = "tx_foundation_domain_model_elements"      
	WHERE tablenames = "tx_xo_domain_model_elements"';
$result = $db->query($query);


// ---------------------------------------------------------------------------------------------------------------------
// News
$query = 'UPDATE tx_news_domain_model_news SET 
    tx_kist_news_no_detail = tx_ps14_no_detail,
    tx_kist_news_location = tx_ps14_location,
    tx_kist_news_event_startdate = tx_ps14_event_startdate,
    tx_kist_news_event_enddate = tx_ps14_event_enddate,
    tx_kist_news_event_starttime = tx_ps14_event_starttime,
    tx_kist_news_event_endtime = tx_ps14_event_endtime,
    tx_kist_news_layout = tx_ps14_layout';
$result = $db->query($query);


// ---------------------------------------------------------------------------------------------------------------------
// KeSearch
$query = 'UPDATE tx_kesearch_indexerconfig SET
	contenttypes = "text, header, ps14_accordion, ps14_downloads, ps14_hero, ps14_marker",
	content_fields = "bodytext, subheader",
	file_reference_fields = "tx_foundation_file,media",
	additional_tables = ""
WHERE uid = 1';
$result = $db->query($query);