<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'archai' );

/** Database username */
define( 'DB_USER', 'Jay' );

/** Database password */
define( 'DB_PASSWORD', 'salmonella' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'TXYPKYH16e5}PJV `i[[avsyH3C2>3){8{~B_$>-,@yVyCk{XHnEL.C,jE5~}F{-' );
define( 'SECURE_AUTH_KEY',  '6X4;m1a$EaeVGPY+A.XT[(>IW<>ACF~y7IIQ:nKNmZcP&D?@}E{k]^(v1$)0IozN' );
define( 'LOGGED_IN_KEY',    '.;l+FpUzepw^K.&60@m4tj7S|_Eb(@)=0}Ln3c|0yX#!,SKNx_Nux2)FB@/uuP_U' );
define( 'NONCE_KEY',        'v{k Si[GBGx~^qi* )i+oP:[B?V+M%/&g|1K0|}UKhR@>YXQt51EYljTGg#nmJFr' );
define( 'AUTH_SALT',        '-{]AG*TK=Vj2yEcD5(^mZ7|wg:0jC))Y.m7:wVf?iB1yoM)~4hS9(8qsr$Pm&T!+' );
define( 'SECURE_AUTH_SALT', 'a[l4QVg@lgSRn3V)`gUJHu.6i4JuU0WC`:0;9/s{f!/vx^g+H(v2::b*smvn~Q(R' );
define( 'LOGGED_IN_SALT',   '8n;NNUwAm|[Nk4@|rQOJ},LkqoM8/Qm1h{7EshmoO~/$o+.Co%[jhX:3`+4/_.fR' );
define( 'NONCE_SALT',       'v;}3,Q$KXZ,O`sCyQJ5jfk%zn=EM7pq&_}Lt hVn9Y&MLuh/0#x]6>&p_}yucyOa' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
