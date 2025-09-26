<?php
// Make sure Composer autoload is included in your main plugin file
// require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

/**
 * Generate QR Code from user-assigned page ID
 */
function rr_user_page_qrcode( $atts ) {
    $atts = shortcode_atts( array(
        'size'     => 250,                 // default QR code size
        'meta_key' => 'invitation_page_id', // user meta key for page ID
    ), $atts );

    $user_id = get_current_user_id();
    if ( ! $user_id ) {
        return 'Please log in to see your QR code.';
    }

    // Get page ID from user meta
      $page_id = get_user_meta( $user_id, 'invitation_page_id', true );
    if ( ! $page_id ) {
        return 'No page assigned to your profile.';
    }

    // Get the permalink of the page
    $page_url = get_permalink( $page_id );
    if ( ! $page_url ) {
        return 'Invalid page assigned.';
    }

    // ✅ Generate QR code using Endroid v4
    $qr = QrCode::create($page_url)
                ->setSize( (int) $atts['size'] );

    $writer = new PngWriter();
    $result = $writer->write($qr);

    // Convert QR code to Base64 for embedding in <img>
    $dataUri = $result->getDataUri();


    $bg_url = get_the_post_thumbnail_url( $page_id, 'full' );
    if ( ! $bg_url ) {
        $bg_url = plugin_dir_url( __FILE__ ) . 'assets/default-bg.jpg';
    }


    // ✅ Output buffering to capture template HTML
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'templates/qr-invitation-4.php';
    return ob_get_clean();
}
add_shortcode( 'user_qrcode', 'rr_user_page_qrcode' );



