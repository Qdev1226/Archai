<?php get_header(); ?>

<?php
	$post_types = wptouch_fdn_get_search_post_types();
	foreach( $post_types as $key => $post_type ) {
		global $search_post_type;
		$search_post_type = $post_type;
		$search_results[ $post_type ] = new WP_Query( $query_string . '&post_type=' . $post_type . '&max_num_pages=10&posts_per_page='. foundation_number_of_posts_to_show() .'' );
		if ( !$search_results[ $post_type ]->have_posts() ) {
			unset( $post_types[ $key ] );
			unset( $search_results[ $post_type ] );
		}
	}
?>

<div id="content" class="search">

	<div class="post-page-head-area bauhaus">
		<h2 class="post-title heading-font"><?php echo sprintf( __( 'You searched for "%s"', 'wptouch-pro' ), esc_attr( $_GET['s'] ) ); ?>:</h2>
		<?php if ( count( $post_types ) > 1 && empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) { ?>
			<span class="select-wrap">
				<select class="search-select heading-font">
					<?php
						foreach( $post_types as $post_type ) { global $search_post_type; $search_post_type = $post_type;
					?>
						<option data-section="<?php echo str_replace( ' ', '-', strtolower( wptouch_fdn_get_search_post_type() ) ); ?>">
							<?php echo sprintf( __( 'Show %s Results', 'wptouch-pro' ), wptouch_fdn_get_search_post_type() ); ?>
						</option>
					<?php } ?>
				</select>
				<i class="wptouch-icon-caret-down"></i>
			</span>
		<?php } ?>
	</div>

	<?php
		foreach( $post_types as $post_type ) { global $search_post_type; $search_post_type = $post_type;
			$query = $search_results[ $post_type ];
	?>

	<div id="<?php echo str_replace( ' ', '-', strtolower( wptouch_fdn_get_search_post_type() ) ); ?>-results">
			<?php if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post(); ?>

			<?php get_template_part( 'post-loop' ); ?>

			<?php } // $query ?>

			<?php } else { ?>

				<?php if ( empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) { ?>
					<span class="no-results">
						<?php _e( 'No results found', 'wptouch-pro' ); ?>.
					</span>
				<?php } ?>

			<?php } ?>

	<?php if ( get_next_posts_link() ) { ?>
		<?php
		if ( in_array( strtolower( $search_post_type ), array( 'post', 'page' ) ) ) {
			$classes = 'load-more-' . strtolower( $search_post_type ). '-link';
		} else {
			$classes = 'load-more-custom-link';
		} ?>
		<a class="<?php echo $classes; ?> no-ajax" href="javascript:return false;" rel="<?php echo get_next_posts_page_link(); ?>" data-lang-type="<?php echo str_replace( ' ', '-', strtolower( wptouch_fdn_get_search_post_type() ) ); ?>">
			<?php echo strtolower( sprintf( __( "Load more %s results", 'wptouch-pro' ), wptouch_fdn_get_search_post_type() ) ); ?>&hellip;
		</a>
	<?php } ?>
	</div>

	<?php } ?>

	<?php if ( count( $post_types ) == 0 && empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) { ?>
		<span class="no-results">
			<?php _e( 'No results found', 'wptouch-pro' ); ?>.
		</span>
	<?php } ?>

</div> <!-- content -->

<?php get_footer(); ?>
