<?php
/*
This page creates the main dashboard for the Quiz Master Next plugin
Copyright 2014, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_quotes_generate_admin(){
	
	//Page Variables
	global $wpdb;
	$mlw_quotes_table_name = $wpdb->prefix . "mlw_quotes";
	$mlw_quotes_hasAddedQuotes = false;
	$mlw_quotes_hasDeletedQuotes = false;
	$mlw_quotes_hasEditedQuotes = false;
	$mlw_quotes_isQueryError = false;
	
	//Add New Quote Form Submitted
	if (isset($_POST["add_quote_form"]) && $_POST["add_quote_form"] == "confirmation")
	{
		$mlw_quotes_quote = stripslashes($_POST["quote"]);
		$mlw_quotes_author = stripslashes($_POST["author"]);
		$mlw_quotes_source = stripslashes($_POST["source"]);
		$mlw_quotes_category_id = intval($_POST["category"]);
		$mlw_quotes_category = $wpdb->get_var( $wpdb->prepare( "SELECT category FROM " . $wpdb->prefix . "mlw_quotes_cate WHERE category_id='%d'", $mlw_quotes_category_id ) );
		$mlw_quotes_query_results = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $mlw_quotes_table_name . " ( quote, author, source, category, category_id, deleted )  VALUES ( %s, %s, %s, %s, %d, %d )", $mlw_quotes_quote, $mlw_quotes_author, $mlw_quotes_source, $mlw_quotes_category, $mlw_quotes_category_id, 0 ) );
		if ($mlw_quotes_query_results != false)
		{
			$mlw_quotes_hasAddedQuotes = true;		
		}
		else
		{
			$mlw_quotes_isQueryError = true;
		}
	}
	
	//Edit Quote Form Submitted
	if (isset($_POST["edit_quote_form"]) && $_POST["edit_quote_form"] == "confirmation")
	{
		$mlw_quotes_edit_id = intval($_POST["edit_quote_id"]);
		$mlw_quotes_edit_quote = stripslashes($_POST["edit_quote"]);
		$mlw_quotes_edit_author = stripslashes($_POST["edit_author"]);
		$mlw_quotes_edit_source = stripslashes($_POST["edit_source"]);
		$mlw_quotes_edit_category_id = intval($_POST["edit_category"]);
		$mlw_quotes_edit_category = $wpdb->get_var( $wpdb->prepare( "SELECT category FROM " . $wpdb->prefix . "mlw_quotes_cate WHERE category_id='%d'", $mlw_quotes_edit_category_id ) );
		$mlw_quotes_query_results = $wpdb->query( $wpdb->prepare( "UPDATE " . $mlw_quotes_table_name . " SET quote='%s', author='%s', source='%s', category='%s', category_id='%d' WHERE quote_id='%d'", $mlw_quotes_edit_quote, $mlw_quotes_edit_author, $mlw_quotes_edit_source, $mlw_quotes_edit_category, $mlw_quotes_edit_category_id, $mlw_quotes_edit_id ) );
		if ($mlw_quotes_query_results != false)
		{
			$mlw_quotes_hasEditedQuotes = true;	
		}
		else
		{
			$mlw_quotes_isQueryError = true;
		}
	}
	
	//Delete Quote Form Submitted
	if (isset($_POST["delete_quote_form"]) && $_POST["delete_quote_form"] == "confirmation")
	{
		$mlw_quotes_delete_id = intval($_POST["delete_quote_id"]);
		$mlw_quotes_query_results = $wpdb->query( $wpdb->prepare( "UPDATE " . $mlw_quotes_table_name . " SET deleted='1' WHERE quote_id='%d'", $mlw_quotes_delete_id ) );
		if ($mlw_quotes_query_results != false)
		{
			$mlw_quotes_hasDeletedQuotes = true;	
		}
		else
		{
			$mlw_quotes_isQueryError = true;
		}
	}
	
	//Retrieve list of quizzes
	$mlw_quotes_sql = "SELECT * FROM " . $wpdb->prefix . "mlw_quotes WHERE deleted='0'";
	$mlw_quotes_sql .= "ORDER BY quote_id DESC";

	$mlw_quotes_data = $wpdb->get_results($mlw_quotes_sql);	
	
	//Retrieve list of categories
	$mlw_quotes_cate_sql = "SELECT * FROM " . $wpdb->prefix . "mlw_quotes_cate WHERE deleted='0'";
	$mlw_quotes_cate_sql .= "ORDER BY category ASC";

	$mlw_quotes_cate_data = $wpdb->get_results($mlw_quotes_cate_sql);	
	?>
	
	
	<!-- css -->
	<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" />
	<!-- jquery scripts -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script type="text/javascript">
		var $j = jQuery.noConflict();
		// increase the default animation speed to exaggerate the effect
		$j.fx.speeds._default = 1000;
		$j(function() {
			$j("button").button();
		
		});
		$j(function() {
			$j('#new_quote_dialog').dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
		
			$j('#new_quote_button').click(function() {
				$j('#new_quote_dialog').dialog('open');
				return false;
			});
			$j('#new_quote_button_two').click(function() {
				$j('#new_quote_dialog').dialog('open');
				return false;
			});
		});
		function deleteQuote(id){
			$j("#delete_quote_dialog").dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
			$j("#delete_quote_dialog").dialog('open');
			var idHidden = document.getElementById("delete_quote_id");
			idHidden.value = id;
		};
		function editQuote(id, quote, author, source, category_id){
			$j("#edit_quote_dialog").dialog({
				autoOpen: false,
				show: 'blind',
				width:700,
				hide: 'explode',
				buttons: {
				Cancel: function() {
					$j(this).dialog('close');
					}
				}
			});
			$j("#edit_quote_dialog").dialog('open');
			var idHidden = document.getElementById("edit_quote_id");
			var quoteText = document.getElementById("edit_quote");
			var authorText = document.getElementById("edit_author");
			var sourceText = document.getElementById("edit_source");
			var cateSelect = document.getElementById("edit_category");
			idHidden.value = id;
			quoteText.value = quote;
			authorText.value = author;
			sourceText.value = source;
			cateSelect.value = category_id;
		};
	</script>
	
	<!--Begin Page Content-->
	<div class="wrap">		
		<h2>Quote Master</h2>
		<p>Use the shortcode [mlw_quotes] in a page or post to load a quote randomly. Or use the Quote Master widget!</p>
		<?php if ($mlw_quotes_isQueryError)
		{
			?>
			<div class="ui-state-error ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Oh-No!</strong> There appears to have been an error in the last request! Please share this with the developer.</p>
			</div>
			<?php
		}
		if ($mlw_quotes_hasEditedQuotes)
		{
			?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Hey!</strong> The quote has been edited successfully.</p>
			</div>
			<?php
		}
		if ($mlw_quotes_hasDeletedQuotes)
		{
			?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Hey!</strong> The quote has been deleted successfully.</p>
			</div>
			<?php
		}
		if ($mlw_quotes_hasAddedQuotes)
		{
			?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Hey!</strong> The quote has been added successfully.</p>
			</div>
			<?php
		}
		?>
		<?php echo mlw_quotes_show_adverts(); ?>
		<button id="new_quote_button_two">Add Quote</button>
		<?php
		
		//Generate Table Of Quotes
		$mlw_quotes_list = "";
		$mlw_quotes_display = "";
		$alternate = "";
		foreach($mlw_quotes_data as $mlw_quotes_info) {
			if($alternate) $alternate = "";
			else $alternate = " class=\"alternate\"";
			$mlw_quotes_list .= "<tr{$alternate}>";
			$mlw_quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quotes_info->quote ." </span><div><span style='color:green;font-size:12px;'><a onclick=\"editQuote('".intval($mlw_quotes_info->quote_id)."','".esc_js($mlw_quotes_info->quote)."','".esc_js($mlw_quotes_info->author)."','".esc_js($mlw_quotes_info->source)."','".intval($mlw_quotes_info->category_id)."')\"href='#'>Edit</a> | <a onclick=\"deleteQuote('".$mlw_quotes_info->quote_id."')\"href='#'>Delete</a></span></div></td>";
			$mlw_quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quotes_info->author ." </span></td>";
			$mlw_quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quotes_info->source ." </span></td>";
			$mlw_quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quotes_info->category ." </span></td>";
			$mlw_quotes_list .= "</tr>";
		}
	
		$mlw_quotes_display .= "<table class=\"widefat\">";
			$mlw_quotes_display .= "<thead><tr>
				<th>Quote</th>
				<th>Author</th>
				<th>Source</th>
				<th>Category</th>
			</tr></thead>";
			$mlw_quotes_display .= "<tbody id=\"the-list\">{$mlw_quotes_list}</tbody>";
			$mlw_quotes_display .= "</table>";
		echo $mlw_quotes_display;
		?>
		<button id="new_quote_button">Add Quote</button>
		
		<!--New Quote Dialog-->
		<div id="new_quote_dialog" title="Add Quote" style="display:none;">
			<?php
			echo "<form action='' method='post'>";
			echo "<input type='hidden' name='add_quote_form' value='confirmation' />";
			?>
			<table class="wide" style="text-align: left; white-space: nowrap;">
				<tr valign="top">
					<td></td>
				</tr>
				<tr valign="top">
					<td><h3>Add Quote</h3></td>
				</tr>
				<tr valign="top">
					<td><span style='font-weight:bold;'>Quote</span></td>
					<td><textarea name="quote" style="width: 500px; height: 150px;"></textarea></td>
				</tr>
				<tr valign="top">
					<td><span style='font-weight:bold;'>Author</span></td>
					<td><input type="text" name="author" value="" /></td>
				</tr>
				<tr valign="top">
					<td><span style='font-weight:bold;'>Source</span></td>
					<td><input type="text" name="source" value="" /></td>
				</tr>
				<tr>
					<td><span style='font-weight:bold;'>Category</span></td>
					<td><select name="category">
							<?php
							foreach($mlw_quotes_cate_data as $mlw_quotes_cate_info) {
								echo "<option value='".$mlw_quotes_cate_info->category_id."'>".$mlw_quotes_cate_info->category."</option>";							
							}
							?>
						</select>
					</td>
				</tr>
			</table>
			<?php
			echo "<p class='submit'><input type='submit' class='button-primary' value='Add Quote' /></p>";
			echo "</form>";
			?>
		</div>
		
		<!--Edit Quote Dialog-->
		<div id="edit_quote_dialog" title="Edit Quote" style="display:none;">
			<?php
			echo "<form action='' method='post'>";
			echo "<input type='hidden' name='edit_quote_form' value='confirmation' />";
			echo "<input type='hidden' id='edit_quote_id' name='edit_quote_id' value='' />";
			?>
			<table class="wide" style="text-align: left; white-space: nowrap;">
				<tr valign="top">
					<td></td>
				</tr>
				<tr valign="top">
					<td><h3>Edit Quote</h3></td>
				</tr>
				<tr valign="top">
					<td><span style='font-weight:bold;'>Quote</span></td>
					<td><textarea name="edit_quote" id="edit_quote" style="width: 500px; height: 150px;"></textarea></td>
				</tr>
				<tr valign="top">
					<td><span style='font-weight:bold;'>Author</span></td>
					<td><input type="text" id="edit_author" name="edit_author" value="" /></td>
				</tr>
				<tr>
					<td><span style='font-weight:bold;'>Source</span></td>
					<td><input type="text" id="edit_source" name="edit_source" value="" /></td>
				</tr>
				<tr>
					<td><span style='font-weight:bold;'>Category</span></td>
					<td><select name="edit_category" id="edit_category">
							<?php
							foreach($mlw_quotes_cate_data as $mlw_quotes_cate_info) {
								echo "<option value='".$mlw_quotes_cate_info->category_id."'>".$mlw_quotes_cate_info->category."</option>";							
							}
							?>
						</select>
					</td>
				</tr>
			</table>
			<?php
			echo "<p class='submit'><input type='submit' class='button-primary' value='Edit Quote' /></p>";
			echo "</form>";
			?>
		</div>
		
		<!--Delete Quote Dialog-->
		<div id="delete_quote_dialog" title="Delete Quote" style="display:none;">
			<?php
			echo "<form action='' method='post'>";
			echo "<input type='hidden' name='delete_quote_form' value='confirmation' />";
			echo "<input type='hidden' id='delete_quote_id' name='delete_quote_id' value='' />";
			?>
			<table class="wide" style="text-align: left; white-space: nowrap;">
				<tr valign="top">
					<td></td>
				</tr>
				<tr valign="top">
					<td><h3>Are You Sure You Want To Delete This Quote?</h3></td>
				</tr>
			</table>
			<?php
			echo "<p class='submit'><input type='submit' class='button-primary' value='Delete Quote' /></p>";
			echo "</form>";
			?>
		</div>
	</div>	
	</div>
	<?php
}
?>