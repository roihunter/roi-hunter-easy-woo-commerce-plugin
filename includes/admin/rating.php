<?php 

/**
 * Change the admin footer text on ROI Hunter Easy admin page.
 *
 * @since  1.0.2
 * @param  string $footer_text
 * @return string
 */
function roi_hunter_easy_admin_footer_text( $footer_text ) {

    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        return $footer_text;
    }

    $helper = new RH_Easy_Helper();
    $current_screen = get_current_screen();

    // Check to make sure we're on a ROI Hunter Easy admin page.
    if ( isset( $current_screen->id ) && $current_screen->id === 'toplevel_page_roi-hunter-easy'  ) {
        // Change the footer text
        if ( ! $helper->get_option('admin_footer_text_rated') ) {
            $footer_text = sprintf(
                /* translators: 1: ROI Hunter Easy 2:: five stars */
                __( 'If you like %1$s please leave us a %2$s rating. A huge thanks in advance!', 'roi-hunter-easy' ),
                sprintf( '<strong>%s</strong>', esc_html__( 'ROI Hunter Easy', 'roi-hunter-easy' ) ),
                '<a href="https://wordpress.org/support/plugin/roi-hunter-easy-for-woocommerce/reviews?rate=5#new-post" target="_blank" class="rhe-rating-link" data-rated="' . esc_attr__( 'Thanks :)', 'roi-hunter-easy' ) . '">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
            );
            wc_enqueue_js(
                "jQuery( 'a.rhe-rating-link' ).click( function() {
                    jQuery.post( '" . admin_url( 'admin-ajax.php' ) . "', { action: 'roi_hunter_easy_rated' } );
                    jQuery( this ).parent().text( jQuery( this ).data( 'rated' ) );
                });"
            );
        } else {
            $footer_text = __( 'Thank you for using ROI Hunter Easy.', 'roi-hunter-easy' );
        }
    }

    return $footer_text;
}
add_filter( 'admin_footer_text', 'roi_hunter_easy_admin_footer_text', 1 );

/**
 * Save the option when the plugin already rated.
 *
 * @return void
 * @since 1.0.2
 */
function roi_hunter_easy_rated() {
    if ( ! current_user_can( 'manage_woocommerce' ) ) {
        wp_die( -1 );
    }
    $helper = new RH_Easy_Helper();
    $helper->update_options( array( 'admin_footer_text_rated' => 1) );
    wp_die();
}
add_action( 'wp_ajax_roi_hunter_easy_rated', 'roi_hunter_easy_rated' );