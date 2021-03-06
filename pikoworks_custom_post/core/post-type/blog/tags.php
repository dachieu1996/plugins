<?php
//start post count 

// function to display number of posts.
function wooxon_getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count;
} 
// function to count views.
function wooxon_setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
} 
// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'wooxon_posts_column_views');
add_action('manage_posts_custom_column', 'wooxon_posts_custom_column_views',5,2);
function wooxon_posts_column_views($defaults){
    $defaults['post_views'] = esc_html__('Views', 'pikoworks-core');
    return $defaults;
}
function wooxon_posts_custom_column_views($column_name, $id){
    if($column_name === 'post_views'){
        echo wooxon_getPostViews(get_the_ID());
    }
}
//end start post count 

/**
 *  Post Love
 */

function wooxon_post_love_display() {    		
    $love = get_post_meta( get_the_ID(), 'post_love', true );
    $love = ( empty( $love ) ) ? 0 : $love;
    echo '<a class="love-button" href="' . admin_url( 'admin-ajax.php?action=wooxon_post_love_add_love&post_id=' . get_the_ID() ) . '" data-id="' . get_the_ID() . '">'. esc_html__('Love: ', 'pikoworks_custom_post') .'<span id="love-count">' . esc_attr($love) . '</span></a>'; 
}

add_action( 'wp_ajax_nopriv_wooxon_post_love_add_love', 'wooxon_post_love_add_love' );
add_action( 'wp_ajax_wooxon_post_love_add_love', 'wooxon_post_love_add_love' );

function wooxon_post_love_add_love() {
	$love = get_post_meta( $_REQUEST['post_id'], 'post_love', true );
	$love++;
	update_post_meta( $_REQUEST['post_id'], 'post_love', $love );
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
		echo esc_attr($love);
		die();
	}
	else {
		wp_redirect( get_permalink( $_REQUEST['post_id'] ) );
		exit();
	}
}