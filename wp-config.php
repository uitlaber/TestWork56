<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test_project' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '100102Zein@' );

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
define( 'AUTH_KEY',         'x7@{xznGo{|lSxtoB*s^v;I;TMxZJODAt/2c&7=i7&=R2qnJc<5_RHhks+|S1d>.' );
define( 'SECURE_AUTH_KEY',  ':]D#QfM.d ]DUM&bqGjI_)[PZq+PdI{IfsVqBZ&Kr,(jx:ADwT{dMNp]Dc 65;_}' );
define( 'LOGGED_IN_KEY',    'H8GO-9H~L0vTLDd^+?ABN;<XOBunj,Fk=EH=:t?F/xb6B@Ar(^#@b+xz|z_TfA+v' );
define( 'NONCE_KEY',        'E:Ku!g^)%OPASTv{Wz11P-dqo%`$+#tPe|3<oiE7RkANJC%&&f[{6=3jI2p@U:SI' );
define( 'AUTH_SALT',        'q=|NN!UeU.ZOz34n9cbgL}?H7c(/uAd~=Rt*a#Mf]=OyI}wZSJVR3z(jJXnsDk}?' );
define( 'SECURE_AUTH_SALT', '4amN9!O_Hh=%!;;{@tS28}p/0W^;Yy<O[1qOG<%5pEQn^xqO3`p-mzl6aot8idZ_' );
define( 'LOGGED_IN_SALT',   'K]Nx|!p[o,B:M7tG4Bzs.xu`cp[!1:&drs#Nlsd2oK)(%7jM%;6W#_K5)3:Xkm<g' );
define( 'NONCE_SALT',       '=XFNs(W8!mH|y.(3=AWIFY0B vE4n>mlXSaEUYNp&RCy>Ix Ke_/_8Tb6NqanY2x' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define('WP_DEBUG', true );
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false); 
define('SCRIPT_DEBUG', true);

/* Add any custom values between this line and the "stop editing" line. */

define('OPENWEATHERMAP_API_KEY', 'bd5e378503939ddaee76f12ad7a97608');



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
