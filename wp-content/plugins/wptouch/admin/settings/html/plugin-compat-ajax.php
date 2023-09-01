<?php require_once( WPTOUCH_DIR . '/core/settings.php' ); ?>
<?php $settings = wptouch_get_settings( 'compat' ); ?>
<?php global $wptouch_pro; ?>
<?php if ( is_array( $settings->plugin_hooks ) && count( (array) $settings->plugin_hooks ) ) { ?>
	<ul class="plugin-compat-list">
	<?php foreach( $settings->plugin_hooks as $key => $value ) { ?>
		<li class="wptouch-setting">
			<div class="checkbox-wrap">
			<?php $friendly_name = $wptouch_pro->get_friendly_plugin_name( $key ); ?>
			<?php echo sprintf( __( '%s', 'wptouch-pro' ), $friendly_name); ?>
				<input type="checkbox" class="checkbox" value="<?php echo $key; ?>" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'compat', 'enabled_plugins' ); ?>[]" id="<?php echo $key; ?>"<?php if ( isset( $settings->enabled_plugins[ $key ] ) && $settings->enabled_plugins[ $key ] ) echo ' checked'; ?><?php if ( defined( 'WPTOUCH_IS_FREE' ) || ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) && !current_user_can( 'manage_network' ) ) ) echo ' disabled'; ?> />
				<label for="<?php echo $key; ?>"></label>
				<?php if ( defined( 'WPTOUCH_IS_FREE' ) ) { ?>
					<span class="pro"><?php _e( 'Pro', 'wptouch-pro' ); ?></span>
				<?php } ?>
			</div>
		</li>
	<?php } ?>
		<input type="checkbox" value="ignore" name="<?php echo wptouch_admin_get_manual_encoded_setting_name( 'compat', 'enabled_plugins' ); ?>[]" checked style="display: none;" />

<?php } else { ?>
	<p><?php _e( 'No plugins activated to disable.', 'wptouch-pro' ); ?></p>
<?php } ?>