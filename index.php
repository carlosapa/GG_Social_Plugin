<?php 

/**
 * Plugin Name: Linked_in Field
 * Description: Add a linked_in field to custom post type.
 * Version: 0.1
 * Author: Carlos Aparicio
 * Author URI: www.berlinerds.com
 * License: GNU
 */


$global_name = 'Portfolio';

/*
*   METABOX STUFF
*/

/* create meta box */
function add_linked_in_metabox () {
	add_meta_box(
		'linked_in_box', 
		esc_html__('Social URLs', 'linkedin_plugin' ),
		'linked_in_box_cb',
		$global_name,
		'normal',
		'default'
	);
}


/* display meta box */
function linked_in_box_cb ($object, $box) {
	wp_nonce_field( basename( __FILE__ ), 'linked_in_box_nonce' ); ?>

	<p>
	    <label for="linked_in_box">
	    	<img src="<?php echo plugin_dir_url(__FILE__); ?>img/linked_in-tiny.png"
	    		 style="margin: 2px 4px -2px 0;" />
	    	<?php _e( "Add your LinkedIn's personal site URL.", 'linkedin_plugin' ); ?>
	    </label>
	    <br />
	    <input class="widefat" type="text" name="linked_in_box" 
	    	   id="linked_in_box" value="<?php echo esc_url(get_post_meta( $object->ID, 'linked_in_box', true )); ?>" 
	    	   size="30" placeholder="your URL here, please..."
	    	   style="height:35px; margin-top: 4px; border-radius: 2px;"/>
  	</p>
  	<p>
	    <label for="twitter_in_box">
	    	<img src="<?php echo plugin_dir_url(__FILE__); ?>img/twitter_in-tiny.png"
	    		 style="margin: 2px 4px -2px 0;" />
	    	<?php _e( "Add your Twitter's personal site URL.", 'linkedin_plugin' ); ?>
		</label>
	    <br />
	    <input class="widefat" type="text" name="twitter_in_box" 
	           id="twitter_in_box" value="<?php echo esc_url(get_post_meta( $object->ID, 'twitter_in_box', true )); ?>" 
	           size="30" placeholder="your URL here, please..."
	           style="height:35px; margin-top: 4px; border-radius: 2px;"/>
  	</p>
  	<p>
	    <label for="xing_in_box">
	    	<img src="<?php echo plugin_dir_url(__FILE__); ?>img/xing_in-tiny.png"
	    		 style="margin: 2px 4px -2px 0;" />
	    	<?php _e( "Add your Xing's personal site URL.", 'linkedin_plugin' ); ?>
		</label>
	    <br />
	    <input class="widefat" type="text" name="xing_in_box" 
	    	   id="xing_in_box" value="<?php echo esc_url(get_post_meta( $object->ID, 'xing_in_box', true )); ?>" 
	    	   size="30" placeholder="your URL here, please..."
	    	   style="height:35px; margin-top: 4px; border-radius: 2px;"/>
  	</p>
  	<p>
	    <label for="facebook_in_box">
	    	<img src="<?php echo plugin_dir_url(__FILE__); ?>img/facebook_in-tiny.png"
	    		 style="margin: 2px 4px -2px 0;" />
	    	<?php _e( "Add your Facebook's personal site URL.", 'linkedin_plugin' ); ?>
		</label>
	    <br />
	    <input class="widefat" type="text" name="facebook_in_box" 
	    	   id="facebook_in_box" value="<?php echo esc_url(get_post_meta( $object->ID, 'facebook_in_box', true )); ?>" 
	    	   size="30" placeholder="your URL here, please..."
	    	   style="height:35px; margin-top: 4px; border-radius: 2px;"/>
  	</p>
  	<p>
	    <label for="vcard_in_box">
	    	<img src="<?php echo plugin_dir_url(__FILE__); ?>img/vcard_in-tiny.png"
	    		 style="margin: 2px 4px -2px 0;" />
	    	<?php _e( "Add your VCard URL.", 'linkedin_plugin' ); ?>
		</label>
	    <br />
	    <input class="widefat" type="text" name="vcard_in_box" 
	    	   id="vcard_in_box" value="<?php echo esc_url(get_post_meta( $object->ID, 'vcard_in_box', true )); ?>" 
	    	   size="30" placeholder="your URL here, please..."
	    	   style="height:35px; margin-top: 4px; border-radius: 2px;"/>
  	</p>

<?php
}

/* save meta box */
function save_linked_in_metabox ($post_id, $post) {

	/*if (!current_user_can($post_type->cap->edit_post, $post_id)) { return $post_id; }
	if ( !isset($_POST['linked_in_box_nonce']) || !wp_verify_nonce('linked_in_box_nonce' , basename( __FILE__ )) ) 
		return $post_id;*/

	$field = array('linked', 'twitter', 'xing', 'facebook', 'vcard');
	$fields_size = sizeof($field);
	$post_type = get_post_type_object( $post->post_type );

	for ($i = 0; $i < $fields_size; $i++) {
			
		$actual_field   = $field[$i] . '_in_box';
		$new_meta_value = (isset($_POST[$actual_field])) ? $_POST[$actual_field] : '';
		$meta_key 		= $actual_field;
		$meta_value 	= get_post_meta($post_id, $meta_key, true);

		if ($new_meta_value && '' == $meta_value) {
			add_post_meta($post_id, $meta_key, $new_meta_value, true);

		} elseif ($new_meta_value && $new_meta_value != $meta_value) {
			update_post_meta($post_id, $meta_key, $new_meta_value);

		} elseif ('' == $new_meta_value && $meta_value) { 
			delete_post_meta($post_id, $meta_key, $meta_value);
		
		} 	
	}
}

