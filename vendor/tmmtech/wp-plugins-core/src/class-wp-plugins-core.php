<?php
/**
 * A main WP Plugins Core class which should be used for initialization.
 *
 * @package WP-Plugins-Core
 */

namespace WP_Plugins_Core;

/**
 * Main WP_Plugins_Core class.
 */
final class WP_Plugins_Core {

    /**
     * Library slug.
     *
     * @var string
     */
    public static $slug = 'wp-plugins-core';

    /**
     * Plugin name.
     *
     * @var string
     */
    public static $name;

    /**
     * Library version.
     *
     * @var string
     */
    public static $version;

    /**
     * AJAX class.
     *
     * @var AJAX
     */
    public $ajax;

    /**
     * Options.
     *
     * @var Options
     */
    public $options;

    /**
     * Plugin slug (for which the library is initialized).
     *
     * @var string
     */
    public $plugin_slug;

    /**
     * Plugin version (for which the library is initialized).
     *
     * @var string
     */
    public $plugin_version;

    /**
     * Main plugin.
     *
     * @var string
     */
    public $plugin_name;

    /**
     * Plugin main name (from which the plugin initialization begins).
     *
     * @var string
     */
    public $plugin_file;

    /**
     * Plugin dir.
     *
     * @var string
     */
    public $plugin_dir;

    /**
     * Plugin object.
     *
     * @var object
     */
    public $plugin;

    /**
     * Constructor.
     *
     * @param Object $plugin A plugin object for which to initialize WP Plugins Core library.
     */
    public function __construct( $plugin ) {
        $this->plugin_name    = $plugin::$name;
        $this->plugin_slug    = $plugin::$slug;
        $this->plugin_version = $plugin::$version;
        $this->plugin_file    = ( new \ReflectionClass( $plugin ) )->getFileName();
        $this->plugin_dir     = dirname( $this->plugin_file );
        $this->plugin         = $plugin;

        $this->define_constants();
        $this->import_plugin_files();
        // Load translations on init (WP 6.7+ warns if textdomains load earlier).
        add_action( 'init', function () { $this->load_plugin_textdomain(); } );
        $this->add_polyfills();

        // Options.
        $this->options = new Options( $this );

        // Assets.
        new Assets\Assets;

        // Notifications.
        // FORK (secure): disabled. The Notifications class fetched a remote ad/promo
        // feed from wpplugins-midlayer.tmm-technology.com hourly (via Action Scheduler)
        // and rendered it through admin_notices as the "drip" swiper carousel. Removing
        // it strips all ads/promotions and removes the only consumer of the bundled
        // Swiper library that carried CVE-2026-27212.
        // new Notifications( $this );

        // Required PHP Extensions.
        new Required_PHP_Extensions( $this );

        // Changelog.
        add_action(
            'current_screen',
            function () {
                $screen = get_current_screen();

                if ( 'dashboard' === $screen->id ) {
                    new Changelog( $this );
                }
            }
        );

        add_action(
            'init',
            function () {

                // AJAX Handler.
                $this->ajax = new AJAX( $this );
            }
        );
    }

    /**
     * Defines constants.
     */
    private function define_constants() {
        require_once __DIR__ . '/../data/constants.php';

        /**
         * Plugin version.
         */
        self::$version = TMM_WP_PLUGINS_CORE_VERSION;
    }

    /**
     * Imports plugin files.
     */
    private function import_plugin_files() {
        $src_files = [
            'class-ajax',
            // FORK (secure): 'class-action-scheduler' removed — only used by the
            // disabled Notifications class (ad/promo feed).
            'class-options',
            // FORK (secure): 'class-notifications' removed — the ad/promo feed is disabled.
            'class-setting-fields',
            'class-utils',
            'class-sanitize',
            'class-changelog',
            'class-required-php-extensions',
            'assets/class-admin-assets',
            'assets/class-assets',
            'assets/screens/class-assets-dashboard',
        ];
        foreach ( $src_files as $file ) {
            require_once TMM_WP_PLUGINS_CORE_DIR . 'src/' . $file . '.php';
        }
    }

    /**
     * Loads textdomain.
     */
    private function load_plugin_textdomain() {
        load_plugin_textdomain(
            'tmm-wp-plugins-core',
            false,
            dirname( TMM_WP_PLUGINS_CORE_BASENAME ) . '/languages'
        );
    }

    /**
     * Adds polyfills for earlier versions of PHP for function used within the plugin.
     *
     * Contains the list of all necessary polyfills for all modern functions used within any of the plugin.
     *
     * @noinspection PhpCSValidationInspection
     */
    private function add_polyfills() {
        if ( ! function_exists( 'str_starts_with' ) ) {
            function str_starts_with( $haystack, $needle ) {
                return 0 === strncmp( $haystack, $needle, strlen( $needle ) );
            }
        }
        if ( ! function_exists( 'str_contains' ) ) {
            function str_contains( $haystack, $needle ) {
                // Match native PHP 8 behaviour: an empty needle returns true.
                return '' === $needle || false !== strpos( $haystack, $needle );
            }
        }
    }
}
