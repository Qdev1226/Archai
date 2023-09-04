<div class="ec_pricepoint_widget">
	<?php for($i=0; $i<count( $pricepoints ); $i++){ ?>
  		<?php if( $pricepoints[$i]->is_less_than ){ ?>
        	<?php if( $pricepoints[$i]->product_count_below > 0 ){ ?>
            	<div>
                	<a href="<?php echo esc_attr( $filter->get_link_string( 4 ) ) . "&amp;pricepoint=" . esc_attr( $pricepoints[$i]->pricepoint_id ); ?>" class="menu_link"><?php if( isset( $_GET['pricepoint'] ) && $_GET['pricepoint'] == $pricepoints[$i]->pricepoint_id ){ echo "<b>"; } ?><?php echo wp_easycart_language( )->get_text( 'ec_pricepoint_widget', 'less_than' )?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $pricepoints[$i]->high_point ) ); ?> (<?php echo esc_attr( $pricepoints[$i]->product_count_below ); ?>)<?php if( isset( $_GET['pricepoint'] ) && $_GET['pricepoint'] == $pricepoints[$i]->pricepoint_id ){ echo "</b>"; } ?></a>
                    <?php if( isset( $_GET['pricepoint'] ) && $_GET['pricepoint'] == $pricepoints[$i]->pricepoint_id ){ ?>
                    <a href="<?php echo esc_attr( $filter->get_link_string( 4 ) ); ?>" class="menu_link"> X </a>
                    <?php }?>
                </div>
            <?php }?>
        <?php }else if( $pricepoints[$i]->is_greater_than ){?>
        	<?php if( $pricepoints[$i]->product_count_above > 0 ){ ?>
            	<div>
                	<a href="<?php echo esc_attr( $filter->get_link_string( 4 ) ) . "&amp;pricepoint=" . esc_attr( $pricepoints[$i]->pricepoint_id ); ?>" class="menu_link"><?php if( isset( $_GET['pricepoint'] ) && $_GET['pricepoint'] == $pricepoints[$i]->pricepoint_id ){ echo "<b>"; } ?><?php echo wp_easycart_language( )->get_text( 'ec_pricepoint_widget', 'greater_than' )?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $pricepoints[$i]->low_point ) ); ?> (<?php echo esc_attr( $pricepoints[$i]->product_count_above ); ?>)<?php if( isset( $_GET['pricepoint'] ) && $_GET['pricepoint'] == $pricepoints[$i]->pricepoint_id ){ echo "</b>"; } ?></a>
                    <?php if( isset( $_GET['pricepoint'] ) && $_GET['pricepoint'] == $pricepoints[$i]->pricepoint_id ){ ?>
                    <a href="<?php echo esc_attr( $filter->get_link_string( 4 ) ); ?>" class="menu_link"> X </a>
                    <?php }?>
                </div>
            <?php }?>
        <?php }else{ ?>
			<?php if( $pricepoints[$i]->product_count_between > 0 ){ ?>
            	<div>
                	<a href="<?php echo esc_attr( $filter->get_link_string( 4 ) ) . "&amp;pricepoint=" . esc_attr( $pricepoints[$i]->pricepoint_id ); ?>" class="menu_link"><?php if( isset( $_GET['pricepoint'] ) && $_GET['pricepoint'] == $pricepoints[$i]->pricepoint_id ){ echo "<b>"; } ?><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $pricepoints[$i]->low_point ) ); ?> - <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $pricepoints[$i]->high_point ) ); ?> (<?php echo esc_attr( $pricepoints[$i]->product_count_between ); ?>)<?php if( isset( $_GET['pricepoint'] ) && $_GET['pricepoint'] == $pricepoints[$i]->pricepoint_id ){ echo "</b>"; } ?></a>
                    <?php if( isset( $_GET['pricepoint'] ) && $_GET['pricepoint'] == $pricepoints[$i]->pricepoint_id ){ ?>
                    <a href="<?php echo esc_attr( $filter->get_link_string( 4 ) ); ?>" class="menu_link"> X </a>
                    <?php }?>
                </div>
            <?php }?>
		<?php }?>
  <?php }?>
</div>