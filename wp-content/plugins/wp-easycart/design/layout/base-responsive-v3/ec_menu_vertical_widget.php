<div>
	<ul class="ec_menu_vertical">
		<?php for($i=0; $i<$menu->level1_count(); $i++){?>
		<li><a href="<?php if( $menu->level2_count( $i ) > 0 ){ echo "#"; }else{ $menu->display_menulevel1_link( $i ); } ?>" id="<?php echo esc_attr( "menu" . $menu->get_menulevel1_id( $i ) ); ?>"><?php $menu->display_menulevel1_name( $i ); ?></a>
		<?php if( $menu->level2_count( $i ) > 0 ){?><ul><?php }?>
			<?php for($j=0; $j<$menu->level2_count( $i ); $j++){?>
			<li><a href="<?php if( $menu->level3_count( $i, $j ) > 0 ){ echo "#"; }else{ $menu->display_menulevel2_link( $i, $j ); } ?>" class="menu_link" id="<?php echo esc_attr( "submenu" . $menu->get_menulevel2_id( $i, $j ) ); ?>"><?php $menu->display_menulevel2_name( $i, $j ); ?></a>
			<?php if( $menu->level3_count( $i, $j ) > 0 ){?><ul><?php }?>
				<?php for($k=0; $k<$menu->level3_count( $i, $j ); $k++){?>
				<li><a href="<?php $menu->display_menulevel3_link( $i, $j, $k ); ?>" class="menu_link" id="<?php echo esc_attr( "subsubmenu" . $menu->get_menulevel3_id( $i, $j, $k ) ); ?>"><?php $menu->display_menulevel3_name( $i, $j, $k ); ?></a></li>
				<?php } ?>
			<?php if( $menu->level3_count( $i, $j ) > 0 ){?></ul><?php }?>
			</li>
			<?php } ?>
		<?php if( $menu->level2_count( $i ) > 0 ){?></ul><?php }?>
		</li>
		<?php }?>
	</ul>
</div>
<div style="clear:both"></div>
<?php if( isset( $menu_id2 ) && $menu_id2 != 0 ){ ?>
<script>
jQuery(document).ready(function() {
	jQuery("#<?php echo esc_attr( $level2 . $menu_id2 ); ?>").trigger( "click" );
});
</script>
<?php }?>
<?php if( $menu_id != 0 ){ ?>
<script>
jQuery(document).ready(function() {
	jQuery("#<?php echo esc_attr( $level . $menu_id ); ?>").trigger( "click" );
});
</script>
<?php }?>