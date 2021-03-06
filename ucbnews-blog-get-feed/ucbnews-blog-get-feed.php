<?php  
/*
Plugin Name:  UCB News Blog Get Feed
Plugin URI: http://news.berkeley.edu
Description: Imports blog posts from RSS feed into a post in the news center and puts them into a Blog Post type
Author: Public Affairs
Version: 1.0

3. RSS pulled by NC
4. NC checks for dupes with title and date
5. NC creates new post and imports data from RSS
6. NC posts and updates megamenu
http://berkeleyblog.staging.wpengine.com/wp-content/uploads/news_center.xml
https://blogs.berkeley.edu/wp-content/uploads/news_center.xml
*/


function check_for_duplicate_blog_items($single_post_title, $single_post_date){
	if( null == get_page_by_title( html_entity_decode($single_post_title), OBJECT, 'blog_item') ){
		error_log('title was not matched');
		return false;
	} 
	return true;
} //check for duplicate blog items

function import_blog_item_into_db($blog_item){
	global $wpdb;
	
	//make post and insert stuff
	$post_id = wp_insert_post(
		array(
			'post_title' => $blog_item->title,
			'post_content' => $blog_item->content,
			'post_date' => $blog_item->pub_date,
			'post_category' => array( 4804, 4847 ),
			'post_type' => 'blog_item',
			'post_status' => 'publish',
            'post_author' => 0,
		)
	);
	
	$b_author = (string)$blog_item->author;
	update_post_meta( $post_id, 'blog_author', $b_author );
	
	$b_author_link = (string)$blog_item->author_link;
	update_post_meta( $post_id, 'blog_author_link', $b_author_link );
	
	$b_image = (string)$blog_item->image_link;
	update_post_meta( $post_id, 'blog_post_image', $b_image );
	
	$b_link = (string)$blog_item->link;
	update_post_meta( $post_id, 'blog_post_link', $b_link );
	
	return $post_id;
}




//get feed items from feed
function get_feed_items(){
error_log('get_feed_items');
	include_once( ABSPATH . WPINC . '/feed.php' );
error_log('included feed.php');
	$rss = fetch_feed( 'https://blogs.berkeley.edu/*****.xml' );
if (is_wp_error($rss)) {
error_log('feed ' . $rss->get_error_message());
}
else {
	$rss_items = $rss->get_items(0, 0);
	
	$items = NULL;
	$index = 0;
error_log('looping through rss items');
	foreach($rss_items as $item){
			$author = $item->get_item_tags('', 'author');
			$author_link = $item->get_item_tags('', 'alink');
			$image_link = $item->get_item_tags('', 'ilink');
			$content = $item->get_item_tags('', 'content');
			$title = $item->get_title();
			$pub_time = explode(", ", $item->get_date());
			$date = new DateTime($pub_time[0]);
			$time = new DateTime($pub_time[1]);
			if( check_for_duplicate_blog_items($title, date_format($date, 'Y-m-d')) == false ) {
				$items[$index]->title = $title;
				$items[$index]->link = $item->get_link();
				$items[$index]->pub_date = date_format($date, 'Y-m-d'); //$date_time; //date( 'n-j-Y', strtotime( $item->get_date() ) );
				$items[$index]->author = $author[0]["data"];
				$items[$index]->author_link = $author_link[0]["data"];
				$items[$index]->image_link = $image_link[0]["data"];
				$items[$index]->content = $content[0]["data"];
				
				$index++;
			} else { error_log('false'); }
	}
	
	return $items;
}
}




function import_posts($item_arr){
	include_once( ABSPATH . 'wp-admin/includes/post.php' );
	
	foreach($item_arr as $item){

		if( $post_id = post_exists( $item->title, $item->content, $item->pub_date ) ) {
			error_log( 'post already imported ' . $post_id, 0);
		} else {
			$query = new WP_Query( array( 'post_type' => 'blog_item' ) );
			$post_id = import_blog_item_into_db($item);
		} //if exists
		
		if ( is_wp_error( $post_id ) )
			error_log('post import error', 0);
		if ( !$post_id ) {
			error_log('post id not here', 0);
		} //end if 
	} //foreach
}





function my_fetched_feed(){
	//get the feed items and return as an object
	$blog_items = get_feed_items();
	if (!is_null($blog_items))
		import_posts($blog_items);
}
?>