<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Twentyfifteen_Child
 */

$standalone_autoload = dirname(__DIR__).'/vendor/autoload.php';

if (!file_exists($standalone_autoload)) { // Then this is used as a dependency, not good...
    throw new \InvalidArgumentException(
        'When used as a Composer dependency, you must run this test as part of the root package test suite'
    );
}

require_once $standalone_autoload;

$vendorDir = dirname(__DIR__, 2);

if (basename($vendorDir) === 'vendor') {
    $alternativeBootstrap = $vendorDir.'/../tests/bootstrap.php';

    if (!file_exists($vendorDir.'/../tests/')) {
        throw new \InvalidArgumentException(
            'To run this test you must setup your own Wordpress bootstrap.php within your tests directory.'
        );
    }

    require $alternativeBootstrap;

    return;
}

$_tests_dir = getenv( 'WP_TESTS_DIR' );

define('WP_TESTS_CONFIG_FILE_PATH', __DIR__.'/wp-tests-config.php');

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL;
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Registers theme
 */
function _register_theme() {
    $theme_root = ABSPATH.'/wp-content/themes/'.WP_DEFAULT_THEME;

	add_filter( 'theme_root', function() use ( $theme_root ) {
		return $theme_root;
	} );

	register_theme_directory( $theme_root );

	add_filter( 'pre_option_template', function() {
		return WP_DEFAULT_THEME;
	});
	add_filter( 'pre_option_stylesheet', function() {
		return WP_DEFAULT_THEME;
	});
}
tests_add_filter( 'muplugins_loaded', '_register_theme' );


// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
