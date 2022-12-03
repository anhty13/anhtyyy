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
define( 'DB_NAME', 'group3' );

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
define( 'AUTH_KEY',         'Eoh609?Yau%D!,]`+2Xs0?*?G.]RE3|5AF,B*Z3)dYUPJ<%ydTy1,/i|^oJrF{]f' );
define( 'SECURE_AUTH_KEY',  'Aye]dVi:Dkrkp@BPr/%u7*j=R ]^uwEez(<k&X^1X{~3$mA[PZSy40Kp^xu]ku1F' );
define( 'LOGGED_IN_KEY',    'cSx2e 2DK&o3Pdq3,L^AK~G1e6e}XTrCErI*J(}I/kZs<3nk&;R^Up1^W{U-+mIj' );
define( 'NONCE_KEY',        '9hn7[f16^&f,#-0(jN)cM{i@p&W}-$ jJ@AfCCU?H,M8Q:l_ ~g6|cJ(<6Ag$t?;' );
define( 'AUTH_SALT',        'x^D?>C15hdG8F=F.e4ovi)a&m~!CphSPar)vohg( `EErQp.fgX(NA7[*$~)2CEy' );
define( 'SECURE_AUTH_SALT', 'qGZX(xnuMPo%[-&_Fq 6iCFO4MXLL}P?*mT)=J>itta9H7jxEc<&_4%6AA ~Ea-~' );
define( 'LOGGED_IN_SALT',   '>FZ$$.SB={SQer$+xJ?%pouV]VJaO#e8I|b#XMd?=`WKi[:Sij?@sN;@o?D}Yh.x' );
define( 'NONCE_SALT',       ')2vUNUc`B{*qE&}vR?)gtG_nTY^}M>Am)!6uMVQ`z(Ov ao^F`{-{I&^T$V1~-LG' );

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
