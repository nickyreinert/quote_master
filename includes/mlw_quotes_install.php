<?php
/*
These functions are used for installing and uninstalling all necessary databases, options, page, etc.. for the plugin to work properly.
*/
function mlw_quotes_activate()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "mlw_quotes";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 

	{

		$sql = "CREATE TABLE " . $table_name . " (

			quote_id mediumint(9) NOT NULL AUTO_INCREMENT,

			quote TEXT NOT NULL,
			
			author TEXT NOT NULL,
			
			source TEXT NOT NULL,
			
			category TEXT NOT NULL,
			
			category_id INT NOT NULL,

			deleted INT NOT NULL,

			PRIMARY KEY  (quote_id)

		);";

		$results = $wpdb->query( $sql );

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('The less their ability, the more their conceit.', 'Ahad Haam', 'Ability', 1, 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('Great ability develops and reveals itself increasingly with every new assignment.', 'Baltasar Gracian', 'Ability', 1, 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('It is a great ability to be able to conceal ones ability.', 'Unknown', 'Ability', 1, 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('Ability will never catch up with the demand for it.', 'Malcom Forbes', 'Ability', 1, 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('We have too many high sounding words, and too few actions that correspond with them.', 'Abigail Adams', 'Action', 2, 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('All human actions have one or more of these seven causes: chance, nature, compulsion, habit, reason, passion, and desire.', 'Aristotle', 'Action', 2, 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('He who strikes the first blow admits he has lost the argument.', 'Chinese Proverb', 'Argument', 3, 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('Silence is one of the hardest arguments to refute.', 'Josh Billings', 'Argument', 3, 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('Behind every argument is someones ignorance.', 'Louis D. Brandeis', 'Argument', 3, 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(quote, author, category, category_id, deleted) " .
           	"VALUES ('It is not necessary to understand things in order to argue about them.', 'Pierre Beaumarchais', 'Argument', 3, 0)";
		$results = $wpdb->query( $insert );
	}
	
	$table_name = $wpdb->prefix . "mlw_quotes_cate";

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 

	{

		$sql = "CREATE TABLE " . $table_name . " (

			category_id mediumint(9) NOT NULL AUTO_INCREMENT,
			
			category TEXT NOT NULL,
			
			deleted INT NOT NULL,

			PRIMARY KEY  (category_id)

		);";

		$results = $wpdb->query( $sql );

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		$insert = "INSERT INTO " . $table_name .
		"(category, deleted) " .
           	"VALUES ('Ability', 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(category, deleted) " .
           	"VALUES ('Action', 0)";
		$results = $wpdb->query( $insert );
		
		$insert = "INSERT INTO " . $table_name .
		"(category, deleted) " .
           	"VALUES ('Argument', 0)";
		$results = $wpdb->query( $insert );
	}
}
?>