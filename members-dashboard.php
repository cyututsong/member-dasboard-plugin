<?php
/*
Plugin Name: Members Dashboard
Description: An Ultimate RSVP plugin for all RSVP websites.
Version: 1.5
Author: DaddyDevs
Author URI: https://yourwebsite.com
Text Domain: members-dashboard
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Prevent direct access
}

/**
 * Includes
 */
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php'; 
require_once plugin_dir_path( __FILE__ ) . 'mbd-qrgenerator.php'; 
require_once plugin_dir_path( __FILE__ ) . 'mbd-usersmetafields.php'; 
require_once plugin_dir_path( __FILE__ ) . 'mbd-shortcodes.php'; 
require_once plugin_dir_path( __FILE__ ) . 'mbd-gfentriesdisplay.php'; 
require_once plugin_dir_path( __FILE__ ) . 'mbd-momentcapture.php'; 

/**
 * Login redirect for subscribers
 */
if ( ! function_exists( 'md_custom_login_redirect' ) ) {
    function md_custom_login_redirect( $redirect_to, $request, $user ) {
        if ( isset( $user->roles ) && in_array( 'subscriber', (array) $user->roles, true ) ) {
            return home_url( '/members' );
        }
        return $redirect_to;
    }
}
add_filter( 'login_redirect', 'md_custom_login_redirect', 10, 3 );

/**
 * Restrict /members page to logged-in users
 */
if ( ! function_exists( 'md_restrict_members_page' ) ) {
    function md_restrict_members_page() {
        if ( is_page( 'members' ) && ! is_user_logged_in() ) {
            wp_redirect( wp_login_url() );
            exit;
        }
    }
}
add_action( 'template_redirect', 'md_restrict_members_page' );


function md_track_page_views() {
    if ( is_singular() && ! is_admin() ) {
        global $post;
        $post_id = $post->ID;
        $views = get_post_meta( $post_id, 'rr_page_views', true );

        if ( ! $views ) {
            $views = 0;
        }

        $views++;
        update_post_meta( $post_id, 'rr_page_views', $views );
    }
}
add_action( 'wp_head', 'md_track_page_views' );


// Frontend assets
function md_frontend_assets() {
    // css (example)
    wp_enqueue_style(
        'members-dashboard-css',
        plugin_dir_url( __FILE__ ) . 'members-dashboard.css',
        [],
        '1.0'
    );

    // html2canvas on the front-end (CDN)
    wp_enqueue_script(
        'html2canvas',
        'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js',
        [],
        '1.4.1',
        true
    );

    // Invite screenshot script (depends on html2canvas + jQuery)
    wp_enqueue_script(
        'md-invite-screenshot',
        plugin_dir_url( __FILE__ ) . 'invite-screenshot.js',
        [ 'jquery', 'html2canvas' ],
        '1.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'md_frontend_assets' );



/**
 * Enqueue frontend assets
 */
if ( ! function_exists( 'md_frontend_assets' ) ) {
    function md_frontend_assets() {
        // Members dashboard CSS
        wp_enqueue_style(
            'members-dashboard-css',
            plugin_dir_url( __FILE__ ) . 'members-dashboard.css',
            [],
            '1.0'
        );

        // GF Entries (Frontend)
        wp_enqueue_script(
            'md-gf-entries',
            plugin_dir_url( __FILE__ ) . 'gf-entries.js',
            [ 'jquery' ],
            '1.2.1',
            true
        );

        wp_localize_script( 'md-gf-entries', 'gfEntriesAjax', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        ] );

    }
}
add_action( 'wp_enqueue_scripts', 'md_frontend_assets' );


function enqueue_ajax_pagination_script() {
    wp_enqueue_script('jquery'); // Enqueue jQuery

    wp_enqueue_script(
        'moment-pagination',
        plugin_dir_url( __FILE__ ) . 'moments-pagination.js',
        [ 'jquery' ],
        '1.0',
        true
    );


    // Localize the script to provide the Ajax URL and nonce for security
    wp_localize_script('moment-pagination', 'ajaxpagination', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-pagination-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_pagination_script');