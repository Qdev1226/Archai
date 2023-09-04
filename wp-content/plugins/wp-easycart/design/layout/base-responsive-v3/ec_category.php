<li class="ec_category_li" id="ec_category_li_<?php echo esc_attr( $this->options->category_id ); ?>">
	
	<div style="padding:0px; margin:auto; vertical-align:middle;" class="ec_category_type">
    	
        <div class="ec_image_container_none">
        	
        	<a href="<?php echo esc_attr( $this->get_category_link( ) ); ?>" class="ec_image_link_cover"></a>
        
        	<div class="ec_category_image_display_type">
            	
                <img src="<?php echo esc_attr( $this->get_image( ) ); ?>" alt="<?php echo wp_easycart_language( )->convert_text( $this->options->category_name ); ?>" />
                
            </div>
        
        </div>
        
        <h3 class="ec_category_title_type">
        	<a href="<?php echo esc_attr( $this->get_category_link( ) ); ?>" class="ec_image_link_cover"><?php echo wp_easycart_language( )->convert_text( $this->options->category_name ); ?></a>
        </h3>
        
    </div>
    
</li>