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
define( 'DB_NAME', 'wordpress-netvlies' );

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
define( 'AUTH_KEY',         'H9*575y0!<%3t0,8tZAw~}z}Ag.sAMh3{<v~lz)o)Z$_{(E{6e1|vjQf7y!uxVUS' );
define( 'SECURE_AUTH_KEY',  '^>AeU:9XQ5-GPGHC-JS]r*XKgu-~K`/} r!SVHplc8T-}o[Kz$8Qg|56g#[b:n*y' );
define( 'LOGGED_IN_KEY',    '-K$-B |}-RC[fIt^6l~=d($M[Sj`3f`7iW/?@P=ok1P{[cDP.7qi>$;?p-I0=2{e' );
define( 'NONCE_KEY',        '`e/6oTYR]e6)|hTSIKg&2 SqzNV|n>8oycOq:(<:! $!TWEKANKISqsS|^+xNn?D' );
define( 'AUTH_SALT',        'PQz69|_=a 6Z+E(@H|;$&Ah{Sj^x%<z>}s|A|E2Dn7u<waVIG9NxvSxUk91A+GQC' );
define( 'SECURE_AUTH_SALT', '5/UO3AfA-k%wR/$gMyuXsB@lc$1x<Il =!`ENEFp?^at,65ub9)8S88b_k<xv9R~' );
define( 'LOGGED_IN_SALT',   '{J&Pj}plcdinEbgnrhmAk<{U&#:Xq>kT*Y ^jwGtIT)ehu1 s^ R;7r4/^Fg,sBg' );
define( 'NONCE_SALT',       'sUodXV~QI7uKM#aMjr!NBqv6)QW;APa,#J@wM,#LU~j30;9Pn6ArN_;HU,k&Ly-n' );

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
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */

define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
