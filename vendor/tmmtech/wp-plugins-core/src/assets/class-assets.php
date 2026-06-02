<?php
/**
 * Assets
 *
 * Loads assets (JS, CSS), adds data for them.
 *
 * @package WP-Plugins-Core
 */

namespace WP_Plugins_Core\Assets;

/**
 * Assets class.
 */
final class Assets {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_libs' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_libs' ] );

        add_action( 'admin_enqueue_scripts', [ 'WP_Plugins_Core\Assets\Admin', 'init' ] );
    }


    /**
     * Registers the libs.
     */
    public function register_libs() {

        // 1. Swiper.
        // FORK (secure): removed. Swiper (v8.2.4, CVE-2026-27212) was only used to
        // render the vendor's promotional "drip notifications" carousel, which this
        // fork disables. The library has been deleted from libs/swiper/.

        // SweetAlert2.

        // 1.1. Style.

        wp_register_style(
            'tmm-wp-plugins-core-lib-sweetalert2',
            TMM_WP_PLUGINS_CORE_URL . 'libs/sweetalert2/sweetalert2.min.css',
            [],
            '11.26.25'
        );

        // 1.2. Script.

        wp_register_script(
            'tmm-wp-plugins-core-lib-sweetalert2',
            TMM_WP_PLUGINS_CORE_URL . 'libs/sweetalert2/sweetalert2.all.min.js',
            [],
            '11.26.25',
            true
        );
    }
}
