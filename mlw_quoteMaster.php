<?php
/**
 * Plugin Name: Quote Master
 * Plugin URI: http://mylocalwebstop.com
 * Description: Use this plugin to add quotes to your website.
 * Author: Frank Corso
 * Author URI: http://mylocalwebstop.com
 * Version: 6.3.5
 *
 * Disclaimer of Warranties
 * The plugin is provided "as is". My Local Webstop and its suppliers and licensors hereby disclaim all warranties of any kind, 
 * express or implied, including, without limitation, the warranties of merchantability, fitness for a particular purpose and non-infringement. 
 * Neither My Local Webstop nor its suppliers and licensors, makes any warranty that the plugin will be error free or that access thereto will be continuous or uninterrupted.
 * You understand that you install, operate, and uninstall the plugin at your own discretion and risk.
 * 
 * @author Frank Corso
 * @version 6.3.5
 */

///Files to Include
include("includes/mlw_quotes_admin.php");
include("includes/mlw_quotes_category.php");
include("includes/mlw_quotes_install.php");
include("includes/mlw_quotes_update.php");
include("includes/mlw_quotes_shortcodes.php");
include("includes/mlw_quotes_widgets.php");
include("includes/mlw_quotes_help.php");

///Activation Actions
register_activation_hook( __FILE__, 'mlw_quotes_activate');
add_action('admin_menu', 'mlw_quotes_add_menu');
add_action('admin_init', 'mlw_quotes_update');
add_action('widgets_init', create_function('', 'return register_widget("Mlw_Quotes_Quote_Widget");'));
add_shortcode('mlw_quotes', 'mlw_quotes_shortcode');

//Setup Translations
function mlw_quotes_translation_setup() {
  load_plugin_textdomain( 'mlw_quotes_text_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}
add_action('plugins_loaded', 'mlw_quotes_translation_setup');

///Create Admin Pages
function mlw_quotes_add_menu()
{
	if (function_exists('add_menu_page'))
	{
		add_menu_page('Quote Master', 'Quote Master', 'moderate_comments', __FILE__, 'mlw_quotes_generate_admin');
		add_submenu_page(__FILE__, 'Categories', 'Categories', 'moderate_comments', 'mlw_quotes_category', 'mlw_quotes_generate_category_page');
		add_submenu_page(__FILE__, 'Help', 'Help', 'moderate_comments', 'mlw_quotes_help', 'mlw_quotes_generate_help_page');
	}
}


function mlw_quotes_show_adverts()
{
	$mlw_advert = "";
	$mlw_advert_text = "";
	if ( get_option('mlw_advert_shows') == 'true' )
	{
		$mlw_random_int = rand(0, 5);
		switch ($mlw_random_int) {
			case 0:
				$mlw_advert_text = "Need support or features? Check out our Premium Support options! Visit our <a href=\"http://mylocalwebstop.com/shop/\">WordPress Store</a> for details!";
				break;
			case 1:
				$mlw_advert_text = "Is Quote Master beneficial to your website? Please help by giving us a review on WordPress.org by going <a href=\"http://wordpress.org/support/view/plugin-reviews/quote-master\">here</a>!";
				break;
			case 2:
				$mlw_advert_text = "Want help installing and configuring one of our plugins? Check out our Plugin Installation services. Visit our <a href=\"http://mylocalwebstop.com/shop/\">WordPress Store</a> for details!";
				break;
			case 3:
				$mlw_advert_text = "Would you like to support this plugin but do not need or want premium support? Please consider our inexpensive 'Advertisements Be Gone' add-on which will get rid of these ads. Visit our <a href=\"http://mylocalwebstop.com/shop/\">Plugin Add-On Store</a> for details!";
				break;
			case 4:
				$mlw_advert_text = "Need help keeping your plugins, themes, and WordPress up to date? Want around the clock security monitoring and off-site back-ups? How about WordPress training videos, a monthly status report, and support/consultation? Check out our <a href=\"http://mylocalwebstop.com/wordpress-maintenance-services/\">WordPress Maintenance Services</a> for more details!";
				break;
			case 5:
				$mlw_advert_text = "Setting up a new site? Let us take care of the set-up so you back to running your business. Check out our <a href=\"http://mylocalwebstop.com/shop/\">WordPress Store</a> for more details!";
				break;
			default:
				$mlw_advert_text = "Need support or features? Check out our Premium Support options! Visit our <a href=\"http://mylocalwebstop.com/shop/\">Plugin Add-On Store</a> for details!";
		}
		$mlw_advert .= "
			<style>
			div.help_decide
			{
				display: block;
				text-align:center;
				letter-spacing: 1px;
				margin: auto;
				text-shadow: 0 1px 1px #000000;
				background: #0d97d8;
				border: 5px solid #106daa;
				-moz-border-radius: 20px;
				-webkit-border-radius: 20px;
				-khtml-border-radius: 20px;
				border-radius: 20px;
				color: #FFFFFF;
			}
			div.help_decide a
			{
				color: yellow;
			}		
			</style>";
		$mlw_advert .= "
			<div class=\"help_decide\">
			<p>$mlw_advert_text</p>
			</div>";
	}
	return $mlw_advert;	
}
?>
