<?php
define( 'WP_CACHE', true );
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'potentatemedia' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'I}nTy~(,?I-S&r%{)b-f$| PLg;BQJb]TtF^W_C29nz`!&kZKYk<@}.h|mOk6zQ~' );
define( 'SECURE_AUTH_KEY',  ',go;sMigU,~3dP11Rq[KBZHNq(f5LSD;EO7*OAX?9my3_+n,>`JwLF>fyiT7 :;~' );
define( 'LOGGED_IN_KEY',    'L LhK!3J(cBG5G]BMT%G8d2gH(A ws?C >f_j?INy-O,IQjBry5)9t(ER!yWL(3m' );
define( 'NONCE_KEY',        '+)!T;b`Ygc_Ncy/lN=(Aa2KlAQne!mOo)MngFhd:-73IzW~N<?9QJSmdWQ_BNFj1' );
define( 'AUTH_SALT',        'i^OeU^#*n?TLuJXp%`tnmtIfHg2,>4U>1s4a~zIu`q]3BKH]Q?k^!SA;{0|^[eA(' );
define( 'SECURE_AUTH_SALT', '&8d54l-=|4%DYSwK[Q~88Bp!sUP=fD{CM/ype?5a!Fr-co/Bl,-xLc_I$scjo$QX' );
define( 'LOGGED_IN_SALT',   'XI6G-]SQ6r}e|xV0E||Qtps/;<ljMAJ=W%`Mi5t|`3XGK^oe|q`t:lkJi3w`48>w' );
define( 'NONCE_SALT',       'AJ7@Ir5,f]A5eag-@<$}%yIdlm(#Pdr%g,!(n?&:59[@Wto>lq,w;B`I{B{6`,&j' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'media_';

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
