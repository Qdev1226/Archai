<?php
$groups = $GLOBALS['ec_categories']->get_categories( $parentid );
$ec_cv_index = 0;
foreach ( $groups as $category_item ) {
	$category = new ec_category( $category_item );
	if ( $ec_cv_index % $columns == 0 ) { ?>
		<div class="ec_category_view_list">
	<?php }?>
	<div class="ec_category_li" style="width:<?php echo esc_attr(100/$columns); ?>%;">
		<div class="ec_category_view_image">
			<a href="<?php echo esc_attr( $category->get_category_link( ) ); ?>"><img src="<?php echo esc_attr( $category->get_image( ) ); ?>" alt="<?php echo wp_easycart_language( )->convert_text( $category->options->category_name ); ?>" /></a>
		</div>
		<div class="ec_category_view_data">
			<h3><a href="<?php echo esc_attr( $category->get_category_link( ) ); ?>"><?php echo wp_easycart_language( )->convert_text( $category->options->category_name ); ?></a></h3>
			<p><?php echo wp_easycart_escape_html( $category->options->short_description ); ?></p>
			<?php if( isset( $category->options->children ) ){ ?>
			<ul>
				<?php foreach( $category->options->children as $child_item ){ 
				$child = new ec_category( $child_item );
				?>
				<li><a href="<?php echo esc_attr( $child->get_category_link( ) ); ?>"><?php echo wp_easycart_language( )->convert_text( $child->options->category_name ); ?></a></li>
				<?php } ?>
			</ul>
			<?php }?>
		</div>
	</div>
	<?php if ( $ec_cv_index % $columns == $columns - 1 ) { ?>
		</div>
	<?php }
	$ec_cv_index++;
} ?>
<?php if ( $ec_cv_index % $columns != 0 ) { 
	while ( $ec_cv_index % $columns != 0 ) { ?>
		<div class="ec_category_li" style="width:<?php echo esc_attr(100/$columns); ?>%;"></div>
		<?php $ec_cv_index++;
	} ?>
	</div>
<?php } ?>