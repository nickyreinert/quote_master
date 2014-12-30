<?php
global $wpdb;

	$table_name = $wpdb->prefix . "mlw_quotes";

	$sql = "DROP TABLE IF EXISTS ".$table_name;

	$results = $wpdb->query( $sql );
	
	$table_name = $wpdb->prefix . "mlw_quotes_cate";

	$sql = "DROP TABLE IF EXISTS ".$table_name;

	$results = $wpdb->query( $sql );
	
	delete_option('mlw_quotes_version');
	
delete_option('mlw_advert_shows');
?>