<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
  * Class to create the various shortcodes
  *
  * @since 6.4.0
  */
class QM_Shortcodes
{
    /**
      * Main Construct Function
      *
      * Call functions within class
      *
      * @since 6.4.0
      * @uses QM_Shortcodes::load_dependencies() Loads required filed
      * @uses QM_Shortcodes::add_hooks() Adds actions to hooks and filters
      * @return void
      */
    function __construct()
    {
      $this->load_dependencies();
      $this->add_hooks();
    }

    /**
      * Load File Dependencies
      *
      * @since 6.4.0
      * @return void
      */
    public function load_dependencies()
    {
      //Insert code
    }

    /**
      * Add Hooks
      *
      * Adds functions to relavent hooks and filters
      *
      * @since 6.4.0
      * @return void
      */
    public function add_hooks()
    {
      add_shortcode('quotes', array($this, 'display_quotes'));

      //Left for legacy
      add_shortcode('mlw_quotes', array($this, 'display_quotes'));
    }

    /**
     * Displays Quotes
     *
     * @since 6.4.0
     * @return string The HTML of the quote
     */
    public function display_quotes()
    {
      extract(shortcode_atts(array(
    		'cate' => 'all',
    		'all' => 'no'
    	), $atts));

      $shortcode = '';
      $my_query = new WP_Query( array('post_type' => 'quote', 'posts_per_page' => -1) );
    	if( $my_query->have_posts() )
    	{
    	  while( $my_query->have_posts() )
    		{
          $shortcode_each = "<div class='qm_quote'>";
    	    $my_query->the_post();
					$shortcode_each .= '"'.esc_html(get_the_content()).'"<br />';
          $shortcode_each .= "</div>";
          $shortcode .= apply_filters('qm_display_quote', $shortcode_each, get_the_ID());
    	  }
    	}
    	wp_reset_postdata();
			return $shortcode;
    }
}
$qm_shortcodes = new QM_Shortcodes();
?>
