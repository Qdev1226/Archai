<div class="ec_menu_horizontal">
  <ul>
  <?php for($i=0; $i<$menu->level1_count(); $i++){?>
  	<li><a href="<?php $menu->display_menulevel1_link( $i ); ?>"<?php if( $menu->level2_count( $i ) > 0 ){ echo " class=\"has-submenu\""; } ?>><?php $menu->display_menulevel1_name( $i ); ?></a>
	<?php if( $menu->level2_count( $i ) > 0 ){?><ul><?php }?>
	<?php for($j=0; $j<$menu->level2_count( $i ); $j++){?>
    	<li><a href="<?php $menu->display_menulevel2_link( $i, $j ); ?>"<?php if( $menu->level3_count( $i, $j ) > 0 ){ echo " class=\"has-submenu\""; } ?>><?php $menu->display_menulevel2_name( $i, $j ); ?></a>
            <?php if( $menu->level3_count( $i, $j ) > 0 ){?><ul><?php }?>
			<?php for($k=0; $k<$menu->level3_count( $i, $j ); $k++){?>
    		<li><a href="<?php $menu->display_menulevel3_link( $i, $j, $k ); ?>"><?php $menu->display_menulevel3_name( $i, $j, $k ); ?></a></li>
            <?php } ?>
            <?php if( $menu->level3_count( $i, $j ) > 0 ){?></ul><?php }?>
        </li>
    <?php } ?>
    <?php if( $menu->level2_count( $i ) > 0 ){?></ul><?php }?>
    </li>
  <?php }?>
  </ul>
</div>
<div class="ec_menu_mobile_horizontal">
    <div>
    <ul class="ec_menu_vertical">
    <?php for($i=0; $i<$menu->level1_count(); $i++){?>
    <li><a href="<?php if( $menu->level2_count( $i ) > 0 ){ echo "#"; }else{ $menu->display_menulevel1_link( $i ); } ?>"><?php $menu->display_menulevel1_name( $i ); ?></a>
    <?php if( $menu->level2_count( $i ) > 0 ){?><ul><?php }?>
    <?php for($j=0; $j<$menu->level2_count( $i ); $j++){?>
        <li><a href="<?php if( $menu->level3_count( $i, $j ) > 0 ){ echo "#"; }else{ $menu->display_menulevel2_link( $i, $j ); } ?>" class="menu_link"><?php $menu->display_menulevel2_name( $i, $j ); ?></a>
            <?php if( $menu->level3_count( $i, $j ) > 0 ){?><ul><?php }?>
            <?php for($k=0; $k<$menu->level3_count( $i, $j ); $k++){?>
            <li><a href="<?php $menu->display_menulevel3_link( $i, $j, $k ); ?>" class="menu_link"><?php $menu->display_menulevel3_name( $i, $j, $k ); ?></a></li>
            <?php } ?>
            <?php if( $menu->level3_count( $i, $j ) > 0 ){?></ul><?php }?>
        </li>
    <?php } ?>
    <?php if( $menu->level2_count( $i ) > 0 ){?></ul><?php }?>
    </li>
    <?php }?>
    </ul>
    </div>
</div>