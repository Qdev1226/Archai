<div class="ec_admin_settings_input">
	<div>
		<div class="wpeasycart-timeline-container">
			<div class="wpeasycart-timeline-container-inner">
				<h4><?php esc_attr_e( 'Order History', 'wp-easycart' ); ?></h4>
				<div class="wpeasycart-timline-scrollbox" data-simplebar="init">
					<div class="wpeasycart-timline-scrollbox-content">
						<div class="wpeasycart-timline-scrollbox-content-mask">
							<div class="wpeasycart-timline-scrollbox-content-mask-offset">
								<div class="wpeasycart-timline-scrollbox-content-mask-container">
									<div class="wpeasycart-timeline-content-container">
										<div class="wpeasycart-timeline">
											<div class="wpeasycart-timeline-item">
												<span class="dashicons dashicons-plus-alt"></span>
												<div class="wpeasycart-timeline-item-info">
													<a href="#"><?php echo sprintf( esc_attr__( 'Order %d was created!', 'wp-easycart' ), ( ( isset( $_GET['order_id'] ) ) ? (int) $_GET['order_id'] : '' ) ); ?></a>
													<small><?php esc_attr_e( 'Order logging is limited in our free software.', 'wp-easycart' ); ?></small>
													<p><a href="#" target="_blank" onclick="show_pro_required( ); return false;">View the Full Order Log</a></p>
												</div>
											</div>
											
											<div class="wpeasycart-timeline-item" style="filter:blur(.2rem)">
												<span class="dashicons dashicons-money-alt"></span>
												<div class="wpeasycart-timeline-item-info">
													<a href="#">Cras mattis lorem erat, id ullamcorper</a>
													<small>Fusce quis nisi in sapien elementum dictum sit amet ac urna.</small>
													<p><?php esc_attr_e( 'Less than a minute ago', 'wp-easycart' ); ?></p>
												</div>
											</div>
											
											<div class="wpeasycart-timeline-item" style="filter:blur(.2rem)">
												<span class="dashicons dashicons-money-alt"></span>
												<div class="wpeasycart-timeline-item-info">
													<a href="#">Cras mattis lorem erat, id ullamcorper</a>
													<small>Fusce quis nisi in sapien elementum dictum sit amet ac urna.</small>
													<p><?php esc_attr_e( 'Less than a minute ago', 'wp-easycart' ); ?></p>
													
												</div>
											</div>
											
											<div class="wpeasycart-timeline-item">
												<span class="dashicons dashicons-lock"></span>
												<div class="wpeasycart-timeline-item-info">
													<a href="#"><?php echo sprintf( esc_attr__( 'Order logging is limited', 'wp-easycart' ), ( ( isset( $_GET['order_id'] ) ) ? (int) $_GET['order_id'] : '' ) ); ?></a>
													<small><?php esc_attr_e( 'Upgrade to PRO or Premium and unlock your full log with powerful management tools!', 'wp-easycart' ); ?></small>
													<p><a href="#" target="_blank" onclick="show_pro_required( ); return false;">View the Full Order Log</a></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>