<?php

$db = new mysqli('db', 'db', 'db', 'db');

// Pages
$query = 'UPDATE pages SET 
	tx_foundation_navigation_not_linked = tx_xo_no_link,
	tx_foundation_disable_breadcrumb = tx_xo_no_breadcrumb,
	tx_foundation_breadcrumb_hidden = tx_xo_breadcrumb_hidden,
	tx_foundation_navigation_content = tx_xo_navigation_content,
	tx_foundation_navigation_layout = tx_xo_navigation_layout';
$result = $db->query($query);

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

// Categories
$query = 'UPDATE sys_category SET 
	tx_foundation_link = tx_xo_link';
$result = $db->query($query);

// Address
$query = 'UPDATE tt_address SET 
	tx_foundation_directors = tx_xo_directors,
	tx_foundation_commercial_register = tx_xo_commercial_register,
	tx_foundation_registered_office = tx_xo_registered_office,
	tx_foundation_vat_number = tx_xo_vat_number,
	tx_foundation_opening_hours_description = tx_xo_opening_hours_description,
	tx_foundation_opening_hours = tx_xo_opening_hours,
	tx_foundation_youtube = tx_xo_youtube,
	instagram = tx_xo_instagram';
$result = $db->query($query);

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
		l10n_state
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
		l10n_state
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


