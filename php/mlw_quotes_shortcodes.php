<?php
/*
This function displays the quote to the user when using the shortcode.
*/
function mlw_quotes_shortcode($atts)
{
	extract(shortcode_atts(array(
		'cate' => 'all',
		'all' => 'no'
	), $atts));
	
	$mlw_quotes_cate = $cate;
	$mlw_quotes_limit_sql = "";
	if ($mlw_quotes_cate == "all")
	{
		$mlw_quotes_cate_sql = "";
	}
	else
	{
		$mlw_quotes_cate_sql = " AND category_id=".$mlw_quotes_cate;
	}
	if ($all == 'no')
	{
		$mlw_quotes_limit_sql = " LIMIT 1";
	}
	else
	{
		$mlw_quotes_limit_sql = "";
	}
	$mlw_quotes_display = "";
	global $wpdb;
	$mlw_quotes_table_name = $wpdb->prefix . "mlw_quotes";
	$mlw_quotes_data = $wpdb->get_results( "SELECT * FROM " .$mlw_quotes_table_name. " WHERE deleted='0'".$mlw_quotes_cate_sql." ORDER BY RAND()".$mlw_quotes_limit_sql );
	foreach($mlw_quotes_data as $mlw_quotes_each) {
		$mlw_quotes_display .= "<p>'".$mlw_quotes_each->quote."'</p>";
		$mlw_quotes_display .= "<p>~ ".$mlw_quotes_each->author."<br />";
		if ( $mlw_quotes_each->source != "" )
		{
			$mlw_quotes_display .= "Source: ".$mlw_quotes_each->source."";
		}
		$mlw_quotes_display .= "</p>";
		if ($all != 'no')
		{
		$mlw_quotes_display .= "<br /><br />";
		}
	}
	
	return $mlw_quotes_display;
}
?>