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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'myProject' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'Q8Yv9BWm+nmK$L;bI}BZd<8&:Z8d&7h`/<6K{-+w-?|]`qf5E`P/nj>^uqETUH8*' );
define( 'SECURE_AUTH_KEY',  '/]^BNXg$8y:cLEk49qx>P(=%cX%x;|@h[*nc@!K^>[O?$tK5AP;:I%5R__*8lMo6' );
define( 'LOGGED_IN_KEY',    '1K^~b)b;RJ Lcs2:qhk<VUJtVG .z=_YH%Ur7t!mC*9UK?@TRa;HC`@>6LMpUmhU' );
define( 'NONCE_KEY',        'LD~y$BdwnwJ)JP/!{MyO5M>_<x{|)H*H]=UwWvoz$}xM%_l%valGMxYQy4unuY8t' );
define( 'AUTH_SALT',        '(|Twv6VpqNWL49=t?mtR$XI<XX~{s?W0S:qLhNtlooXfDr;yF{XWfIJGMPh:,>SD' );
define( 'SECURE_AUTH_SALT', '_G(m/ah^:L{:3{`WfBkzIv*Mi#?RE-(1`m45>?@!-L2Zz-$IL^#)0-Tdn!-T!ftd' );
define( 'LOGGED_IN_SALT',   '0#h~(`1c5ul0Cp=yVC<K`/~PAU]!:Ps$dwPY;.=e`3ZyQwDAY/e^3hNgi69D$s|Q' );
define( 'NONCE_SALT',       '-E5Ze`j*_N^_[TkhAFCj|9_v1e&.,0Kd6Q)#@0$y.2>7t/wm+,RN9L^e)yq)M|p-' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
 
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
