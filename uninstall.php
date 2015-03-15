<?php
//if uninstall not called from WordPress, then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
{
	exit();
}

global $wpdb;

$table_name = $wpdb->prefix . "mlw_quotes";
$results = $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

$table_name = $wpdb->prefix . "mlw_quotes_cate";
$results = $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

delete_option('mlw_quotes_version');
delete_option('mlw_advert_shows');
?>
