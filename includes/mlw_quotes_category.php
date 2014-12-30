<?php
/*
This page lists the categories
Copyright 2014, My Local Webstop (email : fpcorso@mylocalwebstop.com)
*/

function mlw_quotes_generate_category_page(){
	
	//Page Variables
	global $wpdb;
	$mlw_quotes_table_name = $wpdb->prefix . "mlw_quotes_cate";
	$mlw_quotes_hasAddedCate = false;
	$mlw_quotes_hasDeletedCate = false;
	$mlw_quotes_hasEditedCate = false;
	$mlw_quotes_isQueryError = false;
	
	//Add New Cate Form Submitted
	if (isset($_POST["add_cate_form"]) && $_POST["add_cate_form"] == "confirmation")
	{
		$mlw_quotes_cate = stripslashes($_POST["category"]);
		$mlw_quotes_query_results = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $mlw_quotes_table_name . " ( category, deleted )  VALUES ( %s, %d )", $mlw_quotes_cate, 0 ) );
		if ($mlw_quotes_query_results != false)
		{
			$mlw_quotes_hasAddedCate = true;		
		}
		else
		{
			$mlw_quotes_isQueryError = true;
		}
	}
	
	//Edit Cate Form Submitted
	if (isset($_POST["edit_cate_form"]) && $_POST["edit_cate_form"] == "confirmation")
	{
		$mlw_quotes_edit_id = intval($_POST["edit_cate_id"]);
		$mlw_quotes_edit_cate = stripslashes($_POST["edit_cate"]);
		$mlw_quotes_query_results = $wpdb->query( $wpdb->prepare( "UPDATE " . $mlw_quotes_table_name . " SET category='%s' WHERE category_id='%d'", $mlw_quotes_edit_cate, $mlw_quotes_edit_id ) );
		if ($mlw_quotes_query_results != false)
		{
			$mlw_quotes_hasEditedCate = true;	
		}
		else
		{
			$mlw_quotes_isQueryError = true;
		}
	}
	
	//Delete Quote Form Submitted
	if (isset($_POST["delete_cate_form"]) && $_POST["delete_cate_form"] == "confirmation")
	{
		$mlw_quotes_delete_id = intval($_POST["delete_cate_id"]);
		$mlw_quotes_query_results = $wpdb->query( $wpdb->prepare( "UPDATE " . $mlw_quotes_table_name . " SET deleted='1' WHERE category_id='%d'", $mlw_quotes_delete_id ) );
		if ($mlw_quotes_query_results != false)
		{
			$mlw_quotes_hasDeletedCate = true;	
		}
		else
		{
			$mlw_quotes_isQueryError = true;
		}
	}
	
	//Retrieve list of categories
	$mlw_quotes_sql = "SELECT * FROM " . $wpdb->prefix . "mlw_quotes_cate WHERE deleted='0'";
	$mlw_quotes_sql .= "ORDER BY category ASC";

	$mlw_quotes_data = $wpdb->get_results($mlw_quotes_sql);	
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
			$j('#new_category_dialog').dialog({
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
		
			$j('#new_category_button').click(function() {
				$j('#new_category_dialog').dialog('open');
				return false;
			});
			$j('#new_category_button_two').click(function() {
				$j('#new_category_dialog').dialog('open');
				return false;
			});
		});
		function deleteCate(id){
			$j("#delete_cate_dialog").dialog({
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
			$j("#delete_cate_dialog").dialog('open');
			var idHidden = document.getElementById("delete_cate_id");
			idHidden.value = id;
		};
		function editCate(id, cate){
			$j("#edit_cate_dialog").dialog({
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
			$j("#edit_cate_dialog").dialog('open');
			var idHidden = document.getElementById("edit_cate_id");
			var cateText = document.getElementById("edit_cate");
			idHidden.value = id;
			cateText.value = cate;
		};
	</script>
	
	<!--Begin Page Content-->
	<div class="wrap">		
		<h2>Quote Master Categories</h2>
		<?php if ($mlw_quotes_isQueryError)
		{
			?>
			<div class="ui-state-error ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Oh-No!</strong> There appears to have been an error in the last request! Please share this with the developer.</p>
			</div>
			<?php
		}
		if ($mlw_quotes_hasEditedCate)
		{
			?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Hey!</strong> The category has been edited successfully.</p>
			</div>
			<?php
		}
		if ($mlw_quotes_hasDeletedCate)
		{
			?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Hey!</strong> The category has been deleted successfully.</p>
			</div>
			<?php
		}
		if ($mlw_quotes_hasAddedCate)
		{
			?>
			<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				<strong>Hey!</strong> The category has been added successfully.</p>
			</div>
			<?php
		}
		?>
		<?php echo mlw_quotes_show_adverts(); ?>
		<button id="new_category_button_two">Add Category</button>
		<?php
		
		//Generate Table Of Categories
		$mlw_quotes_list = "";
		$mlw_quotes_display = "";
		$alternate = "";
		foreach($mlw_quotes_data as $mlw_quotes_info) {
			if($alternate) $alternate = "";
			else $alternate = " class=\"alternate\"";
			$mlw_quotes_list .= "<tr{$alternate}>";
			$mlw_quotes_list .= "<td><span style='font-size:16px;'>" . $mlw_quotes_info->category ." </span><div><span style='color:green;font-size:12px;'><a onclick=\"editCate('".intval($mlw_quotes_info->category_id)."','".esc_js($mlw_quotes_info->category)."')\"href='#'>Edit</a> | <a onclick=\"deleteCate('".$mlw_quotes_info->category_id."')\"href='#'>Delete</a></span></div></td>";
			$mlw_quotes_list .= "<td>[mlw_quotes cate=".$mlw_quotes_info->category_id."]</td>";
			$mlw_quotes_list .= "<td>[mlw_quotes cate=".$mlw_quotes_info->category_id." all='yes']</td>";
			$mlw_quotes_list .= "</tr>";
		}
	
		$mlw_quotes_display .= "<table class=\"widefat\">";
			$mlw_quotes_display .= "<thead><tr>
				<th>Category</th>
				<th>Random Shortcode</th>
				<th>List Shortcode</th>
				</tr></thead>";
			$mlw_quotes_display .= "<tbody id=\"the-list\">{$mlw_quotes_list}</tbody>";
			$mlw_quotes_display .= "</table>";
		echo $mlw_quotes_display;
		?>
		<button id="new_category_button">Add Category</button>
		
		<!--New Category Dialog-->
		<div id="new_category_dialog" title="Add Category" style="display:none;">
			<?php
			echo "<form action='' method='post'>";
			echo "<input type='hidden' name='add_cate_form' value='confirmation' />";
			?>
			<table class="wide" style="text-align: left; white-space: nowrap;">
				<tr valign="top">
					<td></td>
				</tr>
				<tr valign="top">
					<td><h3>Add Category</h3></td>
				</tr>
				<tr valign="top">
					<td><span style='font-weight:bold;'>Category</span></td>
					<td><input type="text" name="category" value="" /></td>
				</tr>
			</table>
			<?php
			echo "<p class='submit'><input type='submit' class='button-primary' value='Add Category' /></p>";
			echo "</form>";
			?>
		</div>
		
		<!--Edit Category Dialog-->
		<div id="edit_cate_dialog" title="Edit Category" style="display:none;">
			<?php
			echo "<form action='' method='post'>";
			echo "<input type='hidden' name='edit_cate_form' value='confirmation' />";
			echo "<input type='hidden' id='edit_cate_id' name='edit_cate_id' value='' />";
			?>
			<table class="wide" style="text-align: left; white-space: nowrap;">
				<tr valign="top">
					<td></td>
				</tr>
				<tr valign="top">
					<td><h3>Edit Category</h3></td>
				</tr>
				<tr valign="top">
					<td><span style='font-weight:bold;'>Category</span></td>
					<td><input type="text" id="edit_cate" name="edit_cate" value="" /></td>
				</tr>
			</table>
			<?php
			echo "<p class='submit'><input type='submit' class='button-primary' value='Edit Category' /></p>";
			echo "</form>";
			?>
		</div>
		
		<!--Delete Category Dialog-->
		<div id="delete_cate_dialog" title="Delete Category" style="display:none;">
			<?php
			echo "<form action='' method='post'>";
			echo "<input type='hidden' name='delete_cate_form' value='confirmation' />";
			echo "<input type='hidden' id='delete_cate_id' name='delete_cate_id' value='' />";
			?>
			<table class="wide" style="text-align: left; white-space: nowrap;">
				<tr valign="top">
					<td></td>
				</tr>
				<tr valign="top">
					<td><h3>Are You Sure You Want To Delete This Category?</h3></td>
				</tr>
			</table>
			<?php
			echo "<p class='submit'><input type='submit' class='button-primary' value='Delete Category' /></p>";
			echo "</form>";
			?>
		</div>
	</div>	
	</div>
	<?php
}
?>