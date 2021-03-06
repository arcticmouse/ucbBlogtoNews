<?php
/**
 * The Template for displaying all single jump story posts.
 *
 */

get_header(); ?>

	<?php yt_before_primary(); ?>
	
	<div id="primary" <?php yt_section_classes( 'content-area', 'primary' );?>>
		
		<?php yt_primary_start(); ?>
		
		<main id="content" <?php yt_section_classes( 'site-content', 'content' );?> role="main">
		
		<?php yt_before_loop(); ?>
		
		<?php while ( have_posts() ) : the_post(); ?>
		
			<?php yt_loop_start(); ?>
			
					<?php
/**
 * @package yeahthemes
 */
 
	$format = get_post_format();
	
	if( false === $format )
		$format = 'standard';
	
	$formats_meta = yt_get_post_formats_meta( get_the_ID());
	
	/**
	 *Quote format
	 */
	$quote_author = !empty( $formats_meta['_format_quote_source_name'] )  ? '<cite class="entry-format-meta margin-bottom-30">' . $formats_meta['_format_quote_source_name'] . '</cite>' : '';
	$quote_author = !empty( $formats_meta['_format_quote_source_url'] ) ? sprintf( '<cite class="entry-format-meta margin-bottom-30"><a href="%s">%s</a></cite>', $formats_meta['_format_quote_source_url'], $formats_meta['_format_quote_source_name'] ) : $quote_author;
	
	/**
	 *Link format
	 */
	$share_url = !empty( $formats_meta['_format_link_url'] ) ? $formats_meta['_format_link_url'] : get_permalink( get_the_ID() );
	
	//print_r($formats_meta);
	$share_url_text = !empty( $formats_meta['_format_link_url'] )  
		? sprintf( '<p class="entry-format-meta margin-bottom-30">%s <a href="%s">#</a></p>',
			$formats_meta['_format_link_url'],
			get_permalink( get_the_ID() ) )
		: '';
	
	/**
	 *Extra class for entry title
	 */
	$entry_title_class = ' margin-bottom-30';
	if( 'quote' === $format  && $quote_author 
		|| 'link' === $format  && $share_url_text 
	){
		$entry_title_class = '';
	}
	
	
	$entry_title = get_the_title( get_the_ID() );
	if( 'link' === $format  && $share_url_text  ){
		$entry_title = sprintf('<a href="%s" title="%s" target="_blank" rel="external" class="secondary-2-primary">%s</a>', $share_url, get_the_title( get_the_ID() ), get_the_title( get_the_ID() ) );
	}

	$feature_image = yt_get_options('blog_single_post_featured_image');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'yt_before_single_post_entry_header' );?>

	<header class="entry-header story-header">

		<?php do_action( 'yt_single_post_entry_header_start' );?>
<div class="category">
<?php
$categories = get_the_category();
$separator = ', ';
$output = '';

if($categories){
	foreach($categories as $category) {
		if (strtolower($category->name) != 'web general') {
			if( $category->name == 'Berkeley Blog') {
				$cat_link_output = 'http://blogs.berkeley.edu';
			} else {
				$cat_link_output = get_category_link( $category->term_id );
			}
			$output .= '<a href="'. $cat_link_output .'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
		}
	}
echo trim($output, $separator);
}
?></div>


		<h1 class="entry-title <?php echo $entry_title_class; ?>"><?php echo $entry_title; ?></h1>
		
		<?php echo 'quote' === $format ? $quote_author : '';?>
		<?php echo 'link' === $format ? $share_url_text : '';?>
<div class="story-header-meta">
<p class="byline">

                        <?php 
							$author = get_post_meta($post->ID, 'blog_author', true);
							$author_link = get_post_meta($post->ID, 'blog_author_link', true);
							
							echo __('From the Berkeley Blog, by ', 'textdomain');
							
							if(!empty($author)) {  
								if(!empty($author_link)) {
									echo '<a href="' . $author_link . '">' . $author . '</a>';
								} else {
									echo $author;
								}
							} else {
								echo '<a href="http://blogs.berkeley.edu/">The Berkeley Blog</a>';
							}
						?>                        
                        
                        <span class="pipe">|</span> <?php
					$time_string = '<time class="published ' . ( get_the_time( 'U' ) == get_the_modified_time( 'U' ) ? 'updated' : '' ) . '" datetime="%1$s">%2$s</time>';
					if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
						$time_string .= '<time class="updated hidden" datetime="%3$s">%4$s</time>';
				
					$time_string = sprintf( $time_string,
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date() ),
						esc_attr( get_the_modified_date( 'c' ) ),
						esc_html( get_the_modified_date() )
					);
					echo $time_string;
				?>
 </p> 
 <?php echo sharing_display(); ?>