/* hook the plugin */
function linked_in_metabox_setup() {
  add_action( 'add_meta_boxes', 'add_linked_in_metabox' );
  add_action( 'save_post', 'save_linked_in_metabox', 10, 2 );
}
add_action( 'load-post.php', 'linked_in_metabox_setup' );
add_action( 'load-post-new.php', 'linked_in_metabox_setup' );



/*
*   CITY STUFF
*/

/* create meta box */
function add_city_metabox () {
	add_meta_box(
		'city_box', 
		esc_html__('City', 'linkedin_plugin' ),
		'city_box_cb',
		$global_name,
		'side',
		'default'
	);
}

/* display meta box */
function city_box_cb ($object, $box) {
?>
	<p>
	    <label for="city_box">
	    	<?php _e( "Add your city name.", 'linkedin_plugin' ); ?>
	    </label>
	    <br />
	    <input class="widefat" type="text" name="city_box" 
	    	   id="city_box" value="<?php echo esc_url(get_post_meta( $object->ID, 'city_box', true )); ?>" 
	    	   size="30" 
	    	   style="height:35px; margin-top: 4px; border-radius: 2px;"/>
  	</p>

<?php
}

/* save meta box */
function save_city_box ($post_id, $post) {

	$post_type = get_post_type_object( $post->post_type );

	$actual_field   = 'city_box';
	$new_meta_value = (isset($_POST[$actual_field])) ? $_POST[$actual_field] : '';
	$meta_key 		= $actual_field;
	$meta_value 	= get_post_meta($post_id, $meta_key, true);

	if ($new_meta_value && '' == $meta_value) {
		add_post_meta($post_id, $meta_key, $new_meta_value, true);

	} elseif ($new_meta_value && $new_meta_value != $meta_value) {
		update_post_meta($post_id, $meta_key, $new_meta_value);

	} elseif ('' == $new_meta_value && $meta_value) { 
		delete_post_meta($post_id, $meta_key, $meta_value);

	}
}

/* hook the plugin */
function city_metabox_setup() {
  add_action( 'add_meta_boxes', 'add_city_metabox' );
  add_action( 'save_post', 'save_city_box', 10, 2 );
}
add_action( 'load-post.php', 'city_metabox_setup' );
add_action( 'load-post-new.php', 'city_metabox_setup' );



/*
*   DEFINITION OF ACTION TO BE DONE
*/

function the_linkedin_URL_cb () {

    function find_the_URL ($string_in) {
        
        $orig_bid = get_current_blog_id();
        $post_slug = get_the_title(get_the_id());
        $bids = array(1, 2, 3, 4, 5, 6);
        $URL_output = '';
        $key = $string_in . '_in_box';

     	// check if the url exists
        if (get_post_meta(get_the_id(), $key, true ) !== '') {
        	$URL_output = get_post_meta(get_the_id(), $key, true );

        } else {

	        foreach ($bids as $bid) {
				switch_to_blog($bid);
				$args = array('post_type' => 'portfolio', 'meta_key' => $key);
				$query = new WP_Query( $args );
				$size = sizeof($query->posts);

				for ($j = 0; $j < $size; $j++) {
					$title = $query->posts[$j]->post_title;
					if ($title == $post_slug) {
						$URL_output = get_post_meta( $query->posts[$j]->ID, $key, true );
					}
				}

				wp_reset_query();
	        }

	        restore_current_blog();
        }

        return $URL_output;
    }

    function display_the_URL () {
        $URL = find_the_URL('linked');
        echo $URL;
    }
    

    function the_URL () {
        $URL = find_the_URL('linked');
        if ($URL !== '') {
            $string_output = '
              <a href="' . $URL . '" title="linked_in_URL" target="_blank">
                <img src="' . plugin_dir_url(__FILE__) . '/img/url.png" style="width: 35px;"/>
              </a>';
            echo $string_output;
        }
    }

    function the_Social_Block () {
    	$social_media = array('linked', 'twitter', 'xing', 'facebook', 'vcard');
    	$social_media_length = sizeof($social_media);
    	$URL = array();

    	for ($i = 0; $i < $social_media_length; $i++) {
    		
    		$URL[$i] = find_the_URL($social_media[$i]);

    		if ($URL[$i] !== '') {
	            $string_output = '
	              <a href="' . $URL[$i] . '" title="linked_in_URL" target="_blank">
	                <img src="' . plugin_dir_url(__FILE__) . '/img/logo_cards-' . $social_media[$i] . '.png"/>
	              </a>';
	            echo $string_output;
	        }
    	}

    	?><pre><?php print_r($URL); ?></pre><?php
    }

    function the_city () {
    	$city = get_post_meta(get_the_id(), 'city_box', true);
    	if ($city !== '') {
    		echo $city;
    	}
    }
}

add_action('the_linkedin_URL', 'the_linkedin_URL_cb');

?>