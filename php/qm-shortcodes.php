<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
  * Class to create the various shortcodes
  *
  * @since 7.0.0
  */
class QM_Shortcodes
{
    /**
      * Main Construct Function
      *
      * Call functions within class
      *
      * @since 7.0.0
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
      * @since 7.0.0
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
      * @since 7.0.0
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
     * @since 7.0.0
     * @return string The HTML of the quote
     */
    public function display_quotes($atts)
    {
      extract(shortcode_atts(array(
    		'cate' => 'all',
    		'all' => 'no'
    	), $atts));

      wp_enqueue_style( 'qm_quote_style', plugins_url( '../css/quote.css' , __FILE__ ) );

      $shortcode = '';
      $args = array(
        'post_type' => 'quote',
        'orderby' => 'rand',
        'posts_per_page' => 1,
      );
      if ($cate != "all")
      {
        $cate = sanitize_text_field($cate);
        $extra_args = array(
          'tax_query' => array(
        		array(
        			'taxonomy' => 'quote_category',
        			'field'    => 'slug',
        			'terms'    => $cate,
        		),
        	),
        );
        $args = array_merge($args, $extra_args);
      }
      if ($all != 'no')
      {
        $extra_args = array(
          'posts_per_page' => -1,
        );
        $args = array_merge($args, $extra_args);
      }
      $my_query = new WP_Query( $args );
    	if( $my_query->have_posts() )
    	{
    	  while( $my_query->have_posts() )
    		{
          $my_query->the_post();
          $shortcode_each = '<div class="qm_quote">';

            $quote_text = apply_filters('qm_quote_text', get_the_content());
            $shortcode_each .= "<span class='qm_quote_text'>$quote_text</span>";

            $author = get_post_meta(get_the_ID(),'quote_author',true);
            if ($author != '')
            {
              $author = "~".$author;
              $author = apply_filters('qm_author_text', $author);
              $shortcode_each .= "<span class='qm_quote_author'>$author</span>";
            }

            $source = get_post_meta(get_the_ID(),'source',true);
            if ($source != '')
            {
              $source = 'Source: '.$source;
              $source = apply_filters('qm_source_text', $source);
              $shortcode_each .= "<span class='qm_quote_source'>$source</span>";
            }
          $shortcode_each .= '</div>';
          $shortcode .= apply_filters('qm_display_quote', $shortcode_each, get_the_ID());
    	  }
    	}
    	wp_reset_postdata();
			return $shortcode;
    }
}
$qm_shortcodes = new QM_Shortcodes();
?>
