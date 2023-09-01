<?php

wptouch_admin_the_setting_desc();

echo '<div id="multisite-deploy-sites">';
$all_sites = wptouch_multisite_get_site_array();

foreach( $all_sites as $id => $value ) {
	$lowercase = strtolower( $value );
	$classnamed = preg_replace( '/[\s_]/', '-', $lowercase );
	
	echo '<p><input type="checkbox" id="site-' . $id . '" class="site-check"></input>' . '<label for="site-' . $id . '">' . $value . '</label></p>';
}
echo "</div>";

echo '<div id="deploy-button-area">';
echo '<button id="multisite-select-all">' . __( 'Select All', 'wptouch-pro' ) . '</button>';
echo '<button id="multisite-select-none">' . __( 'Select None', 'wptouch-pro' ) . '</button>';
echo '<button id="multisite-deploy">' . __( 'Deploy to Sites', 'wptouch-pro' ) . '</button>';
echo '</div>';

?>

<!-- Modals -->
<div class="remodal remodal-multisite" data-remodal-id="modal-deployment" data-remodal-options="hashTracking: false">
	<button data-remodal-action="close" class="remodal-close"></button>
	<div>
		<h1><?php _e( 'Deployment', 'wptouch-pro' ); ?></h1>
		<p class="deploy-text" data-text="<?php _e( 'WARNING: Deploying to sub sites will overwrite sub site WPtouch Pro settings.', 'wptouch-pro' ); ?>" data-deploying-text="<?php _e( 'Your selected options are being deployed to sub sites', 'wptouch-pro' ); ?>&hellip;" data-completed-text="<?php _e( 'Deploy Complete', 'wptouch-pro' ); ?>!"></p>
		<button class="remodal-confirm deploy-to-sites"><?php _e( 'Deploy Now', 'wptouch-pro' ); ?></button>
	</div>
</div>