<ul>
	<li class="ec_admin_wizard_item<?php if( $this->step == 1 ){ ?> ec_admin_wizard_current<?php }else if( $this->step > 1 ){ ?> ec_admin_wizard_complete<?php }?>">
        <div class="nav-label"><a href="admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=1" style="color:#333; text-decoration:none; font-weight:bold;"><?php esc_attr_e( 'Page Setup', 'wp-easycart' ); ?></a></div><span class="bubble"><span class="dot"></span></span>
    </li>
    <li class="ec_admin_wizard_item<?php if( $this->step == 2 ){ ?> ec_admin_wizard_current<?php }else if( $this->step > 2 ){ ?> ec_admin_wizard_complete<?php }?>">
    	<div class="nav-label"><a href="admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=2" style="color:#333; text-decoration:none; font-weight:bold;"><?php esc_attr_e( 'Location', 'wp-easycart' ); ?></a></div><span class="bubble"><span class="dot"></span></span>
    </li>
    <li class="ec_admin_wizard_item<?php if( $this->step == 3 ){ ?> ec_admin_wizard_current<?php }else if( $this->step > 3 ){ ?> ec_admin_wizard_complete<?php }?>">
    	<div class="nav-label"><a href="admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3" style="color:#333; text-decoration:none; font-weight:bold;"><?php esc_attr_e( 'Payments', 'wp-easycart' ); ?></a></div><span class="bubble"><span class="dot"></span></span>
    </li>
    <li class="ec_admin_wizard_item<?php if( $this->step == 4 ){ ?> ec_admin_wizard_current<?php }else if( $this->step > 4 ){ ?> ec_admin_wizard_complete<?php }?>">
    	<div class="nav-label"><a href="admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=4" style="color:#333; text-decoration:none; font-weight:bold;"><?php esc_attr_e( 'Shipping', 'wp-easycart' ); ?></a></div><span class="bubble"><span class="dot"></span></span>
    </li>
    <li class="ec_admin_wizard_item<?php if( $this->step == 5 ){ ?> ec_admin_wizard_current<?php }?>">
    	<div class="nav-label"><a href="admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=5" style="color:#333; text-decoration:none; font-weight:bold;"><?php esc_attr_e( 'Complete!', 'wp-easycart' ); ?></a></div><span class="bubble"><span class="dot"></span></span>
    </li>
</ul>