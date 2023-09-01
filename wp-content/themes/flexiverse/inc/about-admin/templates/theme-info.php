<?php
$pro_purchase_url = "https://fireflythemes.com/themes/flexiverse-pro";
$doc_url = "https://fireflythemes.com/documentation/flexiverse";
$theme = wp_get_theme();

if ( is_child_theme() ) {
    $theme = wp_get_theme()->parent();
}
?>
<div class="flexiverse--about-page--wrapper">
	<div id="flexiverse-admin-about-page">
		<div class="flexiverse-admin-card-header">
			<div class="flexiverse-header-left">
				<h2>
					<?php echo esc_html( $theme->Name ); ?>
				</h2>
				<p>
					<?php esc_html_e( 'Multipurpose WordPress Full Site Editing Theme', 'flexiverse' ); ?>
				</p>
			</div>
			<div class="flexiverse-header-right">
				<div class="flexiverse-card-header-button-group">
					<a href="<?php echo $pro_purchase_url; ?>"
						class="flexiverse-admin-button pro-button"
						target="_blank">
					<?php printf( esc_html( 'Get %s Pro', 'flexiverse' ), $theme->Name ); ?>
					</a>
					<a href="<?php echo esc_url( $doc_url ); ?>"
						class="flexiverse-admin-button premium-button" target="_blank"
						rel="noreferrer">
					<?php esc_html_e( 'Documentation', 'flexiverse' ); ?>
					</a>
					<a href="https://fireflythemes.com/support/"
						class="flexiverse-admin-button primary large">
					<?php esc_html_e( 'Get Support', 'flexiverse' ); ?>
					</a>
				</div>
			</div>
		</div>
		<div class="feature-list">
			<div class="flexiverse-about-container">
				<div class="flexiverse-admin-card features">
					<div class="flexiverse-about-container flexiverse-compare-table">
						<div class="about-compare-table">
                        <h3>
                            <?php echo esc_html( $theme->Name ); ?>
                            <?php esc_html_e( 'Free Vs Pro', 'flexiverse' ); ?>
                        </h3>
                        <table>
                            <thead>
                                        <tr>
                                            <th>FlexiVerse Free</th>
                                            <th>Features</th>
                                            <th>FlexiVerse Pro</th>
                                        </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Responsive</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Super Easy Setup</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Color Options for various sections</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Pattern: 404 Error Page</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Pattern: Archive Title</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Pattern: Default Footer</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Pattern: Default Header</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Pattern: Latest News</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Pattern: Main Banner</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Pattern: Services</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Pattern: What We Do</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                    <td>Pattern: Hero Content</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Call to Action</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Gallery Masonry</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Hero Content Two</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Key Feature</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Main Slider</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Post Title</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Recent Work Carousel</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Recent Works</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Services Carousel</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Skills</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Tabs</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Teams</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Pattern: Testimonials Carousel</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Block: Skills</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Block: Masonry</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Block: Popup Video	</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Block: Header Search</td>
                                    <td><i class="dashicons dashicons-yes"></i>More</td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Block: Header Social</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Block: Icons</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Block: Slider	</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                                <tr>
                                    <td><i class="dashicons dashicons-no"></i></td>
                                    <td>Block: Tabs	</td>
                                    <td><i class="dashicons dashicons-yes"></i></td>
                                </tr>
                            </tbody>
                        </table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="flexiverse-card-header-button-group footer-buttons">
		<a href="<?php echo esc_url( $doc_url ); ?>" class="flexiverse-admin-button pro-button" rel="noreferrer"
			target="_blank"><i class="fa fa-paint-brush"></i>
		<?php printf( esc_html( 'Get %s Pro', 'flexiverse' ), $theme->Name ); ?>
		</a>
		<a href="<?php echo esc_url( $doc_url ); ?>" class="flexiverse-admin-button premium-button"
			target="_blank" rel="noreferrer"><i class="fa fa-book"></i>
		<?php esc_html_e( 'Documentation', 'flexiverse' ); ?>
		</a>
		<a href="https://fireflythemes.com/support/"
			class="flexiverse-admin-button primary large">
		<?php esc_html_e( 'Get Support', 'flexiverse' ); ?>
		</a>
	</div>
</div>
