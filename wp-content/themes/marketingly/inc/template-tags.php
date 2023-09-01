<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package marketingly
 */

/**
|------------------------------------------------------------------------------
| Related Posts
|------------------------------------------------------------------------------
|
| You can show related posts by Categories or Tags. 
| It has two options to show related posts
|
| 1. Thumbnail related posts (default)
| 2. List of related posts
| 
| @return void
|
*/

if ( ! function_exists('marketingly_related_posts') ) :

	function marketingly_related_posts() {
		
		global $post;

		$taxonomy = 'cat';
		$numberRelated = 9;

		$args =  array();

		if ( $taxonomy == 'tag' ) {

			$tags = wp_get_post_tags( $post->ID );
			$arr_tags = array();
			
			foreach( $tags as $tag ) {
				
				array_push($arr_tags, $tag->term_id);

			}
			
			if ( ! empty($arr_tags) ) { 
			    $args = array(
				    'tag__in'			=> $arr_tags,  
				    'post__not_in'		=> array($post->ID),  
				    'posts_per_page'	=> $numberRelated,
			    ); 
			}

		} else {

			 $args = array( 
			 	'category__in'		=> wp_get_post_categories($post->ID), 
			 	'posts_per_page'	=> $numberRelated, 
			 	'post__not_in'		=> array($post->ID) 
			 );

		}

		if ( ! empty( $args ) ) {
			
			$posts = get_posts( $args );

			if ( $posts ) {
			?>

			<div class="fbox posts-related clearfix">
				
				<div class="swidget">
					<h3 class="related-title"><?php esc_html_e('Related Post', 'marketingly') ?></h3>
				</div>
				
				<?php
					$related_style = 'grid';
					if ( $related_style == 'grid' ) :
				?>
					
					<ul class="grid-related-posts">
						
						<?php
						foreach ( $posts as $p ) {
						?>
							
							<li>

								<div class="thumbnail">
									<?php if ( has_post_thumbnail( $p->ID ) ) : ?>
									
										<a href="<?php echo esc_url( get_the_permalink( $p->ID ) ) ?>">
											<?php echo get_the_post_thumbnail( $p->ID, 'marketingly-small' ); ?>
										</a>

									<?php else : ?>

										<a href="<?php echo esc_url( get_the_permalink( $p->ID ) ) ?>">
											<img class="wp-post-image" src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/nothumb-related-posts.jpg" />
										</a>

									<?php endif; ?>
								</div>
								
								<a href="<?php echo esc_url( get_the_permalink( $p->ID ) ) ?>"><?php echo esc_html( get_the_title( $p->ID ) ) ?></a>
							
							</li>

						<?php
							}
						?>

					</ul>

				<?php
					elseif ( $related_style == 'list' ) :
				?>

					<ul class="list-related-posts">
						
						<?php
						foreach ( $posts as $p ) {
						?>
							
							<li>
										
								<?php if ( has_post_thumbnail( $p->ID ) ) : ?>
									<div class="featured-thumbnail">
										<a href="<?php echo esc_url( get_the_permalink( $p->ID ) ) ?>">
											<?php echo get_the_post_thumbnail( $p->ID, 'marketingly-small' ) ?>
										</a>
									</div>

								<?php else : ?>
									
									<div class="featured-thumbnail">
										<a href="<?php echo esc_url( get_the_permalink( $p->ID ) ) ?>">
											<img class="wp-post-image" src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/nothumb-related-posts.jpg" />
										</a>
									</div>

								<?php endif; ?>

								<div class="related-data">
									<a href="<?php echo esc_url( get_the_permalink( $p->ID ) ) ?>"><?php echo esc_html( get_the_title( $p->ID ) ) ?></a>
									<?php the_excerpt(); ?>
								</div>
							
							</li>

						<?php
							}
						?>

					</ul>

					<?php
						else :
					?>

					<ul class="nothumb-related-posts">
						
						<?php
						foreach ( $posts as $p ) {
							?>
							
							<li>												
								
								<a href="<?php echo esc_url( get_the_permalink($p->ID) ) ?>"><?php echo get_the_title($p->ID) ?></a>								
								
							</li>

						<?php
							}
						?>
					</ul>

					<?php endif; ?>

				</div>
			
				<?php
			}
		}
	}
endif;


if ( ! function_exists( 'marketingly_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function marketingly_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'marketingly' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'marketingly' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'marketingly_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function marketingly_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'marketingly' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'marketingly' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'marketingly' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'marketingly' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'marketingly' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					esc_html( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'marketingly' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				esc_html( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;


/**
|------------------------------------------------------------------------------
| Related Posts
|------------------------------------------------------------------------------
|
| You can show rander slideshow by Categories or Tags. 
| It has two options to show rander_slideshow
|
| 1. Rander Slideshow
| 
| @return void
|
*/

if ( ! function_exists('marketingly_rander_slideshow') ) :

	function marketingly_rander_slideshow() {

		$limit_slide = 4;
		$cats = 0;

		$args = array(
			'category__in'		=> $cats,
			'posts_per_page'	=> $limit_slide
		);
		
		$slide_posts = new WP_Query( $args );

		if ( $slide_posts->have_posts() ) :

		?>
			
		<div id="homepage-slide" class="flexslider">

			<ul class="slides">
				<?php /* Start the Loop */ ?>
				<?php 
					while ( $slide_posts->have_posts() ) : $slide_posts->the_post();
				?>

					<li>

						<?php if ( has_post_thumbnail() ) : ?>
						
							<a href="<?php the_permalink() ?>">
								<?php the_post_thumbnail('marketingly-lite-slider') ?>
							</a>

							<p class="flex-caption">
								<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
							</p>
						
						<?php else : ?>
							
							<a href="<?php the_permalink() ?>">
								<img class="wp-post-image" src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/marketingly-lite-slider.jpg" />
							</a>

							<p class="flex-caption">
								<a href="<?php the_permalink() ?>"><?php the_title() ?></a>
							</p>

						<?php endif; ?>

					</li>
			
				<?php
				endwhile;
				?>
			</ul>

		</div>

		<?php
		endif;
		wp_reset_postdata();

	}
endif;