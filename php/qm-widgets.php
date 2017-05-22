<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Class to generate the quote widget
 *
 * @since 7.0.0
 */
class QM_Widget extends WP_Widget {

   	// constructor
    function __construct() {
        parent::__construct(false, $name = __('Quote Master Widget', 'quote-master'));
    }

    // widget form creation
    function form($instance) {
	    // Check values
  		if( $instance) {
  	     	$title = esc_attr($instance['title']);
  	     	$cate = esc_attr($instance['cate']);
  		} else {
  			$title = '';
  			$cate = 'all';
  		}

  		$categories = get_terms('quote_category');
      ?>
  		<p>
  		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'quote-master'); ?></label>
  		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
  		</p>
  		<p>
  		<label for="<?php echo $this->get_field_id('cate'); ?>"><?php _e('Category', 'quote-master'); ?></label>
  		<select name="<?php echo $this->get_field_name('cate'); ?>" id="<?php echo $this->get_field_id('cate'); ?>" class="widefat">

  		<?php
  		echo "<option value='all' id='all' ",$cate == 'all' ? " selected='selected'" : "",">All</option>";
  		foreach($categories as $category) {
  			echo '<option value=' . $category->term_id . ' id=' . $category->term_id . '', $cate == $category->term_id ? ' selected="selected"' : '', '>', $category->name, '</option>';
  		}
  		?>
  		</select>
  		</p>

  		<?php
  	}

    // widget update
    function update($new_instance, $old_instance) {
      $instance = $old_instance;
    	// Fields
    	$instance['title'] = strip_tags($new_instance['title']);
    	$instance['cate'] = strip_tags($new_instance['cate']);
     	return $instance;
    }

    // widget display
    function widget($args, $instance) {
        extract( $args );
   		// these are the widget options
   		$title = apply_filters('widget_title', $instance['title']);
   		$cate = $instance['cate'];
    	echo $before_widget;
   		// Display the widget
   		echo '<div class="widget-text wp_widget_plugin_box">';
   		// Check if title is set
   		if ( $title ) {
      		echo $before_title . $title . $after_title;
   		}

       $settings = (array) get_option( 'qm-settings' );
       if ( isset( $settings['chosen_style'] ) ) {
         switch ($settings['chosen_style']) {
           case 'default':
             wp_enqueue_style( 'qm_quote_style', plugins_url( '../css/quote.css' , __FILE__ ) );
             break;

           default:
             echo "<style>".$settings['custom_style']."</style>";
             break;
         }
       } else {
         wp_enqueue_style( 'qm_quote_style', plugins_url( '../css/quote.css' , __FILE__ ) );
       }

      $shortcode = '';
      $args = array(
        'post_type' => 'quote',
        'orderby' => 'rand',
        'posts_per_page' => 1,
      );
      if ($cate != "all")
      {
        $extra_args = array(
          'tax_query' => array(
        		array(
        			'taxonomy' => 'quote_category',
        			'field'    => 'id',
        			'terms'    => $cate,
        		),
        	),
        );
        $args = array_merge($args, $extra_args);
      }
      $my_query = new WP_Query( $args );
     	if( $my_query->have_posts() )
     	{
     	  while( $my_query->have_posts() )
     		{
          $my_query->the_post();
          $shortcode_each = '<div class="qm_quote_widget">';

            $quote_text = apply_filters('qm_quote_text', get_the_content());
            $shortcode_each .= "<span class='qm_quote_widget_text'>$quote_text</span>";

            $author = get_post_meta(get_the_ID(),'quote_author',true);
            if ($author != '')
            {

				$author = ' ~ '.$author;
				$author = apply_filters('qm_author_text', $author);
				$shortcode_each .= "<span class='qm_quote_widget_author'>$author</span>";

		        if ( isset( $settings['shorten_author'] ) && $settings['shorten_author'] == '1' )
				{
					$authorArr = explode(',', $author);
					if (array_key_exists(0, $authorArr)) {
						$author = trim($authorArr[0]);
					}
				}
			}

            $source = get_post_meta(get_the_ID(),'source',true);
            if ($source != '')
            {
              $source = 'Source: '.$source;
              $source = apply_filters('qm_source_text', $source);
              $shortcode_each .= "<span class='qm_quote_widget_source'>$source</span>";
            }

            if ( isset( $settings['enable_tweet'] ) && $settings['enable_tweet'] == '1' )
			{

				if ( isset( $settings['link_to_homepage'] ) && $settings['link_to_homepage'] == '1' ) {

					$backlink = ' ~ '. $_SERVER['HTTP_HOST'];

				} else {

					$backlink = ' ~ ' . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
				}

				$clean_quote_text = esc_html(strip_tags($quote_text));


				if (strlen($clean_quote_text . $author . $backlink) > 140) {

					// why - 6 you might ask?
					// for some yet unknown reason, www. in front of the
					// back link counts for 6 chars for twitter - don't ask
					// why, i double checked it :/

					$maxTweetLength = 140 - 6 - strlen($author . $backlink . ' [...] ');

					$tweet = substr($clean_quote_text, 0, $maxTweetLength) . ' [...] ';
					$tweet .= $author;
					$tweet .= $backlink;


				} else {

					$tweet = $clean_quote_text . $author . $backlink;

				}

				$shortcode_each .= "<a target=\"_blanK\" href='https://twitter.com/intent/tweet?text=".$tweet."' class='qm_quote_tweet'>Tweet</a>";

			}

          $shortcode_each .= '</div>';
          $shortcode .= apply_filters('qm_display_quote', $shortcode_each, get_the_ID());
     	  }
     	}
     	wp_reset_postdata();
 			echo $shortcode;
   		echo '</div>';
   		echo $after_widget;
    }
}
?>
