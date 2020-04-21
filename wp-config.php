<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'releituras');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

if ( !defined('WP_CLI') ) {
    define('WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
    define('WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']);
}



/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'esUUTVDK4tnUJZuiW9UYtvc2ZWNuWNlUHuqfXMRB5yxy32sngEgpikL5igWLF2q9');
define('SECURE_AUTH_KEY',  '0bKoE73ZES5eNaMrKGrEsA4U5rsZHlCgpqjQyF3CVTOl4bm5yfXIBwpnHKxKyCTM');
define('LOGGED_IN_KEY',    'EQK9AUB1c7PhIggPGyWY5pvc9paUDxTFy7SJhtGOPFrqz74VI9jAieq4wZhXMdkJ');
define('NONCE_KEY',        'pYPv8yMVfe8HzlQOF6pD16gcIBU4DzaAp0gfyI57JELEQUCSkEvJdcEVy9Bhckdl');
define('AUTH_SALT',        'uQolRXod7UL1tBi6JGI0Jx4mCNtrF9Lzbwaq9jcJBMDjWTUajuqq5cJZbLievxC8');
define('SECURE_AUTH_SALT', 'va9wlthxhiLg0re3JujBwalASsrJOhKvyZIAe9jQC5NnSiwTpZzAsGv32dp0nyZR');
define('LOGGED_IN_SALT',   'dCwL5PK3d8hus5mMPcGva7US8nBIUTHZA4H6IviTxl6sjAkB0PtMxWlczuuTqd8d');
define('NONCE_SALT',       'NMxIcARHVidkHQx37iQXdx9JGLFncHYzYcRwC8wsrbhf6OEO9fM5YKrW836tIZH4');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
