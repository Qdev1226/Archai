<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type='text/css'>
    <!--
		.ec_title {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 18px; float:left; width:100%; border-bottom:3px solid #CCC; margin-bottom:15px; }
        .ec_image{ width:200px;}
		.ec_image > img{ max-width:200px; }
		.ec_content_row{ font-family: Arial, Helvetica, sans-serif; font-size:12px; float:left; width:100%; margin:0 0 10px; }
		.ec_content_row strong{ font-weight:bold; }
		.ec_content_row.ec_extra_margin{ margin-top:25px; }
	-->
    </style>
</head>

<body>
	<table>
    	<tbody>
    		<tr>
            	<td width="225" style="text-align:left;" valign="top"><img src="<?php echo esc_attr( $product->get_first_image_url( ) ); ?>" alt="<?php echo esc_attr( $product->title ); ?>" style="max-width:200px; width:200px; height:auto;" /></td>
                <td valign="top">
                    <h1 style="margin-top:0px; margin-bottom:20px;"><?php echo esc_attr( htmlspecialchars( $product->title, ENT_QUOTES ) ); ?></h1>
                    <p><a href="<?php echo esc_attr( $product->get_product_link( ) ); ?>" target="_blank"><?php echo wp_easycart_language( )->get_text( 'ec_stock_notify_email', 'view_now' ); ?></a></p>
                    <p><?php echo esc_attr( $product->display_product_description( ) ); ?></p>
                </td>
            </tr>
            <tr><td></td><td><a href="<?php echo esc_attr( $product->get_product_unsubscribe_link( $subscriber->email, $subscriber->product_subscriber_id ) ); ?>" target="_blank"><?php echo wp_easycart_language( )->get_text( 'ec_stock_notify_email', 'unsubscribe' ); ?></a></td></tr>
        </tbody>
    </table>
</body>
</html>