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
		esc_html__('LinkedIn URL', 'linkedin_plugin' ),
		'linked_in_box_cb',
		$global_name,
		'normal',
		'default'
	);
}


/* display meta box */
function linked_in_box_cb ($object, $box) {
	wp_nonce_field( basename( __FILE__ ), 'linked_in_box_nonce' );
	wp_nonce_field( basename( __FILE__ ), 'facebook_in_box_nonce' );
	wp_nonce_field( basename( __FILE__ ), 'twitter_in_box_nonce' );
?>
	<p>
	    <label for="linked_in_box"><?php _e( "Add your LinkedIn's personal site URL.", 'linkedin_plugin' ); ?></label>
	    <br />
	    <input class="widefat" type="text" name="linked_in_box" id="linked_in_box" value="<?php echo esc_url(get_post_meta( $object->ID, 'linked_in_box', true )); ?>" size="30" placeholder="your URL here, please..."/>
  	</p>
  	<p>
	    <label for="twitter_in_box"><?php _e( "Add your Twitter's personal site URL.", 'linkedin_plugin' ); ?></label>
	    <br />
	    <input class="widefat" type="text" name="twitter_in_box" id="twitter_in_box" value="<?php echo esc_url(get_post_meta( $object->ID, 'twitter_in_box', true )); ?>" size="30" placeholder="your URL here, please..."/>
  	</p>
  	<p>
	    <label for="facebook_in_box"><?php _e( "Add your Facebook's personal site URL.", 'linkedin_plugin' ); ?></label>
	    <br />
	    <input class="widefat" type="text" name="facebook_in_box" id="facebook_in_box" value="<?php echo esc_url(get_post_meta( $object->ID, 'facebook_in_box', true )); ?>" size="30" placeholder="your URL here, please..."/>
  	</p>
<?php
}

/* save meta box */
function save_linked_in_metabox ($post_id, $post) {



	$fields_array = array('linked', 'twitter', 'facebook');
	$post_type = get_post_type_object( $post->post_type );

	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) { return $post_id; }

	foreach ($fields_array as $field) {
		
		if ( !isset( $_POST[$field . '_box_nonce'] ) || !wp_verify_nonce($field . '_box_nonce', basename( __FILE__ ) ) ) { return $post_id; }

		$new_meta_value = ( isset( $_POST[$field . '_box'] ) ? $_POST[$field . '_box'] : '' );
		$meta_key = $field . '_box';
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		if ( $new_meta_value && '' == $meta_value ) {
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );
		} elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
			update_post_meta( $post_id, $meta_key, $new_meta_value );
		} elseif ( '' == $new_meta_value && $meta_value ) { 
			delete_post_meta( $post_id, $meta_key, $meta_value );
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
*   DEFINITION OF ACTION TO BE DONE
*/

function the_linkedin_URL_cb () {

    function find_the_URL () {
        
        $orig_bid = get_current_blog_id();
        $post_slug = get_the_title(get_the_id());
        $bids = array(1, 2, 3, 4, 5, 6);
        $linkedin_URL_output = '';

        foreach ($bids as $bid) {
          switch_to_blog($bid);
          $args = array('post_type' => 'portfolio', 'meta_key' => 'linked_in_box');
          $query = new WP_Query( $args );
          $size = sizeof($query->posts);
          
          for ($i = 0; $i < $size; $i++) {
            $title = $query->posts[$i]->post_title;
            if ($title == $post_slug) {
              $linkedin_URL_output = get_post_meta( $query->posts[$i]->ID, 'linked_in_box', true );
            }
          }
          
          wp_reset_query();
        }
        
        restore_current_blog();
        return $linkedin_URL_output;
    }

    function display_the_URL () {
        $URL = find_the_URL();
        echo $URL;
    }
    

    function the_URL () {
        $URL = find_the_URL();
        if ($URL !== '') {
            $string_output = '
              <a href="' . $URL . '" title="linked_in_URL" target="_blank">
                <img src="' . plugin_dir_url(__FILE__) . '/img/url.png" style="width: 35px;"/>
              </a>';
            echo $string_output;
        }
    }
}

add_action('the_linkedin_URL', 'the_linkedin_URL_cb');

?>