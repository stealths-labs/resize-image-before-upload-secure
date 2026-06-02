<?php
/**
 * Plugin Name: Resize Image Before Upload (Secure Fork)
 * Plugin URI:  https://github.com/stealths-labs/resize-image-before-upload-secure
 * Description: Client-side image resize before upload. Hardened fork with all ads/promotions and the remote notification feed removed, and the bundled vulnerable Swiper library (CVE-2026-27212) deleted.
 * Version:     1.0.4-secure.1
 * Text Domain: ribu
 * Author:      Stealths Works
 * Author URI:  https://stealths.works
 * Update URI:  false
 * Requires PHP: 7.4
 *
 * @package Resize-Image-Before-Upload-Secure
 *
 * ----------------------------------------------------------------------------
 * SECURITY FORK NOTES
 * ----------------------------------------------------------------------------
 * Forked from: "Resize Image Before Upload" by TMM Technology, v1.0.4
 *              https://wordpress.org/plugins/resize-image-before-upload/
 *
 * Why we forked:
 *   The upstream plugin (latest version) bundles Swiper 8.2.4, which carries
 *   CVE-2026-27212 (CRITICAL). Swiper is used solely to render the vendor's
 *   promotional "drip notifications" carousel, which is fetched hourly from a
 *   remote endpoint (wpplugins-midlayer.tmm-technology.com) and shown via
 *   admin_notices. The upstream author has not refreshed the bundled library.
 *
 * Changes in this fork:
 *   - Disabled the Notifications class (remote ad/promo feed + admin_notices ads).
 *   - Removed the Swiper registration and its enqueue dependencies.
 *   - Deleted the bundled Swiper library (vendor/tmmtech/wp-plugins-core/libs/swiper).
 *   - "Update URI: false" so WordPress never overwrites this fork from wp.org.
 *
 * The core feature (client-side image resize before upload) is unchanged.
 * See SECURITY-vuln-exceptions.md at the repo root for the full record.
 * ----------------------------------------------------------------------------
 */

namespace Resize_Image_Before_Upload;

/**
 * Main Resize_Image_Before_Upload class.
 */
final class Resize_Image_Before_Upload {

    /**
     * AJAX class.
     *
     * @var AJAX
     */
    public static $ajax;

    /**
     * Options.
     *
     * @var Options
     */
    public static $options;

    /**
     * Plugin slug.
     *
     * @var string
     */
    public static $slug;

    /**
     * Plugin version.
     *
     * @var string
     */
    public static $version;

    /**
     * Plugin name.
     *
     * @var string
     */
    public static $name;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->import_initial_files();

        add_action(
            'plugins_loaded',
            function() {
                new \WP_Plugins_Core\WP_Plugins_Core( $this );

                $this->import_plugin_files();

                // Options.
                self::$options = new Options();

                // Adds plugin settings links to plugins admin screen.
                add_filter( 'plugin_action_links_' . RIBU_BASENAME, [ $this, 'plugin_action_links' ] );

                add_action(
                    'init',
                    function () {

                        // Load translations on init (WP 6.7+ warns if loaded earlier).
                        $this->load_plugin_textdomain();

                        // Assets.
                        new Assets\Assets();

                        // Menu.
                        new Admin_Menu();

                        // AJAX Handler.
                        self::$ajax = new AJAX();
                    }
                );
            }
        );
    }

    /**
     * Defines constants.
     */
    private function define_constants() {
        require_once __DIR__ . '/data/constants.php';

        /**
         * Plugin name.
         */
        self::$name = get_file_data( RIBU_FILE, [ 'Plugin Name' ] )[0];

        /**
         * Plugin slug. PINNED to the original slug (not derived from the renamed
         * fork directory) so the option-key namespace stays aligned.
         *
         * Option keys in data/options.php are hard-coded with the
         * `resize-image-before-upload-setting-*` prefix, and Options::get()/set()
         * rebuild short names as "{slug}-{name}". If the slug followed the renamed
         * directory (`resize-image-before-upload-secure`) the rebuilt key would no
         * longer exist in data/options.php and Options::validation_option() would
         * throw an uncaught "Unknown option name" during enqueue, breaking pages.
         * Pinning also preserves any settings the original plugin already stored.
         */
        self::$slug = 'resize-image-before-upload';

        /**
         * Plugin version.
         */
        self::$version = RIBU_VERSION;
    }

    /**
     * Imports initial plugin files:
     *
     * - WP plugins core.
     */
    private function import_initial_files() {
        $files = [
            'vendor/autoload_packages',
            'vendor/tmmtech/wp-plugins-core/wp-plugins-core',
            'vendor/woocommerce/action-scheduler/action-scheduler',
        ];
        foreach ( $files as $file ) {
            require_once RIBU_DIR . $file . '.php';
        }
    }

    /**
     * Imports plugin files.
     */
    private function import_plugin_files() {
        $src_files = [
            'assets/class-assets',
            'assets/screens/class-assets-main-screen',
            'class-admin-menu',
            'class-options',
            'class-ajax',
        ];
        foreach ( $src_files as $file ) {
            require_once RIBU_DIR . 'src/' . $file . '.php';
        }
    }

    /**
     * Loads text-domain.
     */
    private function load_plugin_textdomain() {
        load_plugin_textdomain(
            'ribu',
            false,
            dirname( RIBU_BASENAME ) . '/languages'
        );
    }

    /**
     * Show settings link on the plugin screen.
     *
     * @param mixed $links Plugin Action links.
     *
     * @return array
     */
    public function plugin_action_links( $links ) {
        $action_links = array(
            'settings' => '<a href="' . admin_url( 'upload.php?page=ribu' ) . '" aria-label="' .
                esc_attr__( 'Settings', 'ribu' ) . '">' . esc_html__( 'Settings', 'ribu' ) . '</a>',
        );
        return array_merge( $action_links, $links );
    }
}
new Resize_Image_Before_Upload();