</div>


		<?php if( 'hide' !== yt_get_options( 'blog_post_meta_info_mode' )): ?>
		<div class="entry-meta margin-bottom-30 hidden-print" style="display:none">
			<span class="posted-on">
				
				<?php
					$time_string = '<time class="entry-date published ' . ( get_the_time( 'U' ) == get_the_modified_time( 'U' ) ? 'updated' : '' ) . 'pull-left" datetime="%1$s">%2$s</time>';
					if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
						$time_string .= '<time class="updated hidden" datetime="%3$s">%4$s</time>';
				
					$time_string = sprintf( $time_string,
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date() ),
						esc_attr( get_the_modified_date( 'c' ) ),
						esc_html( get_the_modified_date() )
					);
					echo $time_string;
				?>
			</span>
			<span class="byline">

				<span class="author vcard">
					<a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
						<?php echo esc_html( get_the_author() ); ?>
					</a>
				</span>
			</span>			
		</div><!-- .entry-meta -->
		<?php endif?>

		<?php do_action( 'yt_single_post_entry_header_end' );?>
	</header><!-- .entry-header -->

	<?php do_action( 'yt_before_single_post_entry_content' );?>

	<div class="entry-content">

		<?php 
			$image = get_post_meta($post->ID, 'blog_post_image', true);
			//echo '<img src="' . $image . '" width="750" height="500" alt="Berkeley Blog Image" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" size="(max-width: 750px) 100vw, 750px">';
		?>
        <div class="blog_content"><?php the_content(); ?></div>
        
        <div class="jump-story-container"><?
			$post_link = get_post_meta($post->ID, 'blog_post_link', true);
			
			echo '<a href="' . $post_link . '" />' . __('Join the conversation', 'textdomain') . '</a>';
		?></div>

	</div><!-- .entry-content -->
	
	<?php do_action( 'yt_before_single_post_entry_footer' );?>

	<?php
	
	/*Get current post's tags*/
	$tag_list = '';
	$tags = wp_get_post_tags( $post->ID );
	$tag_divider = '';
	if ($tags) {
		$tag_ids = array();
		foreach ( $tags as $tag) {
			if (strtolower($tag->name) != 'press release' && strtolower($tag->name) != 'media advisory') {
				$tag_list .= $tag_divider;
				$tag_list .= '<a rel="tag" href="/topics/'. $tag->slug.'">'.$tag->name.'</a>'; 
				$tag_divider = ', ';
			}			
		}
	}
	if ( $tag_list ) :
	
	?>

        <footer class="entry-meta hidden-print">
    
            <?php do_action( 'yt_single_post_entry_footer_start' );?>
            
        <?php 
            echo '<div> <strong class="tag-heading">Topics: </strong>'. $tag_list . '</div>';
        ?>

            
            <?php do_action( 'yt_single_post_entry_footer_end' );?>
        </footer><!-- .entry-meta -->
	<?php endif;?>


	<?php do_action( 'yt_after_single_post_entry_footer' );?>
	<!-- noptimize --><script type="text/html" data-role="header .entry-meta"><?php if( function_exists( 'yt_post_meta_description' )) yt_post_meta_description(); ?></script><!-- /noptimize -->
	
</article><!-- #post-<?php the_ID(); ?>## -->

			
			<?php yt_loop_end(); ?>

		<?php endwhile; // end of the loop. ?>
		
		<?php yt_after_loop(); ?>

		</main><!-- #content -->
	
		<?php yt_primary_end(); ?>
		
	</div><!-- #primary -->
	
	<?php yt_after_primary(); ?>
    
	<div class="footer-featured">

			<div class="container">
	<!-- start full width featured posts -->
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('fullwidth') ) : ?>
  <!-- This will be displayed if the sidebar is empty -->
			<?php endif; ?>
            </div>
            
	</div>
<?php get_footer(); ?>