<div class="ec_thermometer_holder">
	<div class="ec_thermometer_top"><div class="ec_thermometer_top_content"></div></div>
    <div class="ec_thermometer_middle">
    	<div class="ec_thermometer_ticks">
    		<div class="ec_thermometer_liquid" style="height:<?php echo esc_attr( (250 * $percent_used ) ); ?>px; margin-top:<?php echo esc_attr( ( 250 - (250 * $percent_used) ) ); ?>px;"></div>
    	</div>
    </div>
    <div class="ec_thermometer_bottom">
    	<div class="ec_thermometer_bottom_content">
        	<div class="ec_thermometer_bottom_stick"></div>
    		<div class="ec_thermometer_bottom_colorball"></div>
        </div>
    </div>
</div>
<div class="ec_goal_box">
	<div class="ec_goal_content">
    	<div class="ec_goal_needed"><span><?php echo esc_attr( $goal_total ); ?></span>Our Goal</div>
        <div class="ec_goal_divider"></div>
        <div class="ec_goal_raised"><span><?php echo esc_attr( $raised_total ); ?></span>Raised so far</div>
    </div>
</div>