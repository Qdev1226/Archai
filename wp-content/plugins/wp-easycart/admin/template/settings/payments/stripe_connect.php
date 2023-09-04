<?php
$default_currency = false;
$account_country = false;
if ( class_exists( 'ec_stripe_connect' ) ) {
	$stripe = new ec_stripe_connect();
	$account = $stripe->get_connect_account();
	if ( $account ) {
		$default_currency = ( isset( $account->default_currency ) ) ? strtoupper( $account->default_currency ) : false;
		$account_country = ( isset( $account->country ) ) ? strtoupper( $account->country ) : false;
	}
}
?>
<div class="ec_admin_stripe_row">
	<div class="ec_admin_slider_row">
		<?php wp_easycart_admin()->preloader->print_preloader( 'ec_admin_stripe_display_loader' ); ?>
		<h3>
			<?php esc_attr_e( 'Stripe', 'wp-easycart' ); ?>
			<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'payment', 'stripe' ) ); ?>" target="_blank" class="ec_help_icon_link" title="<?php esc_attr_e( 'View Help?', 'wp-easycart' ); ?>" style="float:left; margin-left:0px;">
				<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
			</a>
		</h3>
		<div class="ec_admin_slider_row_description">
			<div style="float:left; width:100%; margin-bottom:15px;">
				<?php esc_attr_e( 'Stripe offers the ability to pay with a credit card directly on your website. Adding Stripe gives your shopping cart a more professional look and increases conversions.', 'wp-easycart' ); ?>
				<?php if ( '' != get_option( 'ec_option_stripe_connect_production_access_token' ) ) { ?>
				<br />
				<a href="<?php echo esc_url_raw( wp_easycart_admin()->available_url ); ?>/connect/?step=start&redirect=<?php echo urlencode( esc_url_raw( admin_url() ) . '?ec_admin_form_action=stripe_onboard&env=production&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-stripe' ) ); ?>&env=production"><?php esc_attr_e( 'Switch Production Account', 'wp-easycart' ); ?></a>
				<?php }?>
				<?php if ( get_option( 'ec_option_stripe_connect_sandbox_access_token' ) != '' ) { ?>
				<br />
				<a href="<?php echo esc_url_raw( wp_easycart_admin()->available_url ); ?>/connect/?step=start&redirect=<?php echo urlencode( esc_url_raw( admin_url() ) . '?ec_admin_form_action=stripe_onboard&env=sandbox&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-stripe' ) ); ?>&env=sandbox"><?php esc_attr_e( 'Switch Sandbox Account', 'wp-easycart' ); ?></a>
				<?php } ?>
			</div>
			<?php if ( '' != get_option( 'ec_option_stripe_connect_sandbox_access_token' ) || '' != get_option( 'ec_option_stripe_connect_production_access_token' ) ) { ?>
			<div class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show" style="padding:0px !important;">
				<label style="float:left; width:100%;"><?php esc_attr_e( 'Currency', 'wp-easycart' ); ?></label>
				<select name="ec_option_stripe_currency" id="ec_option_stripe_currency" onchange="ec_admin_save_stripe_connect_options();">
					<option value="USD" <?php if ( 'USD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>U.S. Dollar</option>
					<option value="GBP" <?php if ( 'GBP' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>British Pound</option>
					<option value="CAD" <?php if ( 'CAD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Canadian Dollar</option>
					<option value="EUR" <?php if ( 'EUR' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Euro</option>
					<option value="DEM" <?php if ( 'DEM' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>German Mark</option>
					<option value="CHF" <?php if ( 'CHF' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Swiss Franc</option>

					<option value="AFN" <?php if ( 'AFN' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Afghanistan Afghani</option>
					<option value="ALL" <?php if ( 'ALL' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Albanian Lek</option>
					<option value="AMD" <?php if ( 'AMD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Armenian Dram</option>
					<option value="AOA" <?php if ( 'AOA' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Angolan Kwanza</option>
					<option value="ARS" <?php if ( 'ARS' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Argentine Peso</option>
					<option value="AWG" <?php if ( 'AWG' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Aruban Florin</option>
					<option value="AUD" <?php if ( 'AUD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Australian Dollar</option>
					<option value="AZN" <?php if ( 'AZN' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Azerbaijani an Manat</option>

					<option value="BSD" <?php if ( 'BSD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Bahamanian Dollar</option>
					<option value="BHD" <?php if ( 'BHD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Bahraini Dinar</option>
					<option value="BDT" <?php if ( 'BDT' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Bangladeshi Taka</option>
					<option value="BBD" <?php if ( 'BBD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Barbados Dollar</option>
					<option value="BYR" <?php if ( 'BYR' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Belarussian Ruble</option>
					<option value="BZD" <?php if ( 'BZD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Belize Dollar</option>
					<option value="BMD" <?php if ( 'BMD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Bermudian Dollar</option>
					<option value="BOB" <?php if ( 'BOB' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Bolivian Boliviano</option>
					<option value="BWP" <?php if ( 'BWP' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Botswana Pula</option>
					<option value="BRL" <?php if ( 'BRL' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Brazilian Real</option>
					<option value="BND" <?php if ( 'BND' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Brunei Dollar</option>
					<option value="BGN" <?php if ( 'BGN' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Bulgarian Lev</option>
					<option value="BIF" <?php if ( 'BIF' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Burundi Franc</option>

					<option value="KHR" <?php if ( 'KHR' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Cambodian Riel</option>
					<option value="CVE" <?php if ( 'CVE' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Cape Verde Escudo</option>
					<option value="KYD" <?php if ( 'KYD' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Cayman Islands Dollar</option>
					<option value="XAF" <?php if ( 'XAF' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Central African Republic Franc BCEAO</option>
					<option value="XPF" <?php if ( 'XPF' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>CFP Franc</option>
					<option value="CLP" <?php if ( 'CLP' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Chilean Peso</option>
					<option value="CNY" <?php if ( 'CNY' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Chinese Yuan Renminbi</option>
					<option value="COP" <?php if ( 'COP' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Colombian Peso</option>
					<option value="KMF" <?php if ( 'KMF' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Comoros Franc</option>
					<option value="BAM" <?php if ( 'BAM' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Convertible Marks</option>
					<option value="CRC" <?php if ( 'CRC' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Costa Rican Colon</option>
					<option value="HRK" <?php if ( 'HRK' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Croatian Kuna</option>
					<option value="CUP" <?php if ( 'CUP' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Cuban Peso</option>
					<option value="CYP" <?php if ( 'CYP' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Cyprus Pound</option>
					<option value="CZK" <?php if ( 'CZK' == get_option('ec_option_stripe_currency' ) ) { echo ' selected'; } ?>>Czech Republic Koruna</option>

					<option value="DKK" <?php if ( get_option('ec_option_stripe_currency' ) == "DKK" ) echo ' selected'; ?>>Danish Krone</option>
					<option value="DJF" <?php if ( get_option('ec_option_stripe_currency' ) == "DJF" ) echo ' selected'; ?>>Djibouti Franc</option>
					<option value="DOP" <?php if ( get_option('ec_option_stripe_currency' ) == "DOP" ) echo ' selected'; ?>>Dominican Peso</option>

					<option value="XCD" <?php if ( get_option('ec_option_stripe_currency' ) == "XCD" ) echo ' selected'; ?>>East Caribbean Dollar</option>
					<option value="ECS" <?php if ( get_option('ec_option_stripe_currency' ) == "ECE" ) echo ' selected'; ?>>Ecuador Sucre</option>
					<option value="EGP" <?php if ( get_option('ec_option_stripe_currency' ) == "EGP" ) echo ' selected'; ?>>Egyptian Pound</option>
					<option value="SVC" <?php if ( get_option('ec_option_stripe_currency' ) == "SVC" ) echo ' selected'; ?>>El Salvador Colon</option>
					<option value="ERN" <?php if ( get_option('ec_option_stripe_currency' ) == "ERN" ) echo ' selected'; ?>>Eritrea Nakfa</option>
					<option value="EEK" <?php if ( get_option('ec_option_stripe_currency' ) == "EEK" ) echo ' selected'; ?>>Estonian Kroon</option>
					<option value="ETB" <?php if ( get_option('ec_option_stripe_currency' ) == "ETB" ) echo ' selected'; ?>>Ethiopian Birr</option>

					<option value="FKP" <?php if ( get_option('ec_option_stripe_currency' ) == "FKP" ) echo ' selected'; ?>>Falkland Islands Pound</option>
					<option value="FJD" <?php if ( get_option('ec_option_stripe_currency' ) == "FJD" ) echo ' selected'; ?>>Fiji Dollar</option>
					<option value="CDF" <?php if ( get_option('ec_option_stripe_currency' ) == "CDF" ) echo ' selected'; ?>>Franc Congolais</option>

					<option value="GMD" <?php if ( get_option('ec_option_stripe_currency' ) == "GMD" ) echo ' selected'; ?>>Gambian Dalasi</option>
					<option value="GEL" <?php if ( get_option('ec_option_stripe_currency' ) == "GEL" ) echo ' selected'; ?>>Georgian Lari</option>
					<option value="GHS" <?php if ( get_option('ec_option_stripe_currency' ) == "GHS" ) echo ' selected'; ?>>Ghanaian Cedi</option>
					<option value="GIP" <?php if ( get_option('ec_option_stripe_currency' ) == "GIP" ) echo ' selected'; ?>>Gibraltar Pound</option>
					<option value="GTQ" <?php if ( get_option('ec_option_stripe_currency' ) == "GTQ" ) echo ' selected'; ?>>Guatemalan Quetzal</option>
					<option value="GNF" <?php if ( get_option('ec_option_stripe_currency' ) == "GNF" ) echo ' selected'; ?>>Guinea Franc</option>
					<option value="GWP" <?php if ( get_option('ec_option_stripe_currency' ) == "GWP" ) echo ' selected'; ?>>Guinea-Bissau Peso</option>
					<option value="GYD" <?php if ( get_option('ec_option_stripe_currency' ) == "GYD" ) echo ' selected'; ?>>Guyanan Dollar</option>

					<option value="HTG" <?php if ( get_option('ec_option_stripe_currency' ) == "HTG" ) echo ' selected'; ?>>Haitian Gourde</option>
					<option value="HNL" <?php if ( get_option('ec_option_stripe_currency' ) == "HNL" ) echo ' selected'; ?>>Honduran Lempira</option>
					<option value="HKD" <?php if ( get_option('ec_option_stripe_currency' ) == "HKD" ) echo ' selected'; ?>>Hong Kong Dollar</option>
					<option value="HUF" <?php if ( get_option('ec_option_stripe_currency' ) == "HUF" ) echo ' selected'; ?>>Hungarian Forint</option>

					<option value="ISK" <?php if ( get_option('ec_option_stripe_currency' ) == "ISK" ) echo ' selected'; ?>>Iceland Krona</option>
					<option value="INR" <?php if ( get_option('ec_option_stripe_currency' ) == "INR" ) echo ' selected'; ?>>Indian Rupee</option>
					<option value="IDR" <?php if ( get_option('ec_option_stripe_currency' ) == "IDR" ) echo ' selected'; ?>>Indonesian Rupiah</option>
					<option value="IRR" <?php if ( get_option('ec_option_stripe_currency' ) == "IRR" ) echo ' selected'; ?>>Iranian Rial</option>
					<option value="IQD" <?php if ( get_option('ec_option_stripe_currency' ) == "IQD" ) echo ' selected'; ?>>Iraqi Dinar</option>
					<option value="ILS" <?php if ( get_option('ec_option_stripe_currency' ) == "ILS" ) echo ' selected'; ?>>Israeli New Shekel</option>

					<option value="JMD" <?php if ( get_option('ec_option_stripe_currency' ) == "JMD" ) echo ' selected'; ?>>Jamaican Dollar</option>
					<option value="JPY" <?php if ( get_option('ec_option_stripe_currency' ) == "JPY" ) echo ' selected'; ?>>Japanese Yen</option>
					<option value="JOD" <?php if ( get_option('ec_option_stripe_currency' ) == "JOD" ) echo ' selected'; ?>>Jordanian Dinar</option>

					<option value="KZT" <?php if ( get_option('ec_option_stripe_currency' ) == "KZT" ) echo ' selected'; ?>>Kazakhstan Tenge</option>
					<option value="KES" <?php if ( get_option('ec_option_stripe_currency' ) == "KES" ) echo ' selected'; ?>>Kenyan Shilling</option>
					<option value="KWD" <?php if ( get_option('ec_option_stripe_currency' ) == "KWD" ) echo ' selected'; ?>>Kuwaiti Dinar</option>
					<option value="AOA" <?php if ( get_option('ec_option_stripe_currency' ) == "AOA" ) echo ' selected'; ?>>Kwanza</option>
					<option value="GKS" <?php if ( get_option('ec_option_stripe_currency' ) == "GKS" ) echo ' selected'; ?>>Kyrgyzstan Som</option>

					<option value="KIP" <?php if ( get_option('ec_option_stripe_currency' ) == "KIP" ) echo ' selected'; ?>>Laos Kip</option>
					<option value="LAK" <?php if ( get_option('ec_option_stripe_currency' ) == "LAK" ) echo ' selected'; ?>>Laosian Kip</option>
					<option value="LVL" <?php if ( get_option('ec_option_stripe_currency' ) == "LVL" ) echo ' selected'; ?>>Latvian Lat</option>
					<option value="LBP" <?php if ( get_option('ec_option_stripe_currency' ) == "LBP" ) echo ' selected'; ?>>Lebanese Pound</option>
					<option value="LRD" <?php if ( get_option('ec_option_stripe_currency' ) == "LRD" ) echo ' selected'; ?>>Liberian Dollar</option>
					<option value="LYD" <?php if ( get_option('ec_option_stripe_currency' ) == "LYD" ) echo ' selected'; ?>>Libyan Dinar</option>
					<option value="LTL" <?php if ( get_option('ec_option_stripe_currency' ) == "LTL" ) echo ' selected'; ?>>Lithuanian Litas</option>
					<option value="LSL" <?php if ( get_option('ec_option_stripe_currency' ) == "LSL" ) echo ' selected'; ?>>Loti</option>

					<option value="MOP" <?php if ( get_option('ec_option_stripe_currency' ) == "MOP" ) echo ' selected'; ?>>Macanese Pataca</option>
					<option value="MOP" <?php if ( get_option('ec_option_stripe_currency' ) == "MOP" ) echo ' selected'; ?>>Macao</option>
					<option value="MKD" <?php if ( get_option('ec_option_stripe_currency' ) == "MKD" ) echo ' selected'; ?>>Macedonian Denar</option>
					<option value="MGF" <?php if ( get_option('ec_option_stripe_currency' ) == "MGF" ) echo ' selected'; ?>>Malagasy Franc</option>
					<option value="MGA" <?php if ( get_option('ec_option_stripe_currency' ) == "MGA" ) echo ' selected'; ?>>Malagasy Ariary</option>
					<option value="MWK" <?php if ( get_option('ec_option_stripe_currency' ) == "MWK" ) echo ' selected'; ?>>Malawi Kwacha</option>
					<option value="MYR" <?php if ( get_option('ec_option_stripe_currency' ) == "MYR" ) echo ' selected'; ?>>Malaysian Ringgit</option>
					<option value="MVR" <?php if ( get_option('ec_option_stripe_currency' ) == "MVR" ) echo ' selected'; ?>>Maldive Rufiyaa</option>
					<option value="MTL" <?php if ( get_option('ec_option_stripe_currency' ) == "MRL" ) echo ' selected'; ?>>Maltese Lira</option>
					<option value="MRO" <?php if ( get_option('ec_option_stripe_currency' ) == "MRO" ) echo ' selected'; ?>>Mauritanian Ouguiya</option>
					<option value="MUR" <?php if ( get_option('ec_option_stripe_currency' ) == "MUR" ) echo ' selected'; ?>>Mauritius Rupee</option>
					<option value="MXN" <?php if ( get_option('ec_option_stripe_currency' ) == "MXN" ) echo ' selected'; ?>>Mexican Peso</option>
					<option value="MNT" <?php if ( get_option('ec_option_stripe_currency' ) == "MNT" ) echo ' selected'; ?>>Mongolian Tugrik</option>
					<option value="MAD" <?php if ( get_option('ec_option_stripe_currency' ) == "MAD" ) echo ' selected'; ?>>Moroccan Dirham</option>
					<option value="MZM" <?php if ( get_option('ec_option_stripe_currency' ) == "MZM" ) echo ' selected'; ?>>Mozambique Metical</option>
					<option value="MMK" <?php if ( get_option('ec_option_stripe_currency' ) == "MMK" ) echo ' selected'; ?>>Myanmar Kyat</option>

					<option value="NAD" <?php if ( get_option('ec_option_stripe_currency' ) == "NAD" ) echo ' selected'; ?>>Namibia Dollar</option>
					<option value="NPR" <?php if ( get_option('ec_option_stripe_currency' ) == "NPR" ) echo ' selected'; ?>>Nepalese Rupee</option>
					<option value="ANG" <?php if ( get_option('ec_option_stripe_currency' ) == "ANG" ) echo ' selected'; ?>>Netherlands Antillean Guilder</option>
					<option value="PGK" <?php if ( get_option('ec_option_stripe_currency' ) == "PGK" ) echo ' selected'; ?>>New Guinea Kina</option>
					<option value="TWD" <?php if ( get_option('ec_option_stripe_currency' ) == "TWD" ) echo ' selected'; ?>>New Taiwan Dollar</option>
					<option value="TRY" <?php if ( get_option('ec_option_stripe_currency' ) == "TRY" ) echo ' selected'; ?>>New Turkish Lira</option>
					<option value="NZD" <?php if ( get_option('ec_option_stripe_currency' ) == "NZD" ) echo ' selected'; ?>>New Zealand Dollar</option>
					<option value="NIO" <?php if ( get_option('ec_option_stripe_currency' ) == "NIO" ) echo ' selected'; ?>>Nicaraguan Cordoba Oro</option>
					<option value="NGN" <?php if ( get_option('ec_option_stripe_currency' ) == "NGN" ) echo ' selected'; ?>>Nigerian Naira</option>
					<option value="KPW" <?php if ( get_option('ec_option_stripe_currency' ) == "KPW" ) echo ' selected'; ?>>North Korea Won</option>
					<option value="NOK" <?php if ( get_option('ec_option_stripe_currency' ) == "NOK" ) echo ' selected'; ?>>Norwegian Kroner</option>

					<option value="PKR" <?php if ( get_option('ec_option_stripe_currency' ) == "PKR" ) echo ' selected'; ?>>Pakistan Rupee</option>
					<option value="PAB" <?php if ( get_option('ec_option_stripe_currency' ) == "PAB" ) echo ' selected'; ?>>Panamanian Balboa</option>
					<option value="PYG" <?php if ( get_option('ec_option_stripe_currency' ) == "PYG" ) echo ' selected'; ?>>Paraguay Guarani</option>
					<option value="PEN" <?php if ( get_option('ec_option_stripe_currency' ) == "PEN" ) echo ' selected'; ?>>Peruvian Nuevo Sol</option>
					<option value="PHP" <?php if ( get_option('ec_option_stripe_currency' ) == "PHP" ) echo ' selected'; ?>>Philippine Peso</option>
					<option value="PLN" <?php if ( get_option('ec_option_stripe_currency' ) == "PLN" ) echo ' selected'; ?>>Polish Zloty</option>

					<option value="QAR" <?php if ( get_option('ec_option_stripe_currency' ) == "QAR" ) echo ' selected'; ?>>Qatari Rial</option>

					<option value="OMR" <?php if ( get_option('ec_option_stripe_currency' ) == "OMR" ) echo ' selected'; ?>>Rial Omani</option>
					<option value="RON" <?php if ( get_option('ec_option_stripe_currency' ) == "RON" ) echo ' selected'; ?>>Romanian Leu</option>
					<option value="RUB" <?php if ( get_option('ec_option_stripe_currency' ) == "RUB" ) echo ' selected'; ?>>Russian Rouble</option>
					<option value="RWF" <?php if ( get_option('ec_option_stripe_currency' ) == "RWF" ) echo ' selected'; ?>>Rwanda Franc</option>

					<option value="WST" <?php if ( get_option('ec_option_stripe_currency' ) == "WST" ) echo ' selected'; ?>>Samoan Tala</option>
					<option value="STD" <?php if ( get_option('ec_option_stripe_currency' ) == "STD" ) echo ' selected'; ?>>Sao Tome/Principe Dobra</option>
					<option value="SAR" <?php if ( get_option('ec_option_stripe_currency' ) == "SAR" ) echo ' selected'; ?>>Saudi Riyal</option>
					<option value="RSD" <?php if ( get_option('ec_option_stripe_currency' ) == "RSD" ) echo ' selected'; ?>>Serbian Dinar</option>
					<option value="SCR" <?php if ( get_option('ec_option_stripe_currency' ) == "SCR" ) echo ' selected'; ?>>Seychelles Rupee</option>
					<option value="SLL" <?php if ( get_option('ec_option_stripe_currency' ) == "SLL" ) echo ' selected'; ?>>Sierra Leone Leone</option>
					<option value="SGD" <?php if ( get_option('ec_option_stripe_currency' ) == "SGD" ) echo ' selected'; ?>>Singapore Dollar</option>
					<option value="SKK" <?php if ( get_option('ec_option_stripe_currency' ) == "SKK" ) echo ' selected'; ?>>Slovak Koruna</option>
					<option value="SIT" <?php if ( get_option('ec_option_stripe_currency' ) == "SIT" ) echo ' selected'; ?>>Slovenian Tolar</option>
					<option value="SBD" <?php if ( get_option('ec_option_stripe_currency' ) == "SBD" ) echo ' selected'; ?>>Solomon Islands Dollar</option>
					<option value="SOS" <?php if ( get_option('ec_option_stripe_currency' ) == "SOS" ) echo ' selected'; ?>>Somalia Shilling</option>
					<option value="ZAR" <?php if ( get_option('ec_option_stripe_currency' ) == "ZAR" ) echo ' selected'; ?>>South African Rand</option>
					<option value="KRW" <?php if ( get_option('ec_option_stripe_currency' ) == "KRW" ) echo ' selected'; ?>>South-Korean Won</option>
					<option value="LKR" <?php if ( get_option('ec_option_stripe_currency' ) == "LKR" ) echo ' selected'; ?>>Sri Lanka Rupee</option>
					<option value="SHP" <?php if ( get_option('ec_option_stripe_currency' ) == "SHP" ) echo ' selected'; ?>>St. Helena Pound</option>
					<option value="SDD" <?php if ( get_option('ec_option_stripe_currency' ) == "SDD" ) echo ' selected'; ?>>Sudanese Dollar</option>
					<option value="SRD" <?php if ( get_option('ec_option_stripe_currency' ) == "SRD" ) echo ' selected'; ?>>Suriname Dollar</option>
					<option value="SZL" <?php if ( get_option('ec_option_stripe_currency' ) == "SZL" ) echo ' selected'; ?>>Swaziland Lilangeni</option>
					<option value="SEK" <?php if ( get_option('ec_option_stripe_currency' ) == "SEK" ) echo ' selected'; ?>>Swedish Krona</option>
					<option value="CHF" <?php if ( get_option('ec_option_stripe_currency' ) == "CHF" ) echo ' selected'; ?>>Switzerland Franc</option>
					<option value="SYP" <?php if ( get_option('ec_option_stripe_currency' ) == "SYP" ) echo ' selected'; ?>>Syrian Arab Republic Pound</option>

					<option value="TJS" <?php if ( get_option('ec_option_stripe_currency' ) == "TJS" ) echo ' selected'; ?>>Tajikistani Somoni</option>
					<option value="TZS" <?php if ( get_option('ec_option_stripe_currency' ) == "TZS" ) echo ' selected'; ?>>Tanzanian Shilling</option>
					<option value="THB" <?php if ( get_option('ec_option_stripe_currency' ) == "THB" ) echo ' selected'; ?>>Thai Baht</option>
					<option value="TOP" <?php if ( get_option('ec_option_stripe_currency' ) == "TOP" ) echo ' selected'; ?>>Tonga Pa'anga</option>
					<option value="TTD" <?php if ( get_option('ec_option_stripe_currency' ) == "TTD" ) echo ' selected'; ?>>Trinidad/Tobago Dollar</option>
					<option value="TND" <?php if ( get_option('ec_option_stripe_currency' ) == "TND" ) echo ' selected'; ?>>Tunisian Dinar</option>
					<option value="TMM" <?php if ( get_option('ec_option_stripe_currency' ) == "TMM" ) echo ' selected'; ?>>Turkmenistan Manat</option>

					<option value="UGX" <?php if ( get_option('ec_option_stripe_currency' ) == "UGX" ) echo ' selected'; ?>>Uganda Shilling</option>
					<option value="UAH" <?php if ( get_option('ec_option_stripe_currency' ) == "UAH" ) echo ' selected'; ?>>Ukraine Hryvnia</option>
					<option value="AED" <?php if ( get_option('ec_option_stripe_currency' ) == "AED" ) echo ' selected'; ?>>Utd. Arab Emir. Dirham</option>
					<option value="UYU" <?php if ( get_option('ec_option_stripe_currency' ) == "UYU" ) echo ' selected'; ?>>Uruguayo Peso</option>
					<option value="UZS" <?php if ( get_option('ec_option_stripe_currency' ) == "UZS" ) echo ' selected'; ?>>Uzbekistan Som</option>

					<option value="VUV" <?php if ( get_option('ec_option_stripe_currency' ) == "VUV" ) echo ' selected'; ?>>Vanuatu Vatu</option>
					<option value="VEF" <?php if ( get_option('ec_option_stripe_currency' ) == "VEF" ) echo ' selected'; ?>>Venezuelan Bolivar Fuerte</option>
					<option value="VND" <?php if ( get_option('ec_option_stripe_currency' ) == "VND" ) echo ' selected'; ?>>Vietnamese Dong</option>
					<option value="XOF" <?php if ( get_option('ec_option_stripe_currency' ) == "XOF" ) echo ' selected'; ?>>West African CFA Franc BCEAO</option>
					<option value="YER" <?php if ( get_option('ec_option_stripe_currency' ) == "YER" ) echo ' selected'; ?>>Yemeni Rial</option>

					<option value="YUM" <?php if ( get_option('ec_option_stripe_currency' ) == "YUm" ) echo ' selected'; ?>>Yugoslav New Dinar</option>
					<option value="ZMK" <?php if ( get_option('ec_option_stripe_currency' ) == "ZMK" ) echo ' selected'; ?>>Zambian Kwacha</option>
					<option value="ZWD" <?php if ( get_option('ec_option_stripe_currency' ) == "ZWD" ) echo ' selected'; ?>>Zimbabwean Dollar</option>
				</select>
			</div>
			<div class="ec_method_deactivated" id="stripe_account_currency_note" data-currency="<?php echo esc_attr( $default_currency ); ?>"<?php if ( get_option( 'ec_option_stripe_currency' ) == $default_currency ) { ?> style="display:none"<?php }?>>
				<span class="ec_status_label" style="line-height:1.2em;"><?php esc_attr_e( 'The default currency listed in your account does not match your current selection. This may or may not cause problems with your checkout and typically depends on the payment types you activate below.', 'wp-easycart-pro' ); ?></span>
			</div>
			<div class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show" style="padding:0px !important;">
				<label style="float:left; width:100%;"><?php esc_attr_e( 'Business Country', 'wp-easycart' ); ?></label>
				<select name="ec_option_stripe_company_country" id="ec_option_stripe_company_country" onchange="ec_admin_save_stripe_connect_options();">
					<option value="US" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'US' ) { echo ' selected'; }; ?>>United States (US)</option>
					<option value="AU" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'AU' ) { echo ' selected'; }; ?>>Australia (AU)</option>
					<option value="AT" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'AT' ) { echo ' selected'; }; ?>>Austria (AT)</option>
					<option value="BE" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'BE' ) { echo ' selected'; }; ?>>Belgium (BE)</option>
					<option value="BR" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'BR' ) { echo ' selected'; }; ?>>Brazil (BR)</option>
					<option value="CA" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'CA' ) { echo ' selected'; }; ?>>Canada (CA)</option>
					<option value="HR" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'HR' ) { echo ' selected'; }; ?>>Croatia (HR)</option>
					<option value="CY" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'CY' ) { echo ' selected'; }; ?>>Cyprus (CY)</option>
					<option value="CZ" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'CZ' ) { echo ' selected'; }; ?>>Czech Republic (CZ)</option>
					<option value="DK" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'DK' ) { echo ' selected'; }; ?>>Denmark (DK)</option>
					<option value="EE" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'EE' ) { echo ' selected'; }; ?>>Estonia (EE)</option>
					<option value="FI" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'FI' ) { echo ' selected'; }; ?>>Finland (FI)</option>
					<option value="FR" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'FR' ) { echo ' selected'; }; ?>>France (FR)</option>
					<option value="DE" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'DE' ) { echo ' selected'; }; ?>>Germany (DE)</option>
					<option value="GI" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'GI' ) { echo ' selected'; }; ?>>Gibraltar (GI)</option>
					<option value="GR" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'GR' ) { echo ' selected'; }; ?>>Greece (GR)</option>
					<option value="HK" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'HK' ) { echo ' selected'; }; ?>>Hong Kong SAR China (HK)</option>
					<option value="HU" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'HU' ) { echo ' selected'; }; ?>>Hungary (HU)</option>
					<option value="IN" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'IN' ) { echo ' selected'; }; ?>>India (IN)</option>
					<option value="IE" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'IE' ) { echo ' selected'; }; ?>>Ireland (IE)</option>
					<option value="IT" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'IT' ) { echo ' selected'; }; ?>>Italy (IT)</option>
					<option value="JP" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'JP' ) { echo ' selected'; }; ?>>Japan (JP)</option>
					<option value="LV" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'LV' ) { echo ' selected'; }; ?>>Latvia (LV)</option>
					<option value="LI" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'LI' ) { echo ' selected'; }; ?>>Liechtenstein (LI)</option>
					<option value="LT" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'LT' ) { echo ' selected'; }; ?>>Lithuania (LT)</option>
					<option value="LU" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'LU' ) { echo ' selected'; }; ?>>Luxembourg (LU)</option>
					<option value="MY" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'MY' ) { echo ' selected'; }; ?>>Malaysia (MY)</option>
					<option value="MT" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'MT' ) { echo ' selected'; }; ?>>Malta (MT)</option>
					<option value="MX" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'MX' ) { echo ' selected'; }; ?>>Mexico (MX)</option>
					<option value="NL" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'NL' ) { echo ' selected'; }; ?>>Netherlands (NL)</option>
					<option value="NZ" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'NZ' ) { echo ' selected'; }; ?>>New Zealand (NZ)</option>
					<option value="NO" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'NO' ) { echo ' selected'; }; ?>>Norway (NO)</option>
					<option value="PO" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'PL' ) { echo ' selected'; }; ?>>Poland (PO)</option>
					<option value="PT" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'PT' ) { echo ' selected'; }; ?>>Portugal (PT)</option>
					<option value="RO" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'RO' ) { echo ' selected'; }; ?>>Romania (RO)</option>
					<option value="SG" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'SG' ) { echo ' selected'; }; ?>>Singapore (SG)</option>
					<option value="SI" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'SK' ) { echo ' selected'; }; ?>>Slovakia (SK)</option>
					<option value="SG" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'SI' ) { echo ' selected'; }; ?>>Slovenia (SI)</option>
					<option value="ES" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'ES' ) { echo ' selected'; }; ?>>Spain (ES)</option>
					<option value="SE" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'SE' ) { echo ' selected'; }; ?>>Sweden (SE)</option>
					<option value="CH" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'CH' ) { echo ' selected'; }; ?>>Switzerland (CH)</option>
					<option value="TH" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'TH' ) { echo ' selected'; }; ?>>Thailand (TH)</option>
					<option value="AE" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'AE' ) { echo ' selected'; }; ?>>United Arab Emirates (AE)</option>
					<option value="GB" <?php if ( get_option( 'ec_option_stripe_company_country' ) == 'GB' ) { echo ' selected'; }; ?>>United Kingdom (GB)</option>
				</select>
			</div>
			<div class="ec_method_deactivated" id="stripe_account_country_note" data-country="<?php echo esc_attr( $account_country ); ?>"<?php if ( get_option( 'ec_option_stripe_company_country' ) == $account_country ) { ?> style="display:none"<?php }?>>
				<span class="ec_status_label" style="line-height:1.2em;"><?php esc_attr_e( 'The country listed in your account does not match your current selection. This may or may not cause problems with your checkout.', 'wp-easycart-pro' ); ?></span>
			</div>
			<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_show" style="padding:0px !important;">
				<label><?php _e( 'Payment Theme', 'wp-easycart' ); ?></label>
				<select name="ec_option_stripe_payment_theme" id="ec_option_stripe_payment_theme" onchange="ec_admin_save_stripe_connect_options( );">
					<option value="stripe" selected="selected"><?php esc_attr_e( 'Stripe Theme', 'wp-easycart' ); ?></option>
					<option value="stripe"><?php esc_attr_e( 'Night Theme (PRO &amp; Premium Only)', 'wp-easycart-pro' ); ?></option>
					<option value="stripe"><?php esc_attr_e( 'Flat Theme (PRO &amp; Premium Only)', 'wp-easycart' ); ?></option>
					<option value="stripe"><?php esc_attr_e( 'None (PRO &amp; Premium Only)', 'wp-easycart' ); ?></option>
				</select>
			</div>
			<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_show" style="padding:0px !important;">
				<label><?php _e( 'Payment Layout', 'wp-easycart' ); ?></label>
				<select name="ec_option_stripe_payment_layout" id="ec_option_stripe_payment_layout" onchange="ec_admin_save_stripe_connect_options( );">
					<option value="tabs" selected="selected"><?php esc_attr_e( 'Tabs', 'wp-easycart-pro' ); ?></option>
					<option value="tabs"><?php esc_attr_e( 'Accordion (PRO &amp; Premium Only)', 'wp-easycart-pro' ); ?></option>
				</select>
			</div>
			<?php if ( get_option( 'ec_option_onepage_checkout' ) ) { ?>
				<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_show" style="padding:0px !important;">
					<label><?php _e( 'Stripe Address Auto-Complete', 'wp-easycart' ); ?></label>
					<select name="ec_option_stripe_address_autocomplete" id="ec_option_stripe_address_autocomplete" onchange="ec_admin_save_stripe_connect_options( );">
						<option value="1"<?php echo ( '1' == get_option( 'ec_option_stripe_address_autocomplete' ) ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'Enable (Link Required)', 'wp-easycart-pro' ); ?></option>
						<option value="0"<?php echo ( '0' == get_option( 'ec_option_stripe_address_autocomplete' ) ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'Disabled', 'wp-easycart-pro' ); ?></option>
					</select>
				</div>
			<?php } else { ?>
				<input type="hidden" name="ec_option_stripe_address_autocomplete" id="ec_option_stripe_address_autocomplete" value="<?php echo esc_attr( (int) get_option( 'ec_option_stripe_address_autocomplete' ) ); ?>" />
			<?php } ?>

			<div id="stripe_buy_now_later" class="ec_admin_stripe_section" data-currencies="USD,CAD,GBP,AUD,NZD,EUR,DKK,SEK,NOK" data-countries="US,AU,CA,FR,NZ,ES,GB,AT,BE,DK,EE,FI,GR,DE,GR,IE,IT,LV,LT,NL,NO,SK,SI,SE"<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'USD', 'CAD', 'GBP', 'AUD', 'NZD', 'EUR', 'DKK', 'SEK', 'NOK' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'US', 'AU', 'CA', 'FR', 'NZ', 'ES', 'GB', 'AT', 'BE', 'DK', 'EE', 'FI', 'GR', 'DE', 'GR', 'IE', 'IT', 'LV', 'LT', 'NL', 'NO', 'SK', 'SI', 'SE' ) ) ) { echo ' style="display:none;"'; } ?>>
				<h3 style="float:left; width:100%; margin:25px 0 10px; border-bottom:1px solid #CCC; padding:0 0 5px;"><?php esc_attr_e( 'Buy Now, Pay Later', 'wp-easycart' ); ?></h3>
				<div id="stripe_use_affirm" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="USD" data-countries="US" style="padding:0px !important;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'USD' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'US' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Affirm', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_affirm" id="ec_option_stripe_affirm" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<?php $afterpay_rules = array(
					'AU' => array( 'AUD' ),
					'CA' => array( 'CAD' ),
					'NZ' => array( 'NZD' ),
					'GB' => array( 'GBP' ),
					'US' => array( 'USD' ),
					'FR' => array( 'EUR' ),
					'ES' => array( 'EUR' ),
				); ?>
				<div id="stripe_use_afterpay" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="USD,CAD,GBP,AUD,NZD,EUR" data-countries="AU,CA,FR,NZ,ES,GB,US" data-country-currency='{"AU": ["AUD"],"CA": ["CAD"],"NZ": ["NZD"],"GB": ["GBP"],"US": ["USD"],"FR": ["EUR"],"ES": ["EUR"]}' style="padding:0px !important;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'USD', 'CAD', 'GBP', 'AUD', 'NZD', 'EUR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'US', 'AU', 'CA', 'FR', 'NZ', 'ES', 'GB' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable AfterPay', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_afterpay" id="ec_option_stripe_afterpay" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
					<div class="ec_status_error" style="padding:5px 15px;<?php if ( isset( $afterpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $afterpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) { ?> display:none<?php }?>"><span class="ec_status_label" style="line-height:1.2em;"><?php esc_attr_e( 'This payment type requires your business country and currency to match local norms.', 'wp-easycart' ); ?></span></div>
				</div>
				<?php $klarna_rules = array(
					'AT' => array( 'EUR' ),
					'BE' => array( 'EUR' ),
					'DK' => array( 'DKK' ),
					'FI' => array( 'EUR' ),
					'FR' => array( 'EUR' ),
					'DE' => array( 'EUR' ),
					'IE' => array( 'EUR' ),
					'IT' => array( 'EUR' ),
					'NL' => array( 'EUR' ),
					'NO' => array( 'NOK' ),
					'ES' => array( 'EUR' ),
					'SE' => array( 'SEK' ),
					'GB' => array( 'GBP' ),
					'US' => array( 'USD' ),
				); ?>
				<div id="stripe_use_klarna" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="EUR,USD,GBP,DKK,SEK,NOK" data-countries="AT,BE,DK,EE,FI,GR,DE,GR,IE,IT,LV,LT,NL,NO,SK,SI,ES,SE,GB,US" data-country-currency='{"AT": ["EUR"],"BE": ["EUR"],"DK": ["DKK"],"FI": ["EUR"],"FR": ["EUR"],"DE": ["EUR"],"IE": ["EUR"],"IT": ["EUR"],"NL": ["EUR"],"NO": ["NOK"],"ES": ["EUR"],"SE": ["SEK"],"GB": ["GBP"],"US": ["USD"]}' style="padding:0px !important;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'EUR', 'USD', 'GBP', 'DKK', 'SEK', 'NOK' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'US', 'ES', 'GB', 'AT', 'BE', 'DK', 'EE', 'FI', 'GR', 'DE', 'GR', 'IE', 'IT', 'LV', 'LT', 'NL', 'NO', 'SK', 'SI', 'SE' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Klarna', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_enable_klarna" id="ec_option_stripe_enable_klarna" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
					<div class="ec_status_error" style="padding:5px 15px;<?php if ( isset( $klarna_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $klarna_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) { ?> display:none<?php }?>"><span class="ec_status_label" style="line-height:1.2em;"><?php esc_attr_e( 'This payment type requires your business country and currency to match local norms.', 'wp-easycart' ); ?></span></div>
				</div>
			</div>

			<div id="stripe_wallet_payments">
				<h3 style="float:left; width:100%; margin:25px 0 10px; border-bottom:1px solid #CCC; padding:0 0 5px;"><?php esc_attr_e( 'Wallet Payments', 'wp-easycart' ); ?></h3>
				<div id="stripe_use_applepay" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show" style="padding:0px !important;">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Apple Pay and Google Pay', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_enable_apple_pay" id="ec_option_stripe_enable_apple_pay" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" <?php if ( ! get_option('ec_option_stripe_enable_apple_pay' ) ) echo ' selected'; ?>><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<?php $alipay_rules = array(
					'AU' => array( 'AUD', 'CYN' ),
					'CA' => array( 'CAD', 'CYN' ),
					'AT' => array( 'EUR', 'CYN' ),
					'BE' => array( 'EUR', 'CYN' ),
					'BG' => array( 'EUR', 'CYN' ),
					'CY' => array( 'EUR', 'CYN' ),
					'CZ' => array( 'EUR', 'CYN' ),
					'DK' => array( 'EUR', 'CYN' ),
					'EE' => array( 'EUR', 'CYN' ),
					'FI' => array( 'EUR', 'CYN' ),
					'FR' => array( 'EUR', 'CYN' ),
					'DE' => array( 'EUR', 'CYN' ),
					'GR' => array( 'EUR', 'CYN' ),
					'IE' => array( 'EUR', 'CYN' ),
					'IT' => array( 'EUR', 'CYN' ),
					'LV' => array( 'EUR', 'CYN' ),
					'LT' => array( 'EUR', 'CYN' ),
					'LU' => array( 'EUR', 'CYN' ),
					'MT' => array( 'EUR', 'CYN' ),
					'NL' => array( 'EUR', 'CYN' ),
					'NO' => array( 'EUR', 'CYN' ),
					'PT' => array( 'EUR', 'CYN' ),
					'RO' => array( 'EUR', 'CYN' ),
					'SK' => array( 'EUR', 'CYN' ),
					'SI' => array( 'EUR', 'CYN' ),
					'ES' => array( 'EUR', 'CYN' ),
					'SE' => array( 'EUR', 'CYN' ),
					'CH' => array( 'EUR', 'CYN' ),
					'GB' => array( 'GBP', 'CYN' ),
					'HK' => array( 'HKD', 'CYN' ),
					'JP' => array( 'JPY', 'CYN' ),
					'MY' => array( 'MYR', 'CYN' ),
					'NZ' => array( 'NZD', 'CYN' ),
					'SG' => array( 'SGD', 'CYN' ),
					'US' => array( 'USD', 'CYN' ),
				); ?>
				<div id="stripe_use_alipay" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="CNY,AUD,CAD,EUR,GBP,HKD,JPY,SGD,MYR,NZD,USD" data-countries="AU,AT,BE,BG,CA,HR,CY,CZ,DK,EE,FI,FR,DE,GI,GR,HK,HU,IE,IT,JP,LV,LI,LT,LU,MY,LT,NL,NZ,NO,PT,RO,SG,SK,SI,ES,SE,CH,GB,US" data-country-currency='{"AU": ["AUD","CYN"],"CA": ["CAD","CYN"],"AT": ["EUR","CYN"],"BE": ["EUR","CYN"],"BG": ["EUR","CYN"],"CY": ["EUR","CYN"],"CZ": ["EUR","CYN"],"DK": ["EUR","CYN"],"EE": ["EUR","CYN"],"FI": ["EUR","CYN"],"FR": ["EUR","CYN"],"DE": ["EUR","CYN"],"GR": ["EUR","CYN"],"IE": ["EUR","CYN"],"IT": ["EUR","CYN"],"LV": ["EUR","CYN"],"LT": ["EUR","CYN"],"LU": ["EUR","CYN"],"MT": ["EUR","CYN"],"NL": ["EUR","CYN"],"NO": ["EUR","CYN"],"PT": ["EUR","CYN"],"RO": ["EUR","CYN"],"SK": ["EUR","CYN"],"SI": ["EUR","CYN"],"ES": ["EUR","CYN"],"SE": ["EUR","CYN"],"CH": ["EUR","CYN"],"GB": ["GBP","CYN"],"HK": ["HKD","CYN"],"JP": ["JPY","CYN"],"MY": ["MYR","CYN"],"NZ": ["NZD","CYN"],"SG": ["SGD","CYN"],"US": ["USD","CYN"]}' style="padding:0px !important;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'CNY', 'AUD', 'CAD', 'EUR', 'GBP', 'HKD', 'JPY', 'SGD', 'MYR', 'NZD', 'USD' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( '' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Alipay', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_alipay" id="ec_option_stripe_alipay" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
					<div class="ec_status_error" style="padding:5px 15px;<?php if ( isset( $alipay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $alipay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) { ?> display:none<?php }?>"><span class="ec_status_label" style="line-height:1.2em;"><?php esc_attr_e( 'This payment type requires your business country and currency to match local norms or process payments in CYN.', 'wp-easycart' ); ?></span></div>
				</div>
				<?php $grabpay_rules = array(
					'MY' => array( 'MYR' ),
					'SG' => array( 'SGD' ),
				); ?>
				<div id="stripe_use_grabpay" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="SGD,MYR" data-countries="MY,SG" data-country-currency='{"MY": ["MYR"],"SG": ["SGD"]}' style="padding:0px !important;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'SGD', 'MYR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( '' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable GrabPay (common in Southeast Asia)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_grabpay" id="ec_option_stripe_grabpay" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
					<div class="ec_status_error" style="padding:5px 15px;<?php if ( isset( $grabpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $grabpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) { ?> display:none<?php }?>"><span class="ec_status_label" style="line-height:1.2em;"><?php esc_attr_e( 'This payment type requires your business country and currency to match local norms.', 'wp-easycart' ); ?></span></div>
				</div>
				<?php $wechatpay_rules = array(
					'AU' => array( 'AUD', 'CYN' ),
					'CA' => array( 'CAD', 'CYN' ),
					'AT' => array( 'EUR', 'CYN' ),
					'BE' => array( 'EUR', 'CYN' ),
					'DK' => array( 'EUR', 'DKK', 'CYN' ),
					'FI' => array( 'EUR', 'CYN' ),
					'FR' => array( 'EUR', 'CYN' ),
					'DE' => array( 'EUR', 'CYN' ),
					'IE' => array( 'EUR', 'CYN' ),
					'IT' => array( 'EUR', 'CYN' ),
					'LU' => array( 'EUR', 'CYN' ),
					'NL' => array( 'EUR', 'CYN' ),
					'NO' => array( 'EUR', 'NOK', 'CYN' ),
					'PT' => array( 'EUR', 'CYN' ),
					'ES' => array( 'EUR', 'CYN' ),
					'SE' => array( 'EUR', 'SEK', 'CYN' ),
					'CH' => array( 'EUR', 'CHF', 'CYN' ),
					'GB' => array( 'GBP', 'CYN' ),
					'HK' => array( 'HKD', 'CYN' ),
					'JP' => array( 'JPY', 'CYN' ),
					'SG' => array( 'SGD', 'CYN' ),
					'US' => array( 'USD', 'CYN' ),
				); ?>
				<div id="stripe_use_wechatpay" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="CNY,AUD,CAD,EUR,GBP,HKD,JPY,SGD,USD,DKK,NOK,SEK,CHF" data-countries="AU,AT,BE,CA,DK,FI,FR,DE,HK,IE,IT,JP,LU,NL,NO,PT,SG,ES,SE,CH,GB,US" data-country-currency='{"AU": ["AUD","CYN"],"CA": ["CAD","CYN"],"AT": ["EUR","CYN"],"BE": ["EUR","CYN"],"DK": ["EUR","DKK","CYN"],"FI": ["EUR","CYN"],"FR": ["EUR","CYN"],"DE": ["EUR","CYN"],"IE": ["EUR","CYN"],"IT": ["EUR","CYN"],"LU": ["EUR","CYN"],"NL": ["EUR","CYN"],"NO": ["EUR","NOK","CYN"],"PT": ["EUR","CYN"],"ES": ["EUR","CYN"],"SE": ["EUR","SEK","CYN"],"CH": ["EUR","CHF","CYN"],"GB": ["GBP","CYN"],"HK": ["HKD","CYN"],"JP": ["JPY","CYN"],"SG": ["SGD","CYN"],"US": ["USD","CYN"]}' style="padding:0px !important;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'CNY', 'AUD', 'CAD', 'EUR', 'GBP', 'HKD', 'JPY', 'SGD', 'USD', 'DKK', 'NOK', 'SEK', 'CHF' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( '' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable WeChat Pay', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_wechat" id="ec_option_stripe_wechat" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
					<div class="ec_status_error" style="padding:5px 15px;<?php if ( isset( $wechatpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $wechatpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) { ?> display:none<?php }?>"><span class="ec_status_label" style="line-height:1.2em;"><?php esc_attr_e( 'This payment type requires your business country and currency to match local norms or process payments in CYN.', 'wp-easycart' ); ?></span></div>
				</div>
				<div id="stripe_use_link" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show" style="padding:0px !important;">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Link Payments', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_enable_link" id="ec_option_stripe_enable_link" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>

			<div id="stripe_bank_redirects" class="ec_admin_stripe_section" data-currencies="EUR,PLN,MYR" data-countries="AU,AT,BE,BG,CA,HR,CY,CZ,DK,EE,FI,FR,DE,GI,GR,HK,HU,IE,IT,JP,LV,LI,LT,LU,MT,MX,NL,NZ,NO,PO,PT,RO,SG,SK,SI,ES,SE,CH,GB,US,MY"<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'EUR', 'PLN', 'MYR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU', 'AT', 'BE', 'BG', 'CA', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GI', 'GR', 'HK', 'HU', 'IE', 'IT', 'JP', 'LV', 'LI', 'LT', 'LU', 'MT', 'MX', 'NL', 'NZ', 'NO', 'PL', 'PT', 'RO', 'SG', 'SK', 'SI', 'ES', 'SE', 'CH', 'GB', 'US', 'MY' ) ) ) { echo ' style="display:none;"'; } ?>>
				<h3 style="float:left; width:100%; margin:25px 0 10px; border-bottom:1px solid #CCC; padding:0 0 5px;"><?php esc_attr_e( 'Bank Redirects', 'wp-easycart' ); ?></h3>
				<div id="stripe_use_bancontact" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="EUR" data-countries="AU,AT,BE,BG,CA,HR,CY,CZ,DK,EE,FI,FR,DE,GI,GR,HK,HU,IS,IE,IT,JP,LV,LI,LT,LU,MT,MX,NL,NZ,NO,PO,PT,RO,SG,SK,SI,ES,SE,CH,GB,US" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'EUR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU', 'AT', 'BE', 'BG', 'CA', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GI', 'GR', 'HK', 'HU', 'IS', 'IE', 'IT', 'JP', 'LV', 'LI', 'LT', 'LU', 'MT', 'MX', 'NL', 'NZ', 'NO', 'PL', 'PT', 'RO', 'SG', 'SK', 'SI', 'ES', 'SE', 'CH', 'GB', 'US' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Bancontact (Common in Belgium)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_bancontact" id="ec_option_stripe_bancontact" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_blik" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="PLN" data-countries="AT,BE,BG,HR,CY,CZ,DK,EE,FI,FR,DE,GR,HU,IS,IE,IT,LV,LI,LT,LU,MT,NL,NO,PO,PT,RO,SK,SI,ES,SE" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'PLN' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IS', 'IE', 'IT', 'LV', 'LI', 'LT', 'LU', 'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable BLIK (Common in Poland)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_blik" id="ec_option_stripe_blik" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_eps" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="EUR" data-countries="AU,AT,BE,BG,CA,HR,CY,CZ,DK,EE,FI,FR,DE,GI,GR,HK,HU,IE,IT,JP,LV,LI,LT,LU,MT,MX,NL,NZ,NO,PO,PT,RO,SG,SK,SI,ES,SE,CH,GB,US" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'EUR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU', 'AT', 'BE', 'BG', 'CA', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GI', 'GR', 'HK', 'HU', 'IE', 'IT', 'JP', 'LV', 'LI', 'LT', 'LU', 'MT', 'MX', 'NL', 'NZ', 'NO', 'PL', 'PT', 'RO', 'SG', 'SK', 'SI', 'ES', 'SE', 'CH', 'GB', 'US' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable EPS (Common in Austria)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_eps" id="ec_option_stripe_eps" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_fpx" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="" data-countries="" data-currencies-future="MYR" data-countries-future="MY" style="padding:0px !important; font-size:12px;<?php if ( true || ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'MYR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'MY' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable FPX (Common in Malaysia)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_fpx" id="ec_option_stripe_fpx" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_giropay" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="EUR" data-countries="AU,AT,BE,BG,CA,HR,CY,CZ,DK,EE,FI,FR,DE,GI,GR,HK,HG,IE,IT,JP,LV,LI,LT,LU,MT,MX,NL,NZ,NO,PO,PT,RO,SG,SK,SI,ES,SE,CH,GB,US" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'EUR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU', 'AT', 'BE', 'BG', 'CA', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GI', 'GR', 'HK', 'HG', 'IE', 'IT', 'JP', 'LV', 'LI', 'LT', 'LU', 'MT', 'MX', 'NL', 'NZ', 'NO', 'PL', 'PT', 'RO', 'SG', 'SK', 'SI', 'ES', 'SE', 'CH', 'GB', 'US' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable giropay (Common in Germany)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_giropay" id="ec_option_stripe_giropay" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_ideal" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="EUR" data-countries="AU,AT,BE,BG,CA,HR,CY,CZ,DK,EE,FI,FR,DE,GI,GR,HK,GH,IE,IT,JP,LV,LI,LT,LU,MT,MX,NL,NZ,NO,PO,PT,RO,SG,SK,SI,ES,SE,CH,GB,US" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'EUR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU', 'AT', 'BE', 'BG', 'CA', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GI', 'GR', 'HK', 'GH', 'IE', 'IT', 'JP', 'LV', 'LI', 'LT', 'LU', 'MT', 'MX', 'NL', 'NZ', 'NO', 'PL', 'PT', 'RO', 'SG', 'SK', 'SI', 'ES', 'SE', 'CH', 'GB', 'US' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable iDEAL (the most popular payment method in the Netherlands)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_enable_ideal" id="ec_option_stripe_enable_ideal" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_p24" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="EUR,PLN" data-countries="AU,AT,BE,BG,CA,HR,CZ,DK,EE,FI,FR,DE,GI,GR,HK,HG,IE,IT,JP,LV,LI,LT,LU,MT,MX,NL,NZ,NO,PO,PT,RO,SG,SK,SI,ES,SE,CH,GB,US" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'EUR', 'PLN' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU', 'AT', 'BE', 'BG', 'CA', 'HR', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GI', 'GR', 'HK', 'HG', 'IE', 'IT', 'JP', 'LV', 'LI', 'LT', 'LU', 'MT', 'MX', 'NL', 'NZ', 'NO', 'PL', 'PT', 'RO', 'SG', 'SK', 'SI', 'ES', 'SE', 'CH', 'GB', 'US' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Przelewy24 (Common in Poland)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_p24" id="ec_option_stripe_p24" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_sofort" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="" data-countries="" data-currencies-future="EUR" data-countries-future="AU,AT,BE,BG,CA,HR,CY,CZ,DK,EE,FI,FR,DE,GI,GR,HK,GH,IE,IT,JP,LV,LI,LT,LU,MT,MX,NL,NZ,NO,PO,PT,RO,SG,SK,SI,ES,SE,CH,GB,US" style="padding:0px !important; font-size:12px;<?php if ( true|| ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'EUR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU', 'AT', 'BE', 'BG', 'CA', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GI', 'GR', 'HK', 'GH', 'IE', 'IT', 'JP', 'LV', 'LI', 'LT', 'LU', 'MT', 'MX', 'NL', 'NZ', 'NO', 'PL', 'PT', 'RO', 'SG', 'SK', 'SI', 'ES', 'SE', 'CH', 'GB', 'US' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Sofort (Common in Europe)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_sofort" id="ec_option_stripe_sofort" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>

			<div id="stripe_bank_debits" class="ec_admin_stripe_section" data-currencies="AUD" data-countries="AU" data-currencies-future="GBP,AUD,EUR" data-countries-future="GB,AU,AT,BE,GB,CA,HR,CY,CZ,DK,EE,FI,FR,DE,GI,GR,HK,HG,IE,IT,JP,LV,LI,LT,LU,MT,MX,NL,NZ,NO,PO,PT,RO,SG,SK,SI,ES,SE,CH,US"<?php /* future usage if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'GBP', 'AUD', 'EUR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'GB', 'AU', 'AT', 'BE', 'GB', 'CA', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GI', 'GR', 'HK', 'HG', 'IE', 'IT', 'JP', 'LV', 'LI', 'LT', 'LU', 'MT', 'MX', 'NL', 'NZ', 'NO', 'PL', 'PT', 'RO', 'SG', 'SK', 'SI', 'ES', 'SE', 'CH', 'US' ) ) ) { echo ' style="display:none;"'; } */if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'AUD' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU' ) ) ) { echo ' style="display:none;"'; } ?>>
				<h3 style="float:left; width:100%; margin:25px 0 10px; border-bottom:1px solid #CCC; padding:0 0 5px;"><?php esc_attr_e( 'Bank Debits', 'wp-easycart' ); ?></h3>
				<div id="stripe_use_bacs" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="" data-countries="" data-currencies-future="GBP" data-countries-future="GB" style="padding:0px !important; font-size:12px;<?php if ( true || ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'GBP' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'GB' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Bacs Direct Debit in the UK', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_bacs" id="ec_option_stripe_bacs" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_becs" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="AUD" data-countries="AU" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'AUD' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable BECS Direct Debit in Australia', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_becs" id="ec_option_stripe_becs" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_sepa" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="" data-countries="" data-currencies-future="EUR" data-countries-future="AU,AT,BE,GB,CA,HR,CY,CZ,DK,EE,FI,FR,DE,GI,GR,HK,HG,IE,IT,JP,LV,LI,LT,LU,MT,MX,NL,NZ,NO,PO,PT,RO,SG,SK,SI,ES,SE,CH,GB,US" style="padding:0px !important; font-size:12px;<?php if ( true || ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'EUR' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'AU', 'AT', 'BE', 'GB', 'CA', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GI', 'GR', 'HK', 'HG', 'IE', 'IT', 'JP', 'LV', 'LI', 'LT', 'LU', 'MT', 'MX', 'NL', 'NZ', 'NO', 'PL', 'PT', 'RO', 'SG', 'SK', 'SI', 'ES', 'SE', 'CH', 'GB', 'US' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable SEPA Direct Debit (Europe)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_sepa" id="ec_option_stripe_sepa" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>

			<div id="stripe_realtime_payments" class="ec_admin_stripe_section" data-currencies="BRL,SGD,THB" data-countries="BR,SG,TH"<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'BRL', 'SGD', 'THB' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'BR', 'SG', 'TH' ) ) ) { echo ' style="display:none;"'; } ?>>
				<h3 style="float:left; width:100%; margin:25px 0 10px; border-bottom:1px solid #CCC; padding:0 0 5px;"><?php esc_attr_e( 'Real Time Payments', 'wp-easycart' ); ?></h3>
				<div id="stripe_use_pix" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="BRL" data-countries="BR" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'BRL' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'BR' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Pix (Brazil)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_pix" id="ec_option_stripe_pix" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_paynow" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="SGD" data-countries="SG" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'SGD' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'SG' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable PayNow (Singapore)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_paynow" id="ec_option_stripe_paynow" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_promptpay" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="THB" data-countries="TH" style="padding:0px !important; font-size:12px;<?php if ( ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'THB' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'TH' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable PromptPay (Thailand)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_promptpay" id="ec_option_stripe_promptpay" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>

			<div id="stripe_realtime_payments" class="ec_admin_stripe_section" data-currencies="" data-countries="" data-currencies-future="BRL,JPY,MXN" data-countries-future="BR,JP,MX"<?php if ( true || ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'BRL', 'JPY', 'MXN' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'BR', 'JP', 'MX' ) ) ) { echo ' style="display:none;"'; } ?>>
				<h3 style="float:left; width:100%; margin:25px 0 10px; border-bottom:1px solid #CCC; padding:0 0 5px;"><?php esc_attr_e( 'Vouchers', 'wp-easycart' ); ?></h3>
				<div id="stripe_use_boleto" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="" data-countries="" data-currencies-future="BRL" data-countries-future="BR" style="padding:0px !important; font-size:12px;<?php if ( true || ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'BRL' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'BR' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Boleto (Brazil)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_boleto" id="ec_option_stripe_boleto" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_konbini" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="" data-countries="" data-currencies-future="JPY" data-countries-future="JP" style="padding:0px !important; font-size:12px;<?php if ( true || ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'JPY' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'JP' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable Konbini (Japan)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_konbini" id="ec_option_stripe_konbini" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
				<div id="stripe_use_oxxo" class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show ec_admin_stripe_settings_row" data-currencies="" data-countries="" data-currencies-future="MXN" data-countries-future="MX" style="padding:0px !important; font-size:12px;<?php if ( true || ! in_array( get_option( 'ec_option_stripe_currency' ), array( 'MXN' ) ) || ! in_array( get_option( 'ec_option_stripe_company_country' ), array( 'MX' ) ) ) { echo ' display:none;'; } ?>">
					<label style="float:left; width:100%;"><?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:0px;"></span>' ) ); ?><?php esc_attr_e( 'Enable OXXO (Mexico)', 'wp-easycart' ); ?></label>
					<fieldset class="wp-easycart-admin-field-container">
						<select name="ec_option_stripe_oxxo" id="ec_option_stripe_oxxo" onchange="ec_admin_update_stripe_connect_option( jQuery( this ) );" class="wp-easycart-admin-field">
							<option value="0" selected="selected"><?php esc_attr_e( 'Only Available in PRO &amp; Premium', 'wp-easycart' ); ?></option>
						</select>
						<div class="wp-easycart-admin-icons-container">
							<div class="wp-easycart-admin-icon-close">
								<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
								<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			<?php }?>
			<input type="hidden" name="use_stripe_connect" id="use_stripe_connect" value="<?php echo ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) ? 1 : 0; ?>" />
			<input type="hidden" name="ec_option_stripe_connect_use_sandbox" id="ec_option_stripe_connect_use_sandbox" value="<?php echo ( get_option( 'ec_option_stripe_connect_use_sandbox' ) ) ? 1 : 0; ?>" />
		</div>
		<div class="ec_admin_toggles_wrap">
			<div class="ec_admin_toggle">
				<span><?php esc_attr_e( 'Enable Live', 'wp-easycart' ); ?>:</span>
				<?php if ( get_option( 'ec_option_stripe_connect_production_access_token' ) == '' ) { ?>
				<a href="<?php echo esc_url_raw( wp_easycart_admin()->available_url ); ?>/connect/?step=start&redirect=<?php echo urlencode( esc_url_raw( admin_url() ) . '?ec_admin_form_action=stripe_onboard&env=production&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-stripe' ) ); ?>&env=production">
				<span></span>
				<?php }?>
				<label class="ec_admin_switch">
					<input type="checkbox" onclick="return stripe_live_on_off();" class="ec_admin_slider_checkbox" value="1" id="ec_option_stripe_connect_enable_live"<?php if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && !get_option( 'ec_option_stripe_connect_use_sandbox' ) && get_option( 'ec_option_stripe_connect_production_access_token' ) != '' ) { ?> checked="checked"<?php }?>>
					<span class="ec_admin_slider round"></span>
				</label>
			<?php if ( get_option( 'ec_option_stripe_connect_production_access_token' ) == '' ) { ?>
				</a> 
				<?php }?>
			</div>
			<div class="ec_admin_toggle">
				<span><?php esc_attr_e( 'Enable Sandbox', 'wp-easycart' ); ?>:</span>
				<?php if ( get_option( 'ec_option_stripe_connect_sandbox_access_token' ) == '' ) { ?>
				<a href="<?php echo esc_url_raw( wp_easycart_admin()->available_url ); ?>/connect/?step=start&redirect=<?php echo urlencode( esc_url_raw( admin_url() ) . '?ec_admin_form_action=stripe_onboard&env=sandbox&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-stripe' ) ); ?>&env=sandbox">
				<span></span>
				<?php }?>
				<label class="ec_admin_switch">
					<input type="checkbox" onclick="return stripe_sandbox_on_off();" class="ec_admin_slider_checkbox" value="<1" id="ec_option_stripe_connect_enable_sandbox"<?php if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) && get_option( 'ec_option_stripe_connect_sandbox_access_token' ) != '' ) { ?> checked="checked"<?php }?>>
					<span class="ec_admin_slider round"></span>
				</label>
				<?php if ( get_option( 'ec_option_stripe_connect_sandbox_access_token' ) == '' ) { ?>
				</a>
				<?php }?>
			</div>
		</div>
		<input style="position:absolute; left:-1000px; top:-1000px;" type="text" id="stripe_webhook_url" value="<?php echo esc_url( get_site_url() . '?wpeasycarthook=stripe-webhook' ); ?>" />
		<?php if ( get_option( 'ec_option_stripe_connect_sandbox_access_token' ) != '' || get_option( 'ec_option_stripe_connect_production_access_token' ) != '' ) { ?>
		<div class="ec_admin_webhook">
			<?php global $wpdb; $webhooks = $wpdb->get_results( "SELECT * FROM ec_webhook LIMIT 1" ); if ( $webhooks ) { ?>
			<div class="dashicons dashicons-yes-alt"></div> <?php esc_attr_e( 'It looks like you have already added the Webhook URL to your Stripe account, but if you ever need the information again: ', 'wp-easycart' ); ?> 
			<a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=stripe" target="_blank"><?php esc_attr_e( 'Click Here to Learn More', 'wp-easycart' ); ?></a>
			<?php } else { ?>
			<div class="dashicons dashicons-warning"></div> <strong><?php esc_attr_e( 'To Do', 'wp-easycart' ); ?>:</strong> <?php esc_attr_e( 'You must add the Webhook URL to your Stripe account for best results.', 'wp-easycart' ); ?> 
			<a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=stripe" target="_blank"><?php esc_attr_e( 'Click Here to Learn More', 'wp-easycart' ); ?></a>
			<?php }?>
		</div>

		<div class="ec_admin_webhook">
			<strong><?php esc_attr_e( 'Webhook URL', 'wp-easycart' ); ?>:</strong> 
			<a href="#" onclick="ec_admin_copy_stripe_webhook(); return false;"><?php esc_attr_e( 'Copy Webhook to Clipboard', 'wp-easycart' ); ?></a>
		</div>
		<div class="ec_admin_webhook_copy" id="stripe_webhook_copied" style="display:none;"><?php esc_attr_e( 'Webhook URL has been copied to your clipboard!', 'wp-easycart' ); ?></div>
		<?php }?>
	</div>
</div>